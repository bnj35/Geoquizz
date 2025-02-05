<script setup>
import { onMounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css';
import { useGameStore } from '@/stores/gameStore'

// Access the store
const gameStore = useGameStore()

let map = null;

function setMarkers(currentLat, currentLon, actualLat, actualLon) {
  map = L.map('game_map_recap').setView([actualLat, actualLon], 18)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map)

  // Markers :
  let blackIcon = new L.Icon({
    iconUrl: './src/assets/img/marker-icon-black.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  });

  let greenIcon = new L.Icon({
    iconUrl: './src/assets/img/marker-icon-green.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  });

  // Add markers
  L.marker([actualLat, actualLon], {icon: blackIcon}).addTo(map)
  if(gameStore.currentLat !== null && gameStore.currentLon !== null) {
    L.marker([currentLat, currentLon], {icon: greenIcon}).addTo(map)
  }

  // Ajouter une ligne en pointillés entre les deux points
  L.polyline(
    [[actualLat, actualLon], [currentLat, currentLon]],
    { color: 'black', weight: 3, dashArray: '5, 10' } // Ligne bleue pointillée
  ).addTo(map);

  animateZoomOut();
}

function animateZoomOut() {
  let currentZoom = 18;
  const targetZoom = 12;
  const step = 1; // Diminution du zoom à chaque étape
  const interval = 120; // Temps entre chaque changement de zoom

  const zoomOut = setInterval(() => {
    if (currentZoom > targetZoom) {
      currentZoom -= step;
      map.setZoom(currentZoom, { animate: true });
    } else {
      clearInterval(zoomOut); // Arrêter l'animation une fois atteint
    }
  }, interval);
}

onMounted(() => {
  setMarkers(gameStore.currentLat, gameStore.currentLon, gameStore.actualLat, gameStore.actualLon)
})
</script>





<template>
  <div class="flex h-screen" id="game_recap">
    <div class="basis-2/3" id="game_map_recap"></div>

    <!-- Ajout de justify-center pour centrer tout verticalement -->
    <div v-if="gameStore.hasPlayed" class="h-screen basis-1/3 flex flex-col items-center justify-center gap-2" id="game_image_recap">
      <p class="text-4xl font-bold">Vous avez marqué : {{ gameStore.score }} points.</p>
      <p class="text-3xl font-light">Distance : {{ gameStore.distance }}m ({{gameStore.distanceKm}}km)</p>
      <RouterLink to="/game" class="bg-green-400 w-fit px-4 py-2 rounded-3xl">Passer à l'image suivante</RouterLink>
    </div>
    <div v-else class="h-screen basis-1/3 flex flex-col items-center justify-center gap-4">
      <p class="text-4xl font-bold">Vous n'avez pas joué.</p>
      <RouterLink to="/game" class="bg-green-400 w-fit px-4 py-2 rounded-3xl">Passer à l'image suivante</RouterLink>
    </div>
  </div>
</template>


<style scoped>

</style>
