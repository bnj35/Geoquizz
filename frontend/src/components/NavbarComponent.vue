<script setup>
  import { ref } from 'vue'

  const username = ref('Utilisateur')

</script>

<template>
    <div class="bg-amber-50 border-b-1 sm:flex flex items-center justify-between py-4" id="desktop_navbar">
      <div class="text-center basis-2/10" id="logo">GeoQuizz</div>

      <div class="flex justify-around basis-4/10" id="nav-links">
        <router-link to="/">Home</router-link>
        <router-link to="/game">Game</router-link>
        <router-link to="/profile">Profile</router-link>
        <router-link to="/signup">Signup</router-link>
        <router-link to="/signin">Signin</router-link>
      </div>

      <div class= "basis-2/10 gap-4 profile flex justify-center items-center">
        <span class="">{{ username }}</span>
      </div>
    </div>


  <div class="sm:hidden absolute bottom-0 justify-between items-center flex py-4 w-[95%]" id="mobile_navbar">
    <div class=" basis-2/10 text-center" id="logo">GeoQuizz</div>

    <div class="basis-6/10 flex justify-around items-center" id="nav-links">
      <img class="w-6 h-6" src="../assets/svg/house-solid.svg" alt="">
      <img class="w-6 h-6" src="../assets/svg/gamepad-solid.svg" alt="">
      <img class="w-6 h-6" src="../assets/svg/user-regular.svg" alt="">
      <img class="w-6 h-6" src="../assets/svg/right-to-bracket-solid.svg" alt="">
    </div>

    <div class="basis-2/10 flex flex-col items-center" id=" profile">
      <img class="w-[50px] rounded-full" src="../assets/img/macron.jpg" alt="">
      <span>{{ username }}</span>
    </div>
  </div>

</template>

<style scoped>

</style>
