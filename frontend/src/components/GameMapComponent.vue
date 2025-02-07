<script setup>
import {onMounted, onUnmounted} from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css';
import { useGameStore } from '@/stores/gameStore'
import {
  haversineDistance,
  calculateScore,
  calculateTimeLeft,
  refreshMapOnResize, calculateTotalScore
} from '@/utils/game/GameSystem.js'

// Access the store
const gameStore = useGameStore()

onMounted(() => {
  const map = L.map('game_map').setView([48.690280483493176, 6.18667602539062], 13)
  let currentMarker = null

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map)

  let greenIcon = new L.Icon({
    iconUrl: './src/assets/img/marker-icon-green.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  });

  map.on('click', function (e) {
    if (currentMarker) {
      map.removeLayer(currentMarker)
    }
    currentMarker = L.marker([e.latlng.lat, e.latlng.lng], {icon: greenIcon}).addTo(map)

    gameStore.currentLat = e.latlng.lat
    gameStore.currentLon = e.latlng.lng

    const calculatedDistance = haversineDistance(gameStore.actualLat, gameStore.actualLon, e.latlng.lat, e.latlng.lng)
    gameStore.distance = calculatedDistance
    gameStore.distanceKm = (calculatedDistance / 1000).toFixed(2)
    gameStore.hasPlayed = true;
  })

  refreshMapOnResize(map)
  calculateTotalScore(); //Devrait pas etre là
  calculateTimeLeft(gameStore.timeLeft) //Devrait pas etre là

})

</script>

<template>
  <div
    class="absolute bottom-4 right-4 w-[250px] h-[250px] hover:w-[500px] hover:h-[500px] select-none transition-all"
    id="game_map"></div>
</template>

<style scoped>
</style>
