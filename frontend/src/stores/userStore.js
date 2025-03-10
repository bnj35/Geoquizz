import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
  state: () => ({
    user_token: null,
    user_id: null,
    user_stats_id: null,
    score_total: 0,
    score_moyen: 0,
    nb_parties: 0,
    meilleur_coup: 0,
    pire_coup: 0,
    toast_error: false,
    toast_message: '',
  }),
  actions: {
    showToast(message) {
      this.toast_message = message;
      this.toast_error = true;
      setTimeout(() => {
        this.toast_error = false;
      }, 3000); // Hide the toast after 3 seconds

    }
  },
  persist: {
    enabled: true,
  },
});
