<script setup>
import Navbar from '@/components/NavbarComponent.vue'
import HeroSection from "@/components/HeroComponent.vue";
import ToastComponent from "@/components/ToastComponent.vue";

import {useUserStore} from "@/stores/userStore.js";
import {onMounted} from "vue";

const userStore = useUserStore();

onMounted(() => {
  userStore.showToast("message erreur test")
});

</script>

<template>
  <div class="bg-gray-50 h-screen">
    <Navbar />
    <HeroSection/>
  </div>
</template>
