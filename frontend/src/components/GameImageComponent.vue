<script setup>
import {onMounted, ref} from "vue";
import {callRandomThemeImage, displayImage} from "@/utils/game/GameSystem.js";

onMounted(() => {
  displayImage(callRandomThemeImage());
});

</script>

<template>
  <div class="absolute bg-red-400" id="game_image">
    <img class="h-screen w-screen object-cover" :src="srcGameImage" alt="Game Image" />
  </div>
</template>

<style scoped>

</style>
