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
    default: 'Sign Up'
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
  <div class="form-container">
    <h1>{{ title }}</h1>
    <form @submit="handleSubmit">
      <label for="email">Email address</label>
      <input type="email" id="email" name="email" :placeholder="emailPlaceholder" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" :placeholder="passwordPlaceholder" required />

      <button type="submit">{{ buttonText }}</button>
    </form>

    <div class="separator"></div>

    <div v-if="title === 'Sign In'">
      <p>{{ textLink }} <a href="/signup">{{ paragraphe }}</a></p>
    </div>
    <div v-else>
      <p>{{ textLink }} <a href="/signin">{{ paragraphe }}</a></p>
    </div>
  </div>

</template>

<style scoped>
.form-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #1f1f1f;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  width: 400px;
  height: 500px;
  text-align: center;
}

h1 {
  color: white;
  margin-bottom: 20px;
  font-size: 24px;
  font-weight: 600;
}

form {
  display: flex;
  flex-direction: column;
  width: 100%;
}

label {
  color: #b0b0b0;
  font-size: 14px;
  text-align: left;
  margin-bottom: 5px;
}

input {
  background-color: #333;
  border: none;
  padding: 12px;
  border-radius: 5px;
  margin-bottom: 15px;
  color: white;
  width: 100%;
}

button {
  background-color: #00c37d;
  border: none;
  padding: 12px;
  color: white;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
}

.separator {
  width: 100%;
  height: 2px;
  background-color: #444;
  margin: 20px 0;
}

p {
  color: white;
}

a {
  color: #00c37d;
  text-decoration: none;
}
</style>
