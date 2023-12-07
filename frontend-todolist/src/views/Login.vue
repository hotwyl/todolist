<!-- src/views/Login.vue -->
<template>
  <div class="min-h-screen flex items-center justify-center">
    <div  v-if="isLoading"><LoadingSpinner /></div>
    <div class="bg-white shadow-md p-8 rounded-md" v-else>
      <h2 class="text-2xl font-bold mb-6">Login</h2>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
          <input v-model="email" type="email" id="email" name="email" class="border rounded-md px-3 py-2 w-full" required :disabled="isLoading">
        </div>
        <div class="mb-4">
          <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
          <input v-model="password" type="password" id="password" name="password" class="border rounded-md px-3 py-2 w-full" required :disabled="isLoading">
        </div>
        <button type="submit" class="button bg-blue-500 text-white px-4 py-2 rounded-md" :disabled="isLoading">Entrar</button>
      </form>
      <p>Não possue uma conta? <router-link to="/register">Registrar-se</router-link></p>
    </div>
  </div>
</template>

<script>
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Swal from 'sweetalert2';
import {useAuth} from '@/stores/auth.js';

export default {
  components: {
    LoadingSpinner,
  },
  data() {
    return {
      email: '',
      password: '',
      isLoading: false,
    };
  },
  methods: {
    async login() {
      // Implementar lógica de chamada ao backend usando Axios
      try {
        this.isLoading = true;
        //validar se os campos estão preenchidos
        if(this.email == '' || !this.isValidEmail(this.email)){
          Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo email com um email válido.',
            timerProgressBar: true,
            timer: 1500
          });
          return;
        }

        if(this.password == ''){
          Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo senha com uma senha válida.',
            timerProgressBar: true,
            timer: 1500
          });
          return;
        }

        // Substitua a URL pela rota correta do seu backend Laravel
        const response = await this.$axios.post('/login', {
          email: this.email,
          password: this.password,
        });

        // Lógica após o registro bem-sucedido (redirecionar, exibir mensagem, etc.)
        if(response.data.status == true){
          Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: response.data.message,
            timerProgressBar: true,
            timer: 3000
          });

          console.log(response.data);

          //salvar token no localstorage
          useAuth().setToken(response.data.content.token);
          useAuth().setUser(response.data.content.nome);
        }
          // Após um registro bem-sucedido
          this.$router.push('/'); // Redireciona para a rota de home
      
        // Lógica após o login bem-sucedido (redirecionar, salvar token, etc.)
        // console.log(response.data);
      } catch (error) {
        // Lógica para lidar com erros (exibir mensagem, limpar campos, etc.)
        if(error.response.data.errors){
          Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: error.response.data.errors,
            timerProgressBar: true,
            timer: 3000
          });
          return;
        }
        // console.error('Erro no login:', error.response.data.message);
      } finally {
        this.isLoading = false;
      }
    },
    isValidEmail(email) {
        // Lógica de validação de e-mail simples
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
  },
  created() {
  },
  mounted() {
  },
  updated() {
  }
};
</script>

<style scoped>
/* Estilize conforme necessário */
.min-h-screen {
  margin-top: 10vh;
}

.flex {
  display: flex;
}

.items-center {
  align-items: center;
}

.justify-center {
  justify-content: center;
}

.bg-white {
  background-color: #fff;
}

.shadow-md {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.p-8 {
  padding: 2rem;
}

.rounded-md {
  border-radius: 0.375rem;
}

.text-2xl {
  font-size: 1.5rem;
  line-height: 2rem;
  font-weight: 600;
}

.mb-6 {
  margin-bottom: 1.5rem;
}

.text-sm {
  font-size: 0.875rem;
  line-height: 1.25rem;
}

.mb-2 {
  margin-bottom: 0.5rem;
}

.border {
  border: 1px solid #e5e7eb;
}

.px-3 {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}

.py-2 {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.w-full {
  width: 100%;
}

.bg-blue-500 {
  background-color: #3b82f6;
}

.text-white {
  color: #fff;
}

.px-4 {
  padding-left: 1rem;
  padding-right: 1rem;
}

.py-2 {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.rounded-md {
  border-radius: 0.375rem;
}

.button{
  margin-top: 10px;
}
</style>
