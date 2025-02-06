import {getCurrentInstance, onUnmounted} from "vue";
import {useGameStore} from "@/stores/gameStore.js";
import router from "@/router/index.js";

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

  return Math.round(calculatedDistance);

}

export function calculateScore(distance, timeLeft, maxDistance) {
  const store = useGameStore();

  if (distance <= 0 || timeLeft <= 0 || maxDistance <= 0) return 0;

  const MAX_SCORE = 5000; // Score maximum possible
  const TIME_FACTOR = 1; // Facteur d'importance du temps

  // Calcul du score basé sur la distance (plus elle est petite, plus le score est élevé)
  let distanceScore = Math.max(0, 1 - distance / maxDistance);

  // Facteur de temps (plus le temps restant est élevé, plus le score est boosté)
  let timeBonus = 1 + (timeLeft / 100) * TIME_FACTOR;

  // Score final arrondi
  let score = Math.round(MAX_SCORE * distanceScore * timeBonus);

  store.score = score;

  store.scores.push(score);

  return score;
}

export function calculateTimeLeft() {
  const gameStore = useGameStore();

  if (gameStore.timerStarted) return;

  if (gameStore.timeLeft <= 0) return;

  gameStore.timerStarted = true;

  const timer = setInterval(() => {
    if (gameStore.timeLeft > 0) {
      gameStore.timeLeft--;
    } else {
      clearInterval(timer);
      gameStore.gameState = 'game_over';
      gameStore.timerStarted = false;

      //Force routing :
      router.push({name: 'gamerecap'});

    }
  }, 1000);
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

export function calculateTotalScore() {

  const store = useGameStore();
  let totalScore = store.totalScore;
  store.scores.forEach(score => {
    totalScore += score;
  });

  store.totalScore = totalScore;
}

export function displaySerieImage(img_src) {
  const image = document.querySelector('#game_image img');

  if (image) {
    image.src = img_src;
  } else {
    //On envoi un toast avec une erreur
  }
}

///////////////////////////////////
//Logique de jeu call via API :
///////////////////////////////////

async function initGame() {
  try {
    const response = await fetch('http://localhost:5000/api/game/init');
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
  }
}

//Appeler les images d'une série :
async function callSerieImages() {
  try {
    const response = await fetch('http://localhost:5000/api/series/images');
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
  }
}

export async function signIn(email, password) {
  try {
    const { proxy } = getCurrentInstance();
    const api = proxy.$api();

    const response = await api.post('/signin', {email, password});
    console.log(response.data);
    return response.data;
  } catch (error) {
    console.error(error);
  }
}

export async function signUp(email, password) {
  try {
    const { proxy } = getCurrentInstance();
    const api = proxy.$api();
    const response = await api.post('/signup', {email, password});
    console.log(response.data);
    return response.data;
  } catch (error) {
    console.error(error);
  }
}




