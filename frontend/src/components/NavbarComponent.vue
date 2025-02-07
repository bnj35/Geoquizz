<script setup>
  import { ref } from 'vue'
  import {useUserStore} from "@/stores/userStore.js";

  const userStore = useUserStore()  

  const username = ref('Utilisateur')
  const isLoggedIn = ref(false)

  const logout = () => {
    userStore.user_id = null
    userStore.user_stats_id = null
    userStore.score_total = null
    userStore.score_moyen = null
    userStore.nb_parties = null
    userStore.meilleur_coup = null
    userStore.pire_coup = null
    isLoggedIn.value = false
  }

 if (userStore.user_id) {
    isLoggedIn.value = true
  }

</script>

<template>
    <div class=" bg-gray-50 border-b-1 hidden md:flex items-center justify-between py-4" id="desktop_navbar">
      <div class="text-center basis-2/10"><router-link to="/">Home</router-link></div>

      <div class="flex justify-around basis-4/10" id="nav-links">
        <router-link v-if="isLoggedIn" to="/game">Game</router-link>
        
      </div>

      <div class="basis-2/10 gap-4 profile flex justify-center items-center">
        <router-link v-if="isLoggedIn" to="/profile">Profile</router-link>
        <p v-if="isLoggedIn" @click="logout">Logout</p>
        <router-link v-if="!isLoggedIn" to="/signup">Signup</router-link>
        <router-link v-if="!isLoggedIn" to="/signin">Signin</router-link>
      </div>
    </div>

  <div class="md:hidden fixed bottom-0 justify-between items-center flex py-4 w-full border-t-1 z-50 bg-gray-50" id="mobile_navbar">
    <div class="flex justify-around items-center w-full" id="nav-links">
      <router-link to="/"> <img class="w-6 h-6" src="../assets/svg/house-solid.svg" alt=""></router-link>
      <router-link v-if="isLoggedIn" to="createpartie"><img class="w-6 h-6" src="../assets/svg/gamepad-solid.svg" alt=""></router-link>
      <router-link v-if="isLoggedIn" to="profile"><img class="w-6 h-6" src="../assets/svg/user-regular.svg" alt=""></router-link>
      <router-link v-if="!isLoggedIn" to="signin"><img class="w-6 h-6" src="../assets/svg/right-to-bracket-solid.svg" alt=""></router-link>
    </div>
  </div>  
</template>
