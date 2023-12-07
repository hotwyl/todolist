<!-- src/components/Menu.vue -->
<template>
  <nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex items-center justify-between">
      <router-link to="#" class="text-white text-lg font-bold">Home</router-link>
      <router-link to="#" class="text-white">Listagem</router-link>
      <router-link to="#" class="text-white">Cadasto</router-link>
      <router-link to="#" class="text-white">Edição</router-link>
      <router-link to="#" class="text-white">Exclusão</router-link>
      <router-link to="#" class="text-white">Consulta</router-link>
      <router-link to="#" class="text-white">Sobre</router-link>
      <router-link to="#" class="text-white">Contato</router-link>
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
