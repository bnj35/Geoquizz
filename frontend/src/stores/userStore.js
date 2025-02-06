import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    'user_id': 'bcd5b814-1e41-4267-b8f8-459b541d96bf',

  }),
  persist: {
    enabled: true,
  }
});
