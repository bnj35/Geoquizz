<script setup>
  import { ref } from 'vue'

  const username = ref('Utilisateur')

</script>

<template>
    <div class=" bg-amber-50 border-b-1 hidden md:flex items-center justify-between py-4" id="desktop_navbar">
      <div class="text-center basis-2/10"><router-link to="/">Home</router-link></div>

      <div class="flex justify-around basis-4/10" id="nav-links">
        <router-link to="/game">Game</router-link>
        <router-link to="/profile">Profile</router-link>
        <router-link to="/signup">Signup</router-link>
        <router-link to="/signin">Signin</router-link>
      </div>

      <div class= "basis-2/10 gap-4 profile flex justify-center items-center">
        <span class="">{{ username }}</span>
      </div>
    </div>


  <div class="md:hidden fixed bottom-0 justify-between items-center flex py-4 w-full border-t-1 z-50 bg-amber-50" id="mobile_navbar">
    <div class="flex justify-around items-center w-full" id="nav-links">
      <router-link to="/"> <img class="w-6 h-6" src="../assets/svg/house-solid.svg" alt=""></router-link>
      <router-link to="createpartie"><img class="w-6 h-6" src="../assets/svg/gamepad-solid.svg" alt=""></router-link>
      <router-link to="profile"><img class="w-6 h-6" src="../assets/svg/user-regular.svg" alt=""></router-link>
      <router-link to="signin"><img class="w-6 h-6" src="../assets/svg/right-to-bracket-solid.svg" alt=""></router-link>
    </div>
  </div>  


</template>


