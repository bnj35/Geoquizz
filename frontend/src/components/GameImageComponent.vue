<script setup>
import {onMounted, ref} from "vue";
import {displaySerieImage} from "@/utils/game/GameSystem.js";

onMounted(() => {
  displaySerieImage('./src/assets/img/4.jpg');
});

</script>

<template>
  <div class="absolute bg-red-400" id="game_image">
    <img class="h-screen w-screen object-cover" :src="srcGameImage" alt="Game Image" />
  </div>
</template>

<style scoped>

</style>
