<script setup>
import { ref } from "vue";

const srcGameImage = ref('src/assets/img/stanislas.jpg');
</script>

<template>
  <div class="h-full w-full bg-red-400" id="game_image">
    <img class="object-cover" :src="srcGameImage" alt="Game Image" />
  </div>
</template>

<style scoped>

</style>
