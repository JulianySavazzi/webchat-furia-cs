<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import apiClient from '@/services/apiClient';

// Estados
const usersAndGroups = ref([]);
const currentChat = ref(null);
const messages = ref([]);
const newMessage = ref('');
const isLoading = ref(false);
const onlineUsers = ref([]);

// Configuração do WebSocket
const setupWebSocket = () => {
  if (!window.Echo) {
    console.error('Laravel Echo não está configurado');
    return;
  }

  // Ouvir mensagens privadas
  window.Echo.private(`user.${authUser.value.id}`)
    .listen('.private.message.sent', (data) => {
      if (currentChat.value?.id === data.sender_id || currentChat.value?.id === data.receiver_id) {
        messages.value.push(data);
      }
    });

  // Ouvir mensagens de grupo
  window.Echo.join(`team.1`) // Time FURIA (ID 1)
    .here((users) => {
      onlineUsers.value = users;
    })
    .joining((user) => {
      if (!user.is_bot) {
        console.log(`${user.name} entrou no chat`);
      }
    })
    .leaving((user) => {
      if (!user.is_bot) {
        console.log(`${user.name} saiu do chat`);
      }
    })
    .listen('.team.message.sent', (data) => {
      if (currentChat.value?.id === data.team_id) {
        messages.value.push(data);
      }
    });
};

// Busca usuários/grupos disponíveis
const fetchUsersAndGroups = async () => {
  try {
    isLoading.value = true;
    const response = await apiClient.get('/chats');
    usersAndGroups.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar chats:', error);
  } finally {
    isLoading.value = false;
  }
};

// Busca mensagens do chat selecionado
const fetchMessages = async (chatId) => {
  try {
    const endpoint = currentChat.value.type === 'group'
      ? `/teams/${chatId}/messages`
      : `/chats/${chatId}/messages`;

    const response = await apiClient.get(endpoint);
    messages.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar mensagens:', error);
  }
};

// Envia mensagem
const sendMessage = async () => {
  if (!newMessage.value.trim() || !currentChat.value) return;

  try {
    const endpoint = currentChat.value.type === 'group'
      ? `/teams/${currentChat.value.id}/messages`
      : `/chats/${currentChat.value.id}/messages`;

    await apiClient.post(endpoint, {
      content: newMessage.value,
    });

    newMessage.value = '';
  } catch (error) {
    console.error('Erro ao enviar mensagem:', error);
  }
};

// Atualiza mensagens quando muda o chat
watch(currentChat, (newChat) => {
  if (newChat) {
    messages.value = [];
    fetchMessages(newChat.id);
  }
});

// Simula usuário autenticado (substitua pelo seu sistema real)
const authUser = ref({
  id: 1,
  name: 'Você'
});

// Inicialização
onMounted(() => {
  fetchUsersAndGroups();
  setupWebSocket();
});

// Limpeza ao sair
onUnmounted(() => {
  if (window.Echo) {
    window.Echo.leave(`user.${authUser.value.id}`);
    window.Echo.leave(`team.1`);
  }
});
</script>

<template>
  <div class="flex h-screen bg-gray-900 text-amber-100">
    <!-- Lateral Esquerda: Lista de usuários/grupos -->
    <div class="w-1/4 border-r border-amber-100/20 p-4 overflow-y-auto">
      <h2 class="text-xl font-bold mb-4">Conversas</h2>
      <div v-if="!isLoading">
        <div class="mb-4">
          <h3 class="text-sm font-semibold text-amber-100/70 mb-2">Usuários Online ({{ onlineUsers.length }})</h3>
          <div v-for="user in onlineUsers" :key="user.id" class="flex items-center gap-2 mb-1">
            <div class="h-2 w-2 rounded-full bg-green-500"></div>
            <span>{{ user.name }}</span>
          </div>
        </div>

        <ul>
          <li
            v-for="chat in usersAndGroups"
            :key="chat.id"
            @click="currentChat = chat"
            class="p-3 hover:bg-amber-100/10 rounded-md cursor-pointer"
            :class="{ 'bg-amber-100/20': currentChat?.id === chat.id }"
          >
            {{ chat.name }}
            <span class="text-xs block text-amber-100/50">{{ chat.type === 'group' ? 'Grupo' : 'Usuário' }}</span>
          </li>
        </ul>
      </div>
      <div v-else class="flex justify-center py-8">
        <svg class="animate-spin h-6 w-6 text-amber-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>

    <!-- Lateral Direita: Área de mensagens -->
    <div class="flex-1 flex flex-col">
      <!-- Cabeçalho do chat -->
      <div v-if="currentChat" class="border-b border-amber-100/20 p-4">
        <h3 class="font-bold">{{ currentChat.name }}</h3>
        <p class="text-sm text-amber-100/50">
          {{ currentChat.type === 'group' ? 'Grupo' : 'Chat privado' }}
          <span v-if="currentChat.type === 'group'" class="ml-2">
            ({{ onlineUsers.length }} online)
          </span>
        </p>
      </div>
      <div v-else class="border-b border-amber-100/20 p-4">
        <h3 class="font-bold">Selecione uma conversa</h3>
      </div>

      <!-- Mensagens -->
      <div class="flex-1 p-4 overflow-y-auto">
        <div v-if="currentChat">
          <div
            v-for="message in messages"
            :key="message.id"
            class="mb-4"
            :class="{ 'ml-8': message.sender_id === authUser.id }"
          >
            <div class="font-bold">
              {{ message.sender_id === authUser.id ? 'Você' : message.sender_name }}
              <span v-if="message.is_bot" class="text-xs bg-amber-100/20 px-2 py-1 rounded ml-2">BOT</span>
            </div>
            <p>{{ message.content }}</p>
            <span class="text-xs text-amber-100/50">{{ new Date(message.timestamp).toLocaleTimeString() }}</span>
          </div>
        </div>
        <div v-else class="h-full flex items-center justify-center text-amber-100/50">
          Selecione um chat para começar.
        </div>
      </div>

      <!-- Input de mensagem -->
      <div v-if="currentChat" class="p-4 border-t border-amber-100/20">
        <form @submit.prevent="sendMessage" class="flex gap-2">
          <input
            v-model="newMessage"
            type="text"
            placeholder="Digite uma mensagem..."
            class="flex-1 bg-black/50 border border-amber-100/30 rounded-md px-4 py-2 text-amber-100 focus:outline-none focus:ring-1 focus:ring-amber-200/50"
          />
          <button
            type="submit"
            class="bg-amber-100 hover:bg-amber-200 text-black font-medium px-4 py-2 rounded-md transition duration-200"
          >
            Enviar
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
