<script setup>
  import L from 'leaflet'
  import 'leaflet/dist/leaflet.css'
</script>

<template>
  <div class="flex" id="game_image_recap">
    <div id="game_map_recap"></div>

    <div id="game_image_recap">
      <p>Vous avez marqué : {{ scoreImage }} points.</p>
      <button>Passer à la prochaine image :</button>
    </div>
  </div>
</template>

<style scoped>

</style>
