// Login.vue
import axios from '@/axios';
import { showSuccess, showError } from '@/services/notification';

export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/login', {
          email: this.email,
          password: this.password,
        });
        // Lógica após o login (redirecionar, salvar token, etc.)
        showSuccess('Login bem-sucedido!');
      } catch (error) {
        showError('Erro ao fazer login. Verifique suas credenciais.');
      }
    },
  },
};
