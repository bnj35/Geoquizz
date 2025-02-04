<script setup>
import { calculateScore } from "@/utils/game/GameSystem.js";

import {useGameStore} from "@/stores/gameStore.js";

const gameStore = useGameStore();

</script>

<template>
  <div class="absolute bg-green-400 px-8 rounded-2xl py-2 left-1/2 bottom-8 -translate-x-1/2 cursor-pointer" id="ConfirmButton">
    <button @click="calculateScore(gameStore.distance, gameStore.timeLeft)" class="">Confirmer votre choix</button>
  </div>

</template>

<style scoped>

</style>
