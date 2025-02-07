import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    'user_token': null,
    'user_id': null,
    'user_stats_id': null,
    'score_total': 0,
    'score_moyen': 0,
    'nb_parties': 0,
    'meilleur_coup': 0,
    'pire_coup': 0,


  }),
});
