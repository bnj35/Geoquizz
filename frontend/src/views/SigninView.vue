<script setup>
import FormComponent from "@/components/FormComponent.vue";
import Navbar from "@/components/NavbarComponent.vue";

</script>

<template>
  <Navbar />
  <div>
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

<style scoped>
div {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}
</style>
