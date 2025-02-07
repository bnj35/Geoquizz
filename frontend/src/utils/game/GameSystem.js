import { onUnmounted } from "vue";
import { useGameStore } from "@/stores/gameStore.js";
import router from "@/router/index.js";
import { useAPI } from "@/utils/api.js";
import { useUserStore } from "@/stores/userStore.js";

export function haversineDistance(lat1, lon1, lat2, lon2) {
  const toRadians = (degrees) => degrees * (Math.PI / 180);
  const R = 6371e3;
  const fi1 = toRadians(lat1);
  const fi2 = toRadians(lat2);
  const deltafi = toRadians(lat2 - lat1);
  const deltalambda = toRadians(lon2 - lon1);

  const a = Math.sin(deltafi / 2) ** 2 +
    Math.cos(fi1) * Math.cos(fi2) *
    Math.sin(deltalambda / 2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

  return Math.round(R * c);
}

export function calculateScore(distance, timeLeft, maxDistance) {
  const store = useGameStore();
  if (distance <= 0 || timeLeft <= 0 || maxDistance <= 0) return 0;

  const MAX_SCORE = 5000;
  const TIME_FACTOR = 1;

  let distanceScore = Math.max(0, 1 - distance / maxDistance);
  let timeBonus = 1 + (timeLeft / 100) * TIME_FACTOR;

  let score = Math.round(MAX_SCORE * distanceScore * timeBonus);
  store.score = score;
  store.scores.push(score);

  return score;
}

export function calculateTimeLeft() {
  const gameStore = useGameStore();


  if(gameStore.gameState === 'game_over') return;

  if (gameStore.timerStarted) return;

  if (gameStore.timeLeft <= 0) return;

  const timer = setInterval(() => {
    gameStore.timerStarted = true;

    if (gameStore.timeLeft > 0) {
      gameStore.timeLeft--;
    }
      clearInterval(timer);
      gameStore.gameState = 'game_over';
      gameStore.timerStarted = false;
        router.push({name: 'gamerecap'});
    }
  , 1000);
}


export function refreshMapOnResize(map) {
  const mapElement = document.getElementById('game_map');
  if (mapElement) {
    const resizeObserver = new ResizeObserver(() => {
      map.invalidateSize();
    });
    resizeObserver.observe(mapElement);
    onUnmounted(() => resizeObserver.disconnect());
  }
}

export function calculateTotalScore() {
  const store = useGameStore();
  store.totalScore += store.scores.reduce((acc, score) => acc + score, 0);
}

export function callRandomThemeImage() {
  const gameStore = useGameStore();

  if (gameStore.images.length === 0) return null; // Vérification si le tableau est vide

  const randomIndex = Math.floor(Math.random() * gameStore.images.length);

  const [randomImage] = gameStore.images.splice(randomIndex, 1);

  //On renseigne les store avec les valeurs actuelles :
  gameStore.actualLat = randomImage.latitude;
  gameStore.actualLon = randomImage.longitude;

  return randomImage.image;
}

export function displayImage(img_src) {
  const gameStore = useGameStore();
  const image = document.querySelector('#game_image img');

  //On vérifie si il y a gameover :
  if(gameStore.imagesLeft === 0){
    gameStore.gameState = 'game_over';
    gameStore.timerStarted = false;
    router.push({name: 'gameover'});
  }

  if (image) {
    image.src = `http://localhost:6081` + img_src;
    gameStore.imagesLeft--;
  } else {
    //On envoi un toast avec une erreur
  }
}


///////////////////////////////////
//Logique de jeu call via API :
///////////////////////////////////

//Créer une partie :


export function initGame(time, distance, nb_photos) {
  const gameStore = useGameStore();
  gameStore.distance = distance;
  gameStore.timeLeft = time;
  gameStore.nb_photos = nb_photos;
}

export function createParty(name, token, theme, nb_photos, time, user_id) {
  const api = useAPI();
  return api.post('/parties', { nom: name, token, nb_photos, score: 10, theme, temps: time, user_id })
    .then(response => response.data)
    .catch(error => { console.error('Error creating party:', error); throw error; });
}



//Parties :
//OK
export function getParties() {
  const api = useAPI();
  return api.get('/parties')
    .then(response => response.data)
    .catch(error => { console.error('Error fetching parties:', error); throw error; });
}

export function getPartiesByUserId(id) {
  const api = useAPI();
  return api.get(`/users/${id}/parties`)
    .then(response => response.data)
    .catch(error => { console.error('Error fetching parties:', error); throw error; });
}

export function getUserStats(id) {
  const api = useAPI();
  return api.get(`/users/${id}/stats`)
    .then(response => response.data)
    .catch(error => { console.error('Error fetching user stats:', error); throw error; });
}

export function updateUserStats(user_stats_id) {
  const api = useAPI();
  return api.put(`/stats/${user_stats_id}`, {
    score_total: 0, score_moyen: 0, nb_parties: 0, meilleur_coup: 0, pire_coup: 0
  })
    .then(response => response.data)
    .catch(error => { console.error('Error updating user stats:', error); throw error; });
}


//Appeler les images d'une série :
  async function callThemeImages() {
    const gameStore = useGameStore();
  }



export async function signIn(email, password) {
  try {
    const api = useAPI();
    const userStore = useUserStore();
    const response = await api.post('/signin', {}, {
      headers: { 'Authorization': `Basic ${btoa(`${email}:${password}`)}` }
    });
    userStore.user_token = response.data.token;
    userStore.user_id = response.data.id;
    return response.data;
  } catch (error) {
    console.error(error);
  }
}

export async function signUp(email, password) {
  try {
    const api = useAPI();
    const userStore = useUserStore();
    const response = await api.post('/signup', {}, {
      headers: { 'Authorization': `Basic ${btoa(`${email}:${password}`)}` }
    });
    userStore.user_token = response.data.token;
    userStore.user_id = response.data.id;
    return response.data;
  } catch (error) {
    console.error(error);
  }
}

export async function refresh() {
  try {
    const api = useAPI();
    const userStore = useUserStore();
    const response = await api.get('/refresh', {}, {
      headers: { 'Authorization': `Bearer ${userStore.user_token}` }
    });
    return response.data;
  } catch (error) {
    console.error(error);
  }
}

export async function callSerieImages() {
  try {
    const response = await fetch('http://localhost:5000/api/series/images');
    return await response.json();
  } catch (error) {
    console.error(error);
  }
}
