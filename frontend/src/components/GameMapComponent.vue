<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Variables for the coordinates defined by the game:
const setMarketLat = ref(48.690280483493176)
const setMarketLon = ref(6.18667602539062)

// Variables for the coordinates defined by the player:
const currentMarkerLat = ref('null')
const currentMarkerLon = ref('null')

// Misc:
const distance = ref('null')
const score = ref(0)
const time = ref(5)

onMounted(() => {
  const map = L.map('game_map').setView([48.690280483493176, 6.18667602539062], 13)
  let currentMarker = null

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map)

  // Add a marker to the map at the predefined coordinates:
  L.marker([setMarketLat.value, setMarketLon.value]).addTo(map)

  map.on('click', function (e) {
    if (currentMarker) {
      map.removeLayer(currentMarker)
    }
    currentMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map)

    currentMarkerLat.value = e.latlng.lat
    currentMarkerLon.value = e.latlng.lng

    const calculatedDistance = haversineDistance(setMarketLat.value, setMarketLon.value, e.latlng.lat, e.latlng.lng)
    distance.value = calculatedDistance
    score.value = calculateScore(calculatedDistance)
  })

  const resizeObserver = new ResizeObserver(() => {
    map.invalidateSize()
  })

  resizeObserver.observe(document.getElementById('game_map'))

  // Haversine function
  function haversineDistance(lat1, lon1, lat2, lon2) {
    const toRadians = (degrees) => degrees * (Math.PI / 180);

    const R = 6371e3; // Earth's radius in meters
    const fi1 = toRadians(lat1);
    const fi2 = toRadians(lat2);
    const deltafi = toRadians(lat2 - lat1);
    const deltalambda = toRadians(lon2 - lon1);

    const a = Math.sin(deltafi / 2) * Math.sin(deltafi / 2) +
      Math.cos(fi1) * Math.cos(fi2) *
      Math.sin(deltalambda / 2) * Math.sin(deltalambda / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const calculatedDistance = R * c; // in meters

    return calculatedDistance;
  }

  // Score calculation function
  function calculateScore(distance, timeLeft) {

  }

  function timeLeft() {
    const interval = setInterval(() => {

      //Le temps est écoulé :
      if (time.value === 0) {
        clearInterval(interval)

        //Il reste encore du temps :

      }
      else {
        time.value -= 1
      }
    }, 1000)
  }

  timeLeft();

  onUnmounted(() => {
    resizeObserver.disconnect()
  })
})
</script>

<template>
  <div class="absolute bottom-4 right-4 w-[250px] h-[250px] hover:w-[500px] hover:h-[500px] transition-all" id="game_map"></div>

  <div class="absolute top-4 left-4">
    <p>Point défini par l'utilisateur :</p>
    {{ currentMarkerLat }}
    {{ currentMarkerLon }}
    <p>Point défini par le jeu :</p>
    <p>Distance: {{ distance }}</p>
    <p>Score: {{ score }}</p>
    <p>Temps restant: {{ time }}</p>
  </div>
</template>

<style scoped>

</style>
