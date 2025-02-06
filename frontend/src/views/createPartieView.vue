<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { createParty } from "@/utils/game/GameSystem.js";
import { useUserStore } from "@/stores/userStore.js";
import {useGameStore} from "@/stores/gameStore.js";

const router = useRouter();
const userStore = useUserStore();
const gameStore = useGameStore();

const name = ref('');
const theme = ref('');
const nb_photos = ref();
const time = ref();

const handleSubmit = async () => {
  try {
    const response = await createParty(name.value, 'nrgciiibhffdghghfgddfggtghgfdgdfgfdgfddgfgfdthfgdgfdyghyjhgdgfgfdnfgdggtfgdgfdghrffgdgfdggdgflolioilyhj', theme.value, nb_photos.value, time.value, userStore.user_id);
    if (response) {
      console.log(response)

      gameStore.gameState = 'playing';
      gameStore.maxDistance = response.partie.distance;
      gameStore.themePlayed = response.partie.theme;
      gameStore.gameId = response.partie.id;
      gameStore.timeLeft = response.partie.temps;
      gameStore.nbPhotos = response.partie.nb_photos;
      gameStore.gameName = response.partie.nom;
      gameStore.totalScore = response.partie.score;
      gameStore.state = response.partie.status;

      gameStore.images = response.images;
      gameStore.imagesLeft = nb_photos.value;


      router.push('/game');
    }
  } catch (error) {
    console.error('Error creating party:', error);
  }
};
</script>

<template>
  <div id="create_party">
    <h1>Créer une partie</h1>
    <form @submit.prevent="handleSubmit">
      <label for="name">Nom de la partie :</label>
      <input type="text" id="name" v-model="name" required>
      <label for="theme">Thème de la partie :</label>
      <input type="text" id="theme" v-model="theme" required>
      <label for="nb_photos">Nombre de photos à trouver :</label>
      <input type="number" id="nb_photos" v-model="nb_photos" required>
      <label for="time">Temps (en secondes) :</label>
      <input type="number" id="time" v-model="time" required>
      <button type="submit">Jouer</button>
    </form>
  </div>
</template>

<style scoped>
/* Ajoutez vos styles ici */
</style>
