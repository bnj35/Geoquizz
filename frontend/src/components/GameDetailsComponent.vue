<script setup>
  import { ref } from 'vue';

  import { useGameStore } from '@/stores/gameStore';

  const gameStore = useGameStore();

</script>

<template>
  <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-gray-800 text-white p-4 rounded-lg shadow-lg z-50">
    <div class="flex gap-5 py-1 text-nowrap">
      <p>Score : {{ gameStore.score }}</p>
      <p>Temps restant : {{ gameStore.timeLeft }}</p>
      <p>Serie jouée : {{ gameStore.seriePlayed }}</p>
    </div>
  </div>
</template>


<style scoped>

</style>
