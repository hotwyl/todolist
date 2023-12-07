<!-- src/components/Menu.vue -->
<template>
  <nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex items-center justify-between">
      <router-link to="/" class="text-white text-lg font-bold">Início</router-link>
      <router-link to="/about" class="text-white">Sobre</router-link>
      <router-link to="/login" class="text-white">Login</router-link>
      <router-link to="/register" class="text-white">Registro</router-link>
      <!-- Adicione mais itens de menu conforme necessário -->
    </div>
  </nav>
</template>

<script>
export default {
  // Lógica do componente
};
</script>

<style scoped>
/* Estilos específicos do componente Menu */
nav {
  background-color: #333;
  padding: 1rem;
}

.container {
  max-width: 100%;
  margin: 0 auto;
}

a {
  color: white;
  margin-right: 1rem;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
