<script setup>
import FormComponent from "@/components/FormComponent.vue";
import Navbar from "@/components/NavbarComponent.vue";
</script>

<template>
  <Navbar />
  <div class="flex justify-center items-center h-screen">
    <FormComponent
      title="S'inscrire"
      emailPlaceholder="Entrez votre adresse e-mail"
      passwordPlaceholder="Entrez votre mot de passe"
      buttonText="S'inscrire"
      textLink="Vous avez déjà un compte ?"
      paragraphe="Se connecter"
    />
  </div>
</template>
