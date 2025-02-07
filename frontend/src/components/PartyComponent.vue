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
    <td class="p-2 border border-gray-300">{{ name }}</td>
    <td class="p-2 border border-gray-300">{{ theme }}</td>
    <td class="p-2 border border-gray-300">{{ score }}</td>
    <td class="p-2 border border-gray-300">{{ nb_photos }}</td>
</template>
