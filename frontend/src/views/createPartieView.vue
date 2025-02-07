<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { createParty } from "@/utils/game/GameSystem.js";
import { useUserStore } from "@/stores/userStore.js";
import { useGameStore } from "@/stores/gameStore.js";
import NavbarComponent from '@/components/NavbarComponent.vue';
import { getThemes } from "@/utils/game/GameSystem.js";

const router = useRouter();
const userStore = useUserStore();
const gameStore = useGameStore();

const name = ref('');
const theme = ref('');
const nb_photos = ref();
const time = ref();

const themes = ref([]);

const themesPartie = async () => {
  try {
    const response = await getThemes();
    if (response) {
      themes.value = response.data;
    }
  } catch (error) {
    console.error('Error getting themes:', error);
  }
};

const handleSubmit = async () => {
  try {
    const response = await createParty(name.value, theme.value, nb_photos.value, time.value, userStore.user_id);
    if (response) {

      gameStore.gameState = 'playing';
      gameStore.maxDistance = response.partie.distance;
      gameStore.themePlayed = response.partie.theme;
      gameStore.gameId = response.partie.id;
      gameStore.time = response.partie.temps;
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

onMounted(() => {
  themesPartie();
});
</script>

<template>
  <NavbarComponent />
  <div id="create_party" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md ">
    <h1 class="text-2xl font-bold mb-6">Créer une partie</h1>
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nom de la partie :</label>
        <input type="text" id="name" v-model="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      </div>
      <div>
        <label for="theme" class="block text-sm font-medium text-gray-700">Thème de la partie :</label>
        <select id="theme" v-model="theme" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          <option value="" disabled>Choisissez un thème</option>
          <option v-for="themeOption in themes" :key="themeOption.id" :value="themeOption.nom">{{ themeOption.nom }}</option>
        </select>
      </div>
      <div>
        <label for="nb_photos" class="block text-sm font-medium text-gray-700">Nombre de photos à trouver :</label>
        <input type="number" id="nb_photos" v-model="nb_photos" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      </div>
      <div>
        <label for="time" class="block text-sm font-medium text-gray-700">Temps (en secondes) :</label>
        <input type="number" id="time" v-model="time" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      </div>
      <button type="submit" class="w-full bg-gray-800 text-gray-50 py-2 px-4 rounded-md shadow-sm hover:">Jouer</button>
    </form>
  </div>
</template>
