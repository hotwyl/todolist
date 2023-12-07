<template>
  <div id="app" class="min-h-screen flex flex-col">
    <header class="bg-gray-800 text-white p-4">
      <Menu />
    </header>
    <main class="flex-grow container">
      <router-view></router-view>
    </main>
    <footer class="bg-gray-800 text-white p-4">
      <Rodape />
    </footer>
  </div>
</template>

<script>
import { RouterLink, RouterView } from 'vue-router';
import Menu from '@/components/Menu.vue';
import Rodape from '@/components/Rodape.vue';

export default {
  components: {
    Menu,
    Rodape
  }
};
</script>

<style>
body {
  margin: 0;
  padding: 0;
  font-family: 'Arial', sans-serif;
  box-sizing: border-box;
}

#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

header,
footer {
  background-color: #333;
  color: white;
  padding: 1rem;
}

main {
  flex-grow: 1;
}

.container {
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
}
</style>
