<script setup>
import { onMounted, ref } from "vue";
import { callRandomThemeImage, displayImage } from "@/utils/game/GameSystem.js";
import { useRouter } from 'vue-router';

const router = useRouter();

router.beforeEach((to, from, next) => {
  if (to.name === 'game_image') {
    const image = callRandomThemeImage();
    if (image) {
      displayImage(image);
      next();
    } else {
      next(false); // Cancel navigation if no image is found
    }
  } else {
    next();
  }
});

</script>

<template>
  <div class="absolute bg-red-400" id="game_image">
    <img class="h-screen w-screen object-cover" :src="srcGameImage" alt="Game Image" />
  </div>
</template>

<style scoped>

</style>
