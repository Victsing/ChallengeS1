<template>
  <BaseNaveBar color="#15202B" class="text-white mb-16" />
  <v-row class="mt-16">
    <v-col>
      
      <v-tabs v-model="tab" class="mt-16 mb-16">
        <v-tab value="one">Se connecter</v-tab>
        <v-tab value="two">S'inscrire</v-tab>
        <v-tab value="three">Mot de passe oublié</v-tab>
      </v-tabs>

      <v-window v-model="tab" class="mb-4">
        <v-window-item value="one" class="mt-16">
          <v-form>
            <v-text-field
              label="Email"
              type="email"
              prepend-icon="mdi-account"
              variant="outlined"
              required
              v-model="loginEmail"
            />
            <v-text-field
              label="Mot de passe"
              type="password"
              prepend-icon="mdi-lock"
              variant="outlined"
              required
              class="mb-16"
              v-model="loginPassword"
            />
            <BaseRoundButton @click="login" type="submit" color="pink darken-3" :disabled="disableButton" class="ml-8 mb-16"
              >Se connecter</BaseRoundButton
            >
          </v-form>
        </v-window-item>
        <v-window-item value="two" class="mt-16">
          <v-form>
            <div class="d-flex">
              <v-text-field
                label="Prénom"
                variant="outlined"
                required
                prepend-icon="mdi-account"
                v-model="firstname"
              />
              <v-text-field
                label="Nom"
                variant="outlined"
                required
                prepend-icon="mdi-account"
                class="ml-4"
                v-model="lastname"
              />
            </div>
            <v-text-field
              label="Date de naissance"
              type="date"
              prepend-icon="mdi-calendar"
              variant="outlined"
              required
              v-model="birthday"
            />
            <v-text-field
              label="Email"
              type="email"
              prepend-icon="mdi-email"
              variant="outlined"
              required
              v-model="email"
            />
            <v-text-field
              label="Mot de passe"
              type="password"
              prepend-icon="mdi-lock"
              variant="outlined"
              required
              v-model="password"
            />
            <v-text-field
              label="Confirmation du mot de passe"
              type="password"
              prepend-icon="mdi-lock"
              variant="outlined"
              required
              v-model="confirmPassword"
              class="mb-16"
            />
            <BaseRoundButton @click="register" type="submit" color="pink darken-3" :disabled="disableButton" class="ml-8 mb-16"
              >S'inscrire</BaseRoundButton
            >
          </v-form>
        </v-window-item>
        <v-window-item value="three" class="mt-16">
          <v-form>
            <v-text-field
              label="Email"
              type="email"
              prepend-icon="mdi-account"
              variant="outlined"
              required
              v-model="forgottenPasswordEmail"
            />
            <BaseRoundButton @click="newPassword" type="submit" color="pink darken-3" :disabled="disableButton" class="ml-8 mb-16"
              >Continuer</BaseRoundButton
            >
          </v-form>
        </v-window-item>
      </v-window>
    </v-col>
    <v-col class="mt-16 ml-16">
      <v-img :src="PersonalInfo" alt="personal_info" />
    </v-col>
  </v-row>
  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    :timeout="3000"
    @closeSnackbar="snackbar = false"
  />
</template>

<script setup>
import { ref } from "vue";
import BaseRoundButton from "../components/BaseRoundButton.vue";
import AuthentificationApi from "../backend/AuthentificationApi";
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import PersonalInfo from '@/assets/personal_info.png';
import BaseSnackbar from "@/components/BaseSnackbar.vue";
import { useRouter, useRoute } from "vue-router";
import { getDataFromToken } from "@/mixins";

const route = useRoute();

const tab = ref(route.query.tab ? route.query.tab : "one");

let firstname = ref("");
let lastname = ref("");
let birthday = ref("");
let email = ref("");
let password = ref("");
let confirmPassword = ref("");

let disableButton = ref(false);

let loginEmail = ref("");
let loginPassword = ref("");

let forgottenPasswordEmail = ref("");

let snackbar = ref(false);
let snackbarText = ref("");
let snackbarColor = ref("");

const router = useRouter();

const register = async (e) => {
  disableButton.value = true;
  e.preventDefault();
  AuthentificationApi.register(
    firstname.value,
    lastname.value,
    birthday.value,
    email.value,
    password.value,
    new Date().toISOString()
  ).then((response) => {
    snackbar.value = true;
    snackbarText.value = "Inscription réussie, vous allez recevoir un email pour valider votre compte. Vous allez être redirigé vers la page d'accueil dans 3 secondes.";
    snackbarColor.value = "success";
    setTimeout(() => {
      router.push("/");
    }, 3000);
  }).catch(() => {
    snackbar.value = true;
    snackbarText.value = "Il y a eu une erreur, veuillez recharger la page et réessayer.";
    snackbarColor.value = "error";
    disableButton.value = false;
  });
};

const login = async (e) => {
  disableButton.value = true;
  e.preventDefault();
  AuthentificationApi.login(
    loginEmail.value,
    loginPassword.value
  ).then((response) => {
    localStorage.setItem("token", response.data.token);
    let data = getDataFromToken();
    if (data.roles.includes("ROLE_ADMIN")) {
      router.push("/admin");
    } else {
      router.push("/home");
    }
  }).catch(() => {
    snackbar.value = true;
    snackbarText.value = "Email ou mot de passe incorrect";
    snackbarColor.value = "error";
    disableButton.value = false;
  });
};

const newPassword = async (e) => {
  disableButton.value = true;
  e.preventDefault();
  AuthentificationApi.newPassword(
    forgottenPasswordEmail.value
  ).then((response) => {
    snackbar.value = true;
    snackbarText.value = "Email envoyé, veuillez ouvrir le lien que vous allez recevoir afin de réinitialiser votre mot de passe. Vous allez être redirigé vers la page d'accueil dans 3 secondes.";
    snackbarColor.value = "success";
    setTimeout(() => {
      router.push("/");
    }, 3000);
  }).catch(() => {
    snackbar.value = true;
    snackbarText.value = "Il ya eu une erreur, veuillez recharger la page et réessayer.";
    snackbarColor.value = "error";
    disableButton.value = false;
  });
};
</script>
