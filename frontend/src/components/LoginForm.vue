<template>
  <div class="max-w-md mx-auto p-6 bg-black/50 border border-amber-100/20 rounded-lg">
    <h2 class="text-2xl font-bold text-amber-100 mb-6">Login</h2>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <!-- Campo de Usuário -->
      <div>
        <label for="username" class="block text-sm font-medium text-amber-100 mb-1">Usuário</label>
        <input
          v-model="form.username"
          id="username"
          type="text"
          class="w-full px-4 py-2 bg-black/70 border border-amber-100/30 rounded-md text-amber-100 focus:border-amber-300 focus:ring focus:ring-amber-200/50"
          :class="{ 'border-red-500': errors.username }"
          placeholder="Digite seu usuário"
        >
        <p v-if="errors.username" class="mt-1 text-sm text-red-400">{{ errors.username }}</p>
      </div>

      <!-- Campo de Senha -->
      <div>
        <label for="password" class="block text-sm font-medium text-amber-100 mb-1">Senha</label>
        <input
          v-model="form.password"
          id="password"
          type="password"
          class="w-full px-4 py-2 bg-black/70 border border-amber-100/30 rounded-md text-amber-100 focus:border-amber-300 focus:ring focus:ring-amber-200/50"
          :class="{ 'border-red-500': errors.password }"
          placeholder="Digite sua senha"
        >
        <p v-if="errors.password" class="mt-1 text-sm text-red-400">{{ errors.password }}</p>
      </div>

      <!-- Botão de Submit -->
      <button
        type="submit"
        class="w-full bg-amber-100 hover:bg-amber-200 text-black font-medium py-2 px-4 rounded-md transition duration-200"
        :disabled="isLoading"
      >
        <span v-if="!isLoading">Entrar</span>
        <span v-else class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Processando...
        </span>
      </button>

      <!-- Mensagem de erro -->
      <p v-if="errorMessage" class="mt-2 text-sm text-red-400">{{ errorMessage }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from '@/path/to/your/apiClient';

const emit = defineEmits(['success', 'error']);

const form = ref({
  username: '',
  password: ''
});

const errors = ref({
  username: '',
  password: ''
});

const isLoading = ref(false);
const errorMessage = ref('');

const validate = () => {
  let valid = true;
  errors.value = { username: '', password: '' };

  if (!form.value.username.trim()) {
    errors.value.username = 'Por favor, insira seu usuário';
    valid = false;
  }

  if (!form.value.password) {
    errors.value.password = 'Por favor, insira sua senha';
    valid = false;
  } else if (form.value.password.length < 8) {
    errors.value.password = 'A senha deve ter pelo menos 8 caracteres';
    valid = false;
  }

  return valid;
};

const handleSubmit = async () => {
  if (!validate()) return;

  isLoading.value = true;
  errorMessage.value = '';

  try {
    // Primeiro obtemos o cookie CSRF (se necessário)
    await apiClient.get('/sanctum/csrf-cookie');

    // Fazemos o login
    const response = await apiClient.post('/login', {
      username: form.value.username,
      password: form.value.password
    });

    // Emite evento de sucesso
    emit('success', response.data);

  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Credenciais inválidas';
    emit('error', error);
  } finally {
    isLoading.value = false;
  }
};
</script>
