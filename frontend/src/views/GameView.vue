<script setup>
  import GameMapComponent from "@/components/GameMapComponent.vue";
  import GameImageComponent from "@/components/GameImageComponent.vue";
  import GameDetailsComponent from "@/components/GameDetailsComponent.vue";
  import GameConfirmComponent from "@/components/GameConfirmComponent.vue";

  import {useGameStore} from "@/stores/gameStore.js";
  import {onMounted} from "vue";
  import {calculateTimeLeft} from "@/utils/game/GameSystem.js";

  const gameStore = useGameStore();

  onMounted(() => {
    gameStore.gameState = 'playing';
    calculateTimeLeft(gameStore.timeLeft)
  });
</script>

<template>
    <GameDetailsComponent/>
    <GameImageComponent/>
    <GameMapComponent/>
    <GameConfirmComponent/>
</template>
