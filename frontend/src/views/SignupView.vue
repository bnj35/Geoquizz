<script setup>
import FormComponent from "@/components/FormComponent.vue";
import Navbar from "@/components/NavbarComponent.vue";
</script>

<template>
  <Navbar />
  <div>
    <FormComponent
      title="Sign Up"
      emailPlaceholder="Enter your email"
      passwordPlaceholder="Create a password"
      buttonText="Sign Up"
      textLink="Already have an account?"
      paragraphe="Sign In"
    />
  </div>
</template>

<style scoped>
div {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}
</style>
