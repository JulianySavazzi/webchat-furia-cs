<script setup>
import { ref, computed, onMounted } from 'vue';
import apiClient from '@/services/apiClient';
import {useAuthStore} from '@/stores/useAuthStore';

const authStore = useAuthStore()
// const userNow = authStore.user

// Estados
const allUsers = ref([]);
const teamData = ref(null);
const currentChat = ref(null);
const messages = ref([]);
const newMessage = ref('');
const isLoading = ref(false);

// Contatos ordenados (Team primeiro, Bot em segundo, depois outros usuários)
const orderedContacts = computed(() => {
  const contacts = [];

  // 1. Adiciona o time FURIA CS
  if (teamData.value) {
    contacts.push({
      id: teamData.value.id,
      name: teamData.value.name,
      type: 'team',
      avatar: teamData.value.logo || 'https://via.placeholder.com/50'
    });
  }

  // 2. Adiciona o bot (usuário com ID 1)
  const bot = allUsers.value.find(user => user.id === 1);
  if (bot) {
    contacts.push({
      ...bot,
      type: 'bot'
    });
  }

  // 3. Adiciona outros usuários (exceto o bot)
  const otherUsers = allUsers.value.filter(user => user.id !== 1);
  contacts.push(...otherUsers.map(user => ({
    ...user,
    type: 'user'
  })));

  return contacts;
});

// Busca todos os usuários e dados do time
const fetchContacts = async () => {
  try {
    isLoading.value = true;

    // Busca todos os usuários
    const usersResponse = await apiClient.get('user');
    allUsers.value = usersResponse.data;
    console.log(allUsers)
    // Extrai os dados do time FURIA CS do primeiro usuário
    if (usersResponse.data.length > 0 && usersResponse.data[0].teams?.length > 0) {
      teamData.value = usersResponse.data[0].teams[0];
    }
    console.log(teamData)
  } catch (error) {
    console.error('Erro ao carregar contatos:', error);
  } finally {
    isLoading.value = false;
  }
};

// Busca mensagens do chat selecionado
const fetchMessages = async (chat) => {
  currentChat.value = chat;
  try {
    const endpoint = chat.type === 'team'
      ? `chat/messages/team/${chat.id}`
      : `chat/messages/${chat.id}`;

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
    const endpoint = currentChat.value.type === 'team'
      ? `chat/messages/team/${currentChat.value.id}`
      : `chat/messages/${currentChat.value.id}`;

    await apiClient.post(endpoint, {
      content: newMessage.value
    });

    newMessage.value = '';
    await fetchMessages(currentChat.value);
  } catch (error) {
    console.error('Erro ao enviar mensagem:', error);
  }
};

// Inicialização
onMounted(fetchContacts);
</script>

<template>
  <div class="flex h-screen bg-gray-900 text-amber-100">
    <!-- Lista de Contatos -->
    <div class="w-1/4 border-r border-amber-100/20 p-4 overflow-y-auto">
      <h2 class="text-xl font-bold mb-4">Conversas</h2>

      <ul v-if="!isLoading">
        <li
          v-for="contact in orderedContacts"
          :key="`${contact.type}-${contact.id}`"
          @click="fetchMessages(contact)"
          class="p-3 hover:bg-amber-100/10 rounded-md cursor-pointer flex items-center gap-3"
          :class="{
            'bg-amber-100/20': currentChat?.id === contact.id && currentChat?.type === contact.type,
            'text-green-400': contact.type === 'bot'
          }"
        >
          <img
            :src="contact.avatar || 'https://via.placeholder.com/50'"
            class="w-8 h-8 rounded-full object-cover"
          />

          <div class="flex-1 min-w-0">
            <p class="truncate font-medium">{{ contact.name }}</p>
            <p class="text-xs text-amber-100/50 truncate">
              {{ contact.type === 'team' ? 'Grupo FURIA' :
              contact.type === 'bot' ? 'Chat Bot' : 'Usuário' }}
            </p>
          </div>
        </li>
      </ul>

      <div v-else class="flex justify-center py-8">
        <svg class="animate-spin h-6 w-6 text-amber-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>

    <!-- Área de Chat -->
    <div class="flex-1 flex flex-col">
      <!-- Cabeçalho -->
      <div v-if="currentChat" class="border-b border-amber-100/20 p-4 flex items-center gap-3">
        <img
          :src="currentChat.avatar || 'https://via.placeholder.com/50'"
          class="w-10 h-10 rounded-full object-cover"
        />
        <div>
          <h3 class="font-bold">{{ currentChat.name }}</h3>
          <p class="text-sm text-amber-100/50">
            {{ currentChat.type === 'team' ? 'Grupo FURIA' :
            currentChat.type === 'bot' ? 'Chat com o Bot' : 'Chat privado' }}
          </p>
        </div>
      </div>
      <div v-else class="border-b border-amber-100/20 p-4">
        <h3 class="font-bold">Selecione uma conversa</h3>
      </div>

      <!-- Mensagens -->
      <div class="flex-1 p-4 overflow-y-auto">
        <div v-if="currentChat" class="space-y-4">
          <div
            v-for="message in messages"
            :key="message.id"
            :class="{ 'flex justify-end': message.is_own, 'flex justify-start': !message.is_own }"
          >
            <div
              class="flex gap-3 max-w-[80%]"
              :class="{ 'flex-row-reverse': message.is_own }"
            >
              <img
                v-if="!message.is_own"
                :src="message.sender_avatar || 'https://via.placeholder.com/40'"
                class="w-8 h-8 rounded-full object-cover mt-1"
              />

              <div
                class="flex flex-col"
                :class="{ 'items-end': message.is_own, 'items-start': !message.is_own }"
              >
                <div class="font-bold flex items-center gap-2">
                  <span v-if="!message.is_own">{{ message.sender_name }}</span>
                  <span v-if="message.is_bot" class="text-xs bg-amber-100/20 px-2 py-0.5 rounded">BOT</span>
                  <span v-if="message.is_own">Você</span>
                </div>

                <p
                  class="p-3 rounded-lg"
                  :class="{
                    'bg-amber-100/20': !message.is_own,
                    'bg-amber-100 text-black': message.is_own
                  }"
                >
                  {{ message.content }}
                </p>

                <span class="text-xs text-amber-100/50 mt-1">
                  {{ new Date(message.created_at).toLocaleTimeString() }}
                </span>
              </div>
            </div>
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
