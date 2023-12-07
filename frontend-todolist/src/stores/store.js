// src/store.js
import { createPinia } from 'pinia';

export const pinia = createPinia();

export const useAuthStore = pinia.defineStore('auth', {
    state: () => ({
        user: null,
        token: null,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        setToken(token) {
            this.token = token;
        },
        logout() {
            this.user = null;
            this.token = null;
        },
    },
});