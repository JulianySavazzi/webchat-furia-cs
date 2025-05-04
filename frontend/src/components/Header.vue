<!-- components/Header.vue -->
<script setup>
import CounterStrikeIcon from "@/components/icons/CounterStrikeIcon.vue";

defineProps({
  isAuthenticated: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['login', 'signup', 'logout']);
</script>

<template>
  <header class="bg-black border-b border-amber-100/20 py-4 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto flex justify-between items-center">
      <!-- Logo/Título -->
      <div class="flex items-center space-x-4">
        <!-- Link para Counter-Strike.net com ícone SVG -->
        <a
          href="https://www.counter-strike.net"
          target="_blank"
          rel="noopener noreferrer"
          class="hover:opacity-80 transition-opacity flex items-center"
          title="Site oficial da Counter-Strike"
        >
          <CounterStrikeIcon class="mr-2" />
          <span class="sr-only">Counter-Strike.net</span>
        </a>
        <h1 class="text-amber-100 text-xl sm:text-2xl font-bold">
          Webchat FURIA CS
        </h1>
      </div>

      <!-- Botões condicionais -->
      <div class="flex space-x-2 sm:space-x-4">
        <template v-if="!isAuthenticated">
          <button
            @click="emit('login')"
            class="bg-transparent hover:bg-amber-100/10 text-amber-100 font-medium py-2 px-3 sm:px-4 border border-amber-100/30 rounded-md text-sm sm:text-base transition duration-200"
          >
            Login
          </button>
          <button
            @click="emit('signup')"
            class="bg-amber-100 hover:bg-amber-200 text-black font-medium py-2 px-3 sm:px-4 border border-amber-100 rounded-md text-sm sm:text-base transition duration-200"
          >
            Criar Conta
          </button>
        </template>

        <button
          v-else
          @click="emit('logout')"
          class="bg-transparent hover:bg-red-500/10 text-red-400 font-medium py-2 px-3 sm:px-4 border border-red-400/30 rounded-md text-sm sm:text-base transition duration-200"
        >
          Logout
        </button>
      </div>
    </div>
  </header>
</template>
