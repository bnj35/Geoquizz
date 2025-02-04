import {onUnmounted} from "vue";
import {useGameStore} from "@/stores/gameStore.js";

export function haversineDistance(lat1, lon1, lat2, lon2) {
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

export function calculateScore(distance, timeLeft) {
  if (distance <= 0 || timeLeft <= 0) return 0;

  const MAX_SCORE = 5000; // Score maximum possible
  const MAX_DISTANCE = 3000; // Distance à partir de laquelle le score devient presque nul
  const TIME_FACTOR = 1.5; // Facteur d'importance du temps

  // Calcul du score basé sur la distance (plus elle est petite, plus le score est élevé)
  let distanceScore = Math.max(0, 1 - distance / MAX_DISTANCE);

  // Facteur de temps (plus le temps restant est élevé, plus le score est boosté)
  let timeBonus = 1 + (timeLeft / 100) * TIME_FACTOR;

  // Score final arrondi
  let score = Math.round(MAX_SCORE * distanceScore * timeBonus);

  //On met en store :
  const store = useGameStore();
  store.score = score;

  return score;
}


export function calculateTimeLeft() {
  const gameStore = useGameStore();

  if (gameStore.timeLeft <= 0) return;

  const timer = setInterval(() => {
    if (gameStore.timeLeft > 0) {
      gameStore.timeLeft--;
    } else {
      clearInterval(timer);
      gameStore.gameState = 'game_over';
    }
  }, 1000); // Mise à jour chaque seconde
}


export function refreshMapOnResize(map) {
  const mapElement = document.getElementById('game_map');
  if (mapElement) {
    const resizeObserver = new ResizeObserver(() => {
      map.invalidateSize();
    });

    resizeObserver.observe(mapElement);

    onUnmounted(() => {
      resizeObserver.disconnect();
    });
  }
}

