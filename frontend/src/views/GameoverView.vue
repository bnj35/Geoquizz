<script setup>
  import {useGameStore} from "@/stores/gameStore.js";

  const gameStore = useGameStore();
</script>

<template>
  <div id="game_end">
    <h1>Partie terminée</h1>
    <p>Score : {{ gameStore.totalScore }}</p>
    <p>Serie jouée : {{ gameStore.themePlayed }}</p>
    <router-link to="/" class="text-green-200">Retour à l'accueil</router-link>
  </div>
</template>

<style scoped>

</style>
