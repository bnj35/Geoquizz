<script setup>
  import { ref } from 'vue';

  import { useGameStore } from '@/stores/gameStore';

  const gameStore = useGameStore();


</script>

<template>

  <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-gray-800 text-amber-50 px-4 py-4 rounded-full select-none shadow-xl z-50">
    <div class="flex gap-5 h-full text-nowrap">
      <p class="">Logo GeoQuizz</p>
      <p>Score : {{ gameStore.totalScore }}</p>
      <p>Temps restant : {{ gameStore.timeLeft }}</p>
      <p>Serie jouée : {{ gameStore.themePlayed }}</p>
    </div>
  </div>
</template>


<style scoped>

</style>
