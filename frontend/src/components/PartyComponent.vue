<script setup>
defineProps({
  name: {
    type: String,
    required: true
  },
  theme: {
    type: String,
    required: true
  },
  score: {
    type: Number,
    required: true
  },
  nb_photos: {
    type: Number,
    required: true
  }
});
</script>

<template>
  <div class="bg-green-300 flex justify-center items-center w-1/3 px-4 py-4" id="party_card">
    <div class="basis-2/3 bg-orange-300 text-center" id="party_details">
      <h3>Nom : {{ name }}</h3>
      <p>Theme joué : {{ theme }}</p>
      <p>Score: {{ score }}</p>
      <p>Nombre de photos : {{ nb_photos }}</p>
    </div>
    <div class="basis-1/3 bg-red-300 text-center" id="party_join">
      <p>Jouer à la série</p>
    </div>

  </div>
</template>
