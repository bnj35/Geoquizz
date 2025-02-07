<script setup>
  import {useGameStore} from "@/stores/gameStore.js";
  import NavbarComponent from "@/components/NavbarComponent.vue";

  const gameStore = useGameStore();
</script>

<template>
  <navbar-component/>
  <div id="game_end" class="flex flex-col items-center justify-center h-screen gap-4">
    <h1 class="text-gray-800 text-6xl">Partie terminée</h1>
    <p class="text-gray-800 text-4xl">Score : {{ gameStore.totalScore }}</p>
    <p class="text-gray-800 text-2xl">Serie jouée : {{ gameStore.themePlayed }}</p>
    <router-link to="/"><button class="bg-gray-700 py-4 px-10 mt-8 rounded-[1.25rem] text-gray-50 hover:text-gray-100 hover:bg-gray-800 transition-all cursor-pointer">Retour à l'accueil</button></router-link>
  </div>
</template>

<style scoped>

</style>
