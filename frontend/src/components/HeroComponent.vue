<script setup>
import { ref } from 'vue';
import { getParties, getPartiesByUserId } from "@/utils/game/GameSystem.js";
import SerieComponent from "@/components/SerieComponent.vue";
import PartyComponent from "@/components/PartyComponent.vue";
import {useUserStore} from "@/stores/userStore.js";

const userStore = useUserStore();

//Liste des parties joués par un utilisateur spécifique :
let userParties = ref([]);
getPartiesByUserId(userStore.user_id).then((data) => {
  userParties.value = data.parties;
  console.log(userParties.value);
});

//Liste des parties joués par la communauté :
let parties = ref([]);
getParties().then((data) => {
  parties.value = data.parties;
});
</script>

<template>
  <div class="bg-purple-400 relative flex flex-col py-8" id="hero_section">

    <div id="main">
      <h1>Bienvenue sur GeoQuizz</h1>
      <p>Trouvez la position en un temps déterminé</p>
      <a href=""><router-link to="/game">Jouer</router-link></a>
    </div>

    <div>
      <h2 class="text-3xl">Dernières parties jouées par la communauté :</h2>
      <div class="flex justify-center gap-8" id="parties">
        <PartyComponent
          v-for="party in parties"
          :key="party.id"
          :name="party.nom"
          :theme="party.theme"
          :score="party.score"
          :nb_photos="party.nb_photos"
        />
      </div>
    </div>

    <div>
      <h2 class="text-3xl"> Vos dernières parties :</h2>
      <div class="flex justify-center gap-8" id="series">
        <PartyComponent
          v-for="userParty in userParties"
          :key="userParty.id"
          :name="userParty.nom"
          :theme="userParty.theme"
          :score="userParty.score"
          :nb_photos="userParty.nb_photos"
        />
      </div>
    </div>


  </div>
</template>
