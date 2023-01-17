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
        <BaseRoundButton @click="login" type="submit" color="black" to="/login">Se connecter</BaseRoundButton>
      </v-form>
    </v-window-item>
    <v-window-item value="two">
      <v-form>
        <div class="d-flex">
          <v-text-field label="Firstname" variant="outlined" required prepend-icon="mdi-account" v-model="firstname" />
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
        <v-text-field label="Email" type="email" prepend-icon="mdi-email" variant="outlined" required v-model="email" />
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
        <BaseRoundButton @click="login" type="submit" color="black" to="/login">S'inscrire</BaseRoundButton>
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
        <BaseRoundButton @click="login" type="submit" color="black" to="/login">Continuer</BaseRoundButton>
      </v-form>
    </v-window-item>
  </v-window>
</template>

<script setup>
import { ref } from 'vue';
import BaseRoundButton from '../components/BaseRoundButton.vue';

const tab = ref('one');
let firstname = ref('');
let lastname = ref('');
let birthday = ref('');
let email = ref('');
let password = ref('');
let confirmPassword = ref('');

let loginEmail = ref('');
let loginPassword = ref('');

let forgottenPasswordEmail = ref('');
</script>
