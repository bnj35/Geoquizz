<script setup>
import { onMounted, ref } from 'vue';
import { getParties, getPartiesByUserId } from "@/utils/game/GameSystem.js";
import SerieComponent from "@/components/SerieComponent.vue";
import PartyComponent from "@/components/PartyComponent.vue";
import { useUserStore } from "@/stores/userStore.js";

const userStore = useUserStore();

// Liste des parties jouées par un utilisateur spécifique :
let userParties = ref([]);
onMounted(() => {
  const userStore = useUserStore();

  if (userStore.user_token) {
    getPartiesByUserId(userStore.user_id).then((data) => {
      userParties.value = data.parties;
    });
  }
});

// Liste des parties jouées par la communauté :
let parties = ref([]);
onMounted(() => {
  getParties().then((data) => {
    parties.value = data.parties;
  });
});
</script>

<template>
  <div class="bg-gray-50 relative flex flex-col py-8" id="hero_section">

    <div id="main" class="w-full m-8 mb-16">
      <h1 class="text-gray-800 text-6xl">Bienvenue sur GeoQuizz</h1>
      <p class="text-gray-800 text-2xl">Trouvez où vous êtes le plus vite possible !!!</p>
      <router-link to="/createpartie"><button class="bg-gray-700 py-4 px-10 mt-8 rounded-[1.25rem] text-gray-50 hover:text-gray-100 hover:bg-gray-800 transition-all cursor-pointer">Jouer</button></router-link>
    </div>

    <div v-if="userStore.user_token" class="m-8 mt-5">
      <h2 class="text-3xl mt-8"> Vos dernières parties :</h2>
      <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300 mt-8">
          <thead class="bg-gray-800 text-gray-50">
            <tr>
              <th class="px-4 py-2 border border-gray-300">Nom</th>
              <th class="px-4 py-2 border border-gray-300">Thème</th>
              <th class="px-4 py-2 border border-gray-300">Score</th>
              <th class="px-4 py-2 border border-gray-300">Nombre de photos</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(userParty, index) in userParties" :key="index" :class="{'bg-gray-200': index % 2 === 0}" class="hover:bg-gray-700 hover:text-gray-50 transition-all">
              <PartyComponent
                :name="userParty.nom"
                :theme="userParty.theme"
                :score="userParty.score"
                :nb_photos="userParty.nb_photos"
              />
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="m-8 mt-8">
      <h2 class="text-3xl mt-8">Dernières parties jouées par la communauté :</h2>
      <div class="overflow-x-auto">
        <table class="table-auto border-collapse border border-gray-300 w-full mt-8">
          <thead class="bg-gray-800 text-gray-50">
            <tr>
              <th class="px-4 py-2 border border-gray-300">Nom</th>
              <th class="px-4 py-2 border border-gray-300">Thème</th>
              <th class="px-4 py-2 border border-gray-300">Score</th>
              <th class="px-4 py-2 border border-gray-300">Nombre de photos</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(party, index) in parties" :key="index" :class="{'bg-gray-200': index % 2 === 0}" class="hover:bg-gray-700 hover:text-gray-50 transition-all">
              <PartyComponent
                :name="party.nom"
                :theme="party.theme"
                :score="party.score"
                :nb_photos="party.nb_photos"
              />
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
