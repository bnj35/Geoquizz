<script setup>
import { calculateScore } from "@/utils/game/GameSystem.js";
import { useGameStore } from "@/stores/gameStore.js";
import { computed } from 'vue';

const gameStore = useGameStore();

const isClickable = computed(() => {
  return gameStore.currentLat !== null && gameStore.currentLon !== null;
});

</script>

<template>
  <div
    class="absolute bg-gray-800 text-gray-50 px-16 text-2xl rounded-3xl py-4 left-1/2 bottom-8 -translate-x-1/2 cursor-pointer transition-opacity duration-300"
    id="ConfirmButton"
    :class="{ 'opacity-20': !isClickable }">

    <router-link
      v-if="isClickable"
      @click="calculateScore(gameStore.distance, gameStore.maxDistance)"
      to="/gamerecap"
    >
      <p>Confirmer</p>
    </router-link>
    <p v-else>Confirmer</p>
  </div>
</template>

<style scoped>
.cursor-not-allowed {
  pointer-events: none;
}
</style>
