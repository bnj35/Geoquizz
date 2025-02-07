<script setup>
import { useUserStore } from "@/stores/userStore.js";
import { getUserStats } from "@/utils/game/GameSystem.js";
import { onMounted } from 'vue';
import { ref } from 'vue';
import { getUsername } from "@/utils/game/GameSystem.js";

const userStore = useUserStore();
const username = ref('');

const userName = () => {
    getUsername(userStore.user_id).then((data) => {
        username.value = data.user.email;
        username.value = username.value.split('@')[0];
    });
}

getUserStats(userStore.user_id).then((data) => {
    userStore.score_total = data.stats.score_total;
    userStore.score_moyen = data.stats.score_moyen;
    userStore.nb_parties = data.stats.nb_parties;
    userStore.meilleur_coup = data.stats.meilleur_score;
    userStore.pire_coup = data.stats.pire_coups;
});

onMounted(() => {
    userName();
}
)
</script>

<template>
    <div class="flex flex-col justify-center items-center h-screen mb-2 shadow-2xl">
        <h1 class=" text-7xl mb-10">Bienvenue {{ username }}</h1>
    <div id="user_stats" class="p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 class="text-lg font-bold mb-4">Statistiques pour toi {{ username }}</h2>
        <p class="text-2xl mb-2">Score total : {{ userStore.score_total }}</p>
        <p class="text-2xl mb-2">Score moyen : {{ userStore.score_moyen }}</p>
        <p class="text-2xl mb-2">Nombre de parties : {{ userStore.nb_parties }}</p>
        <p class="text-2xl mb-2">Meilleur coup : {{ userStore.meilleur_coup }}</p>
        <p class="text-2xl">Pire score : {{ userStore.pire_coup }}</p>
    </div>
</div>
</template>
