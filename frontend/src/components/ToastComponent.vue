<script setup>
defineProps({
  message: String,
})
</script>

<template>
  <div class="absolute flex justify-center items-center gap-3 bg-red-400 bottom-4 right-4 px-4 py-4 max-w-2/10 rounded-2xl" id="toast">
    <div class="flex items-center justify-center" id="toast_icon">
      <svg class="w-6 h-6 text-red-500 bg-red-500 rounded-full" fill="none" stroke="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </div>

    <div class="text-center overflow-hidden text-ellipsis whitespace-nowrap" id="toast_message">
      <p class="text-black font-monospace">{{ message }}</p>
    </div>
  </div>
</template>

<style scoped>
</style>
