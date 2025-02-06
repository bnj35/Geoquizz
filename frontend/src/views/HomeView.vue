<script setup>
import Navbar from '@/components/NavbarComponent.vue'
import HeroSection from "@/components/HeroComponent.vue";
import {getCurrentInstance, onMounted} from "vue";
import {createParty} from "@/utils/game/GameSystem.js";

onMounted(() => {
  createParty();
});

</script>

<template>
  <div class="bg-red-400 h-screen">
    <Navbar />
    <HeroSection/>
  </div>
</template>
