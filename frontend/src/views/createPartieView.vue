<script setup>
  import {createParty} from "@/utils/game/GameSystem.js";

  createParty('test', 'test', 5, 1).then((res) => {
    console.log(res);
  });
</script>

<template>
  <div id="create_party">
    <h1>Créer une partie</h1>
    <form>
      <label for="name">Nom de la partie :</label>
      <input type="text" id="name" name="name" required>
      <label for="theme">Thème de la partie :</label>
      <input type="text" id="theme" name="theme" required>
      <label for="nb_photos">Nombre de photos à trouver :</label>
      <input type="number" id="nb_photos" name="nb_photos" required>
      <router-link to="/game">Jouer</router-link>
    </form>
  </div>
</template>

<style scoped>

</style>
