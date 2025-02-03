<script setup>

defineProps({
  serie_name: {
    type: String,
    required: true
  },
  serie_description: {
    type: String,
    required: true
  },
  serie_image: {
    type: String,
    required: true
  },
  serie_difficulty: {
    type: Number,
    required: true
  }
})
</script>

<template>
  <div class="bg-blue-400 flex items-center w-1/4" id="serie">
    <div class="h-[125px] basis-1/3 bg-orange-400" id="serie-image">
      <img class="w-full h-full object-cover" :src="serie_image" alt="Placeholder image">
    </div>

    <div class="bg-orange-400 basis-2/3" id="serie-content">
      <h3>{{ serie_name }}</h3>
      <p>{{ serie_description }}</p>

      <div class="flex gap-2" id="serie-difficulty">
        <img v-for="index in 5" :key="index" :src="`/src/assets/svg/star-${index <= serie_difficulty ? 'full' : 'regular'}.svg`" alt="star" class="w-4 h-4">
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
