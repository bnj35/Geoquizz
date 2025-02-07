<script setup>
import FormComponent from "@/components/FormComponent.vue";
import Navbar from "@/components/NavbarComponent.vue";

</script>

<template>
  <Navbar />
  <div class="flex justify-center items-center h-screen">
    <FormComponent
      title="Se connecter"
      emailPlaceholder="Entrez votre adresse e-mail"
      passwordPlaceholder="Entrez votre mot de passe"
      buttonText="Connexion"
      textLink="Vous n'avez pas de compte ?"
      paragraphe="S'inscrire"
    />
  </div>
</template>

