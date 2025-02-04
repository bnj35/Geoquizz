<script setup>
import { ref } from "vue";

const srcGameImage = ref('src/assets/img/stanislas.jpg');
</script>

<template>
  <div class="absolute bg-red-400" id="game_image">
    <img class="h-screen w-screen object-cover" :src="srcGameImage" alt="Game Image" />
  </div>
</template>

<style scoped>

</style>
