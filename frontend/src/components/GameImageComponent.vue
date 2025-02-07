<script setup>
import {onMounted, ref} from "vue";
import {callRandomThemeImage, displayImage} from "@/utils/game/GameSystem.js";

const loader = ref(true);

onMounted(() => {
  displayImage(callRandomThemeImage(), loader => {
    loader.value = false;
  });
});

</script>

<template>
  <div class="absolute bg-gray-50 " id="game_image">
    <img class="h-screen w-screen object-cover" :src="srcGameImage" alt="Game Image" />
    <div v-if="loader.value" id="loader" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
      <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
    </div>
  </div>

</template>

