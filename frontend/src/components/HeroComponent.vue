<template>
  <div class="bg-purple-400 relative flex flex-col py-8" id="hero_section">

    <div id="main">
      <h1>Bienvenue sur GeoQuizz</h1>
      <p>Trouvez la position en un temps déterminé</p>
      <a href=""><router-link to="/game">Jouer</router-link></a>
    </div>

    <div>
      <h2 class="text-3xl">Nos dernières séries :</h2>
      <div class="flex justify-center gap-8" id="series">
        <SerieComponent serie_image="src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="4"/>
        <SerieComponent serie_image="/src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="2"/>
        <SerieComponent serie_image="/src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="3"/>
      </div>
    </div>

    <div>
      <h2 class="text-3xl">Les plus populaires :</h2>
      <div class="flex justify-center gap-8" id="series">
        <SerieComponent serie_image="src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="3"/>
        <SerieComponent serie_image="/src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="1"/>
        <SerieComponent serie_image="/src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="3"/>
      </div>
    </div>

    <div>
      <h2 class="text-3xl">Dernières tendances :</h2>
      <div class="flex justify-center gap-8" id="series">
        <SerieComponent serie_image="src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="2"/>
        <SerieComponent serie_image="/src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="5"/>
        <SerieComponent serie_image="/src/assets/img/macron.jpg" serie_description="serie_description" serie_name="serie_title" :serie_difficulty="4"/>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import SerieComponent from "@/components/SerieComponent.vue";
</script>
