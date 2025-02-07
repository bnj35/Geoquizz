<script setup>
import FormComponent from "@/components/FormComponent.vue";
import Navbar from "@/components/NavbarComponent.vue";

</script>

<template>
  <Navbar />
  <div class="flex justify-center items-center h-screen">
    <FormComponent
      title="Sign In"
      emailPlaceholder="Enter your email"
      passwordPlaceholder="Enter your password"
      buttonText="Sign In"
      textLink="Already have an account?"
      paragraphe="Sign Up"
    />
  </div>
</template>

