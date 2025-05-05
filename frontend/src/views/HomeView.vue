<script setup>
import {ref, computed} from 'vue';
import Header from '@/components/Header.vue';
import ChatView from '@/components/ChatView.vue';
import {useAuthStore} from '@/stores/useAuthStore';

const authStore = useAuthStore();
const isAuthenticated = computed(() => authStore.isLoggedIn);

</script>

<template>
  <div class="min-h-screen bg-black flex flex-col">
    <Header/>

    <main class="flex-grow container mx-auto px-4 py-8">
      <!-- Conteúdo principal -->
      <div v-if="isAuthenticated" class="flex-1">
        <ChatView /> <!-- Componente de chat é renderizado apenas quando autenticado -->
      </div>
      <div v-else class="container mx-auto px-4 py-8">
        <p class="text-amber-100 text-sm">
          status: {{
            isAuthenticated ? 'soldado em prontidão 🫡' : 'soldado abatido ☠️ - faça login para acessar o chat'
          }}
        </p>
      </div>
    </main>
  </div>
</template>
