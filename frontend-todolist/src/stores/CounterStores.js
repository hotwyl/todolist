import { defineStore } from 'pinia'

export const useCounterStore = defineStore('counter', {
    // !! state -> propriedades reativas
    state() {
        return {
            count: 0
        }
    },

    // !! actions -> methods
    actions: {
        increment() {
            this.count++
        }
    },

    // !! getters -> propriedades computadas
    getters: {
        showCout() {
            return this.count
        },
        doubleCount() {
            return this.count * 2
        }
    },


})