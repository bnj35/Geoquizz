import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    'user_id': 'b2a93c47-da9f-4667-88ee-cf9e14b0b7d5',
    'user_stats_id': null,
    'score_total': 0,
    'score_moyen': 0,
    'nb_parties': 0,
    'meilleur_coup': 0,
    'pire_coup': 0,
  }),
});

