<script setup>
import { defineProps } from 'vue'
import {signIn, signUp} from "@/utils/game/GameSystem.js";

const props = defineProps({
  title: {
    type: String,
    default: 'Login'
  },
  emailPlaceholder: {
    type: String,
    default: 'email@example.com'
  },
  passwordPlaceholder: {
    type: String,
    default: 'Password'
  },
  buttonText: {
    type: String,
    default: 'Sign In'
  },
  textLink: {
    type: String,
    default: 'Don’t have an account?'
  },
  paragraphe: {
    type: String,
    default: "S'inscrire"
  }
});

const handleSubmit = async (event) => {
  event.preventDefault();
  const email = event.target.email.value;
  const password = event.target.password.value;

  if (props.title === 'Sign In') {
    await signIn(email, password);
  } else {
    await signUp(email, password);
  }
};

</script>

<template>
  <div class="flex flex-col items-center justify-center bg-gray-100 p-8 rounded-lg shadow-lg text-center">
    <h1 class="text-gray-800 mb-5 text-2xl font-semibold">{{ title }}</h1>
    <form @submit="handleSubmit" class="flex flex-col w-full">
      <label for="email" class="text-gray-400 text-md text-left mb-1">Adresse email :</label>
      <input type="email" id="email" name="email" :placeholder="emailPlaceholder" required class="bg-gray-200 border border-gray-300 p-3 rounded mb-4 text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-gray-500" />

      <label for="password" class="text-gray-400 text-md text-left mb-1">Mot de passe :</label>
      <input type="password" id="password" name="password" :placeholder="passwordPlaceholder" required class="bg-gray-200 border border-gray-300 p-3 rounded mb-4 text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-gray-500" />

      <button type="submit" class="bg-gray-800 border-none p-3 text-white text-lg rounded cursor-pointer hover:bg-gray-700">{{ buttonText }}</button>
    </form>

    <div class="w-full h-px bg-gray-700 my-5"></div>

    <div v-if="title === 'Sign In'">
      <p class="text-gray-800">{{ textLink }} <a href="/signup" class="text-gray-800 underline">{{ paragraphe }}</a></p>
    </div>
    <div v-else>
      <p class="text-gray-800">{{ textLink }} <a href="/signin" class="text-gray-800 underline">{{ paragraphe }}</a></p>
    </div>
  </div>
</template>
