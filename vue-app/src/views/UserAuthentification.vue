<template>
  <v-tabs v-model="tab" class="mb-6">
    <v-tab value="one">Login</v-tab>
    <v-tab value="two">Register</v-tab>
    <v-tab value="three">Forgot Password</v-tab>
  </v-tabs>
  <v-window v-model="tab" class="pb-4">
    <v-window-item value="one">
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
          label="Password"
          type="password"
          prepend-icon="mdi-lock"
          variant="outlined"
          required
          class="mb-16"
          v-model="loginPassword"
        />
        <BaseRoundButton @click="login" type="submit" color="black" to="#"
          >Se connecter</BaseRoundButton
        >
      </v-form>
    </v-window-item>
    <v-window-item value="two">
      <v-form>
        <div class="d-flex">
          <v-text-field
            label="Firstname"
            variant="outlined"
            required
            prepend-icon="mdi-account"
            v-model="firstname"
          />
          <v-text-field
            label="Lastname"
            variant="outlined"
            required
            prepend-icon="mdi-account"
            class="ml-4"
            v-model="lastname"
          />
        </div>
        <v-text-field
          label="Birthday"
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
          label="Password"
          type="password"
          prepend-icon="mdi-lock"
          variant="outlined"
          required
          v-model="password"
        />
        <v-text-field
          label="Confirm Password"
          type="password"
          prepend-icon="mdi-lock"
          variant="outlined"
          required
          v-model="confirmPassword"
          class="mb-16"
        />
        <BaseRoundButton @click="register" type="submit" color="black" to="#"
          >S'inscrire</BaseRoundButton
        >
      </v-form>
    </v-window-item>
    <v-window-item value="three">
      <v-form>
        <v-text-field
          label="Email"
          type="email"
          prepend-icon="mdi-account"
          variant="outlined"
          required
          v-model="forgottenPasswordEmail"
        />
        <BaseRoundButton @click="login" type="submit" color="black" to="#"
          >Continuer</BaseRoundButton
        >
      </v-form>
    </v-window-item>
  </v-window>
  <div class="text-center">
    <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    :timeout="3000"
    @close-snackbar="snackbar = false"
    />
  </div>
</template>

<script setup>
import { ref } from "vue";
import BaseRoundButton from "../components/BaseRoundButton.vue";
import AuthentificationApi from "../backend/AuthentificationApi";
import BaseSnackbar from "@/components/BaseSnackbar.vue";

const tab = ref("one");
let firstname = ref("");
let lastname = ref("");
let birthday = ref("");
let email = ref("");
let password = ref("");
let confirmPassword = ref("");

let loginEmail = ref("");
let loginPassword = ref("");

let forgottenPasswordEmail = ref("");

let snackbar = ref(false);
let snackbarText = ref("");
let snackbarColor = ref("");

const register = async () => {
  AuthentificationApi.register(
    firstname.value,
    lastname.value,
    birthday.value,
    email.value,
    password.value,
    new Date().toISOString()
  ).then((response) => {
    console.log(response.data);
    snackbar.value = true;
    snackbarText.value = "Inscription réussie";
    snackbarColor.value = "success";
  }).catch(() => {
    snackbar.value = true;
    snackbarText.value = "Email déjà utilisé";
    snackbarColor.value = "error";
  });
};

const login = async () => {
  AuthentificationApi.login(
    loginEmail.value,
    loginPassword.value
  ).then((response) => {
    console.log(response.data);
    snackbar.value = true;
    snackbarText.value = "Connexion réussie";
    snackbarColor.value = "success";
  }).catch(() => {
    snackbar.value = true;
    snackbarText.value = "Email ou mot de passe incorrect";
    snackbarColor.value = "error";
  });
};
</script>
