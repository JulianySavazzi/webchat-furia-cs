<script setup>
import { ref } from 'vue';
import Header from '@/components/Header.vue';

const isAuthenticated = ref(false);

const handleLogin = () => {
  console.log('Login realizado');
  isAuthenticated.value = true;
};

const handleSignup = () => {

  console.log('Conta criada');
  isAuthenticated.value = false;
};

const handleLogout = () => {
  console.log('Logout realizado');
  isAuthenticated.value = false;
};
</script>

<template>
  <div class="min-h-screen bg-black flex flex-col">
    <Header
      :is-authenticated="isAuthenticated"
      @login="handleLogin"
      @signup="handleSignup"
      @logout="handleLogout"
    />

    <main class="flex-grow container mx-auto px-4 py-8">
      <!-- Conteúdo principal -->
      <p class="text-amber-100 text-sm">
        status: {{ isAuthenticated ? 'soldado em prontidão 🫡' : 'soldado abatido ☠️' }}
      </p>
    </main>
  </div>
</template>
