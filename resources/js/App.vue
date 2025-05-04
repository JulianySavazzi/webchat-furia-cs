<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
    console.log('Componente App montado')
})

defineOptions({
    name: 'App'
})
</script>
