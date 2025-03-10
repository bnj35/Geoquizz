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

export function calculateScore(distance, maxDistance) {
  const store = useGameStore();
  const api = useAPI();
  const userStore = useUserStore();

  if (distance <= 0 || maxDistance <= 0) {
    return 0;
  }

  const MAX_SCORE = 5000;
  let distanceScore = Math.max(0, 1 - distance / maxDistance);

  let score = Math.round(MAX_SCORE * distanceScore);

  store.score = score;
  store.scores.push(score);
  store.timeLeft = store.time;
  store.gameState = "game_recap";


  if (userStore.meilleur_coup < score) {
    userStore.meilleur_coup = score;
  }

  if (userStore.pire_coup > score || userStore.pire_coup === 0) {
    userStore.pire_coup = score;
  }

  api.patch(`/parties/${store.gameId}/score`, { score: score }, { headers: { 'Authorization': `Bearer ${userStore.user_token}` } })
  
  store.totalScore = score;

  return score;
}


export function calculateTimeLeft() {
  const gameStore = useGameStore();

  if (gameStore.gameState === 'game_over') return;
  if (gameStore.timerStarted) return;
  if (gameStore.timeLeft <= 0) return;

  const timer = setInterval(() => {
    gameStore.timerStarted = true;

    if (gameStore.timeLeft > 0 && gameStore.gameState === 'playing') {
      gameStore.timeLeft--;
    }
    else if (gameStore.timeLeft === 0) {
      clearInterval(timer);
      gameStore.gameState = 'game_over';
      gameStore.timerStarted = false;
      router.push({ name: 'gamerecap' });
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
    onUnmounted(() => resizeObserver.disconnect());
  }
}

export function calculateTotalScore() {
  const store = useGameStore();
  store.totalScore += store.score;

  return store.totalScore;
}

export function callRandomThemeImage() {
  const gameStore = useGameStore();

  //On reset quelques valeurs du store :
  gameStore.currentLat = null;
  gameStore.currentLon = null;
  gameStore.distance = null;
  gameStore.distanceKm = null;
  gameStore.hasPlayed = false;

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
  const userStore = useUserStore();
  const image = document.querySelector('#game_image img');

  //On vérifie si il y a gameover :
  if(gameStore.imagesLeft === 0){
    gameStore.gameState = 'game_over';
    gameStore.timerStarted = false;
    userStore.score_total = userStore.score_total + gameStore.totalScore;
    userStore.score_moyen = userStore.score_total / userStore.nb_parties;
    router.push({name: 'gameover'});
    return;
  }

  if (image) {
    image.src = import.meta.env.VITE_API_BASE_URL + img_src;
    gameStore.imagesLeft--;
  }
  else {
    //On envoi un toast avec une erreur
  }
}

///////////////////////////////////
//Logique de jeu call via API :
///////////////////////////////////

//Créer une partie :

export function createParty(name, theme, nb_photos, time, user_id) {

  const userStore = useUserStore();

  userStore.nb_parties++; 

  const api = useAPI();
  return api.post('/parties',
    { nom: name, nb_photos: nb_photos, score: 0, theme: theme, temps: time, user_id: user_id },
    { headers: { 'Authorization': `Bearer ${userStore.user_token}` } }
  )
    .then(response => response.data)
    .catch(error => { console.error('Error creating party:', error); throw error; });
}



//Parties :
//OK
export function getParties() {

  const userStore = useUserStore();
  const api = useAPI();
  return api.get('/parties',
    { headers: { 'Authorization': `Bearer ${userStore.user_token}` }
  })
    .then(response => response.data)
    .catch(error => { console.error('Error fetching parties:', error); throw error; });
}

export function getPartiesByUserId(id) {
  const api = useAPI();
  return api.get(`/users/${id}/parties`,
    { headers: { 'Authorization': `Bearer ${useUserStore().user_token}` }})
    .then(response => response.data)
    .catch(error => { console.error('Error fetching parties:', error); throw error; });
}

export function getUserStats(id) {

  const userStore = useUserStore();
  const api = useAPI();
  return api.get(`/users/${id}/stats`,
    { headers: { 'Authorization': `Bearer ${userStore.user_token}` }})
    .then(response => response.data)
    .catch(error => { console.error('Error fetching user stats:', error); throw error; });
}

export function updateUserStats(user_stats_id) {
  const api = useAPI();
  const userStore = useUserStore();
  let statsId = '';

  const score_total = userStore.score_total;
  const score_moyen = userStore.score_moyen;
  const nb_parties = userStore.nb_parties;
  const meilleur_coup = userStore.meilleur_coup;
  const pire_coup = userStore.pire_coup;

  api.get(`/users/${user_stats_id}/stats`, {
    headers: { 'Authorization': `Bearer ${userStore.user_token}` }
  }
).then(response => {
  console.log(response.data);
  statsId = response.data.stats.id;
  api.put(`/stats/${statsId}`, {
   user_id:user_stats_id, score_total: score_total, score_moyen: score_moyen, nb_parties: nb_parties, meilleur_score: meilleur_coup, pire_coups: pire_coup
  })
    .then(response => response.data)
    .catch(error => { console.error('Error updating user stats:', error); throw error; })
}
  )

.catch(error => { console.error('Error updating user stats:', error); throw error; });


}



export async function signIn(email, password) {
  try {
    const api = useAPI();
    const userStore = useUserStore();
    const response = await api.post('/signin', {}, {
      headers: { 'Authorization': `Basic ${btoa(`${email}:${password}`)}` }
    });
    if(response.data){
      router.push({name: 'home'});
    }
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
    }).then (response =>
    {
      return response;
    }
    );
    if(response){
      router.push({name: 'signin'});
    }
    userStore.user_token = response.data.token;
    userStore.user_id = response.data.id;
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

export async function getThemes() {
  try {
    const api = useAPI();
    const response = await api.get('/items/series');
    return await response.data;
  } catch (error) {
    console.error(error);
  }
}

export async function getUsername(id) {
  try {
    const api = useAPI();
    const response = await api.get(`/users/${id}`);
    return await response.data;
  } catch (error) {
    console.error(error);
  }
}
