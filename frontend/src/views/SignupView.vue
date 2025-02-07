<script setup>
import FormComponent from "@/components/FormComponent.vue";
import Navbar from "@/components/NavbarComponent.vue";
</script>

<template>
  <Navbar />
  <div class="flex justify-center items-center h-screen">
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
