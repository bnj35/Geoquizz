<script setup>
  import { ref } from 'vue'

  const username = ref('Utilisateur')

</script>

<template>
    <div class="sm:flex hidden bg-orange-400 flex items-center py-4" id="desktop_navbar">
      <div class="bg-blue-400 text-center basis-2/10" id="logo">GeoQuizz</div>

      <div class="bg-yellow-400 flex justify-around basis-6/10" id="nav-links">
        <router-link to="/">Home</router-link>
        <router-link to="/game">Game</router-link>
        <router-link to="/profile">Profile</router-link>
        <router-link to="/signup">Signup</router-link>
        <router-link to="/signin">Signin</router-link>
      </div>

      <div class="bg-pink-400 basis-2/10 gap-4 profile flex justify-center items-center">
        <img class="bg-red-400 w-[50px] rounded-full" src="../assets/img/macron.jpg" alt="">
        <span class="bg-red-400">{{ username }}</span>
      </div>
    </div>


  <div class="sm:hidden absolute bottom-0 bg-orange-400 justify-between items-center flex py-4 w-[95%]" id="mobile_navbar">
    <div class="bg-blue-400 basis-2/10 text-center" id="logo">GeoQuizz</div>

    <div class="bg-yellow-400 basis-6/10 flex justify-around items-center" id="nav-links">
      <img class="w-6 h-6" src="../assets/svg/house-solid.svg" alt="">
      <img class="w-6 h-6" src="../assets/svg/gamepad-solid.svg" alt="">
      <img class="w-6 h-6" src="../assets/svg/user-regular.svg" alt="">
      <img class="w-6 h-6" src="../assets/svg/right-to-bracket-solid.svg" alt="">
    </div>

    <div class="basis-2/10 bg-pink-400 flex flex-col items-center" id=" profile">
      <img class="w-[50px] rounded-full" src="../assets/img/macron.jpg" alt="">
      <span>{{ username }}</span>
    </div>
  </div>

</template>

<style scoped>

</style>
