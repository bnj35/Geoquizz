<script setup>
import { useUserStore } from "@/stores/userStore.js";
import {getUserStats} from "@/utils/game/GameSystem.js";

const userStore = useUserStore();

getUserStats(userStore.user_id);
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
