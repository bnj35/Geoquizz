<script setup>
import Navbar from '@/components/NavbarComponent.vue'
import HeroSection from "@/components/HeroComponent.vue";
import ToastComponent from "@/components/ToastComponent.vue";

import {useUserStore} from "@/stores/userStore.js";
import {onMounted} from "vue";

const userStore = useUserStore();

onMounted(() => {
  userStore.showToast("le message d'erreur estle message d'erreur est le suivant : eele message d'erreur est le suivant : eele message d'erreur est le suivant : eele message d'erreur est le suivant : eele message d'erreur est le suivant : ee le suivant : eee")
});

</script>

<template>
  <div class="bg-gray-50 h-screen">
    <Navbar />
    <HeroSection/>
    <!-- <ToastComponent message="eeegfplmgfkmoogfkmogkfgfoghkmoee"/> -->
  </div>
</template>
