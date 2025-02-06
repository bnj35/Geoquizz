<script setup>
import { useUserStore } from "@/stores/userStore.js";
import {getUserStats} from "@/utils/game/GameSystem.js";

const userStore = useUserStore();

getUserStats(userStore.user_id).then((data) => {
  userStore.score_total = data.stats.score_total;
  userStore.score_moyen = data.stats.score_moyen;
  userStore.nb_parties = data.stats.nb_parties;
  userStore.meilleur_coup = data.stats.meilleur_score;
  userStore.pire_coup = data.stats.pire_coups;
});
</script>

<template>
  <div id="user_stats">
    <h2>Statistiques de : {{ userStore.user_id }}</h2>
    <p>Score total : {{ userStore.score_total }}</p>
    <p>Score moyen : {{ userStore.score_moyen }}</p>
    <p>Nombre de parties : {{ userStore.nb_parties }}</p>
    <p>Meilleur score : {{ userStore.meilleur_coup }}</p>
    <p>Pire score : {{ userStore.pire_coup }}</p>
  </div>
</template>

<style scoped>
/* Add your styles here */
</style>
