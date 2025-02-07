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
    <td class="p-2 text-center border border-gray-300 ">
      <button class="w-full text-center p-2  cursor-pointer hover:text-amber-100 transition-all rounded-2xl" id="party_join">
        Jouer à la série
      </button>
    </td>
</template>
