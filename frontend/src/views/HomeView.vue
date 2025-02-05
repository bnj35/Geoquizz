<script setup>
import Navbar from '@/components/NavbarComponent.vue'
import HeroSection from "@/components/HeroComponent.vue";
import {getCurrentInstance, onMounted} from "vue";
import axios from "axios";

const api = getCurrentInstance().appContext.config.globalProperties.$api();

async function createParty() {
  try {
    // const response = await axios.post('http://gateway/parties', {
    //   "nom": "partiddefefegziuiebugezguizeguibiedede",
    //   "token": "dededf",
    //   "nb_photos": 3,
    //   "score": 10,
    //   "theme": "Nancy",
    //   "temps": 10
    // });
    //
    // const response = await api.post('/parties', {
    //   "nom": "partiddefefegziuiebugezguizeguibie3dede",
    //   "token": "dededf",
    //   "nb_photos": 3,
    //   "score": 10,
    //   "theme": "Nancy",
    //   "temps": 10
    // });
    const response = await axios.get('http://gateway/parties');
    console.log(response);
    console.log(response.data);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
  }
}

onMounted(async () => {
  const party = await createParty();
});

</script>

<template>
  <div class="bg-red-400 h-screen">
    <Navbar/>
    <HeroSection/>
  </div>
</template>
