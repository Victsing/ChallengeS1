<template>
  <v-form>
    <v-text-field
      label="New Password"
      type="password"
      prepend-icon="mdi-lock"
      variant="outlined"
      required
      v-model="resetPassword"
    />
    <v-text-field
      label="Confirm New Password"
      type="password"
      prepend-icon="mdi-lock"
      variant="outlined"
      required
      class="mb-16"
      v-model="confirmResetPassword"
    />
    <BaseRoundButton @click="login" type="submit" color="black" to="/login">Confirmer</BaseRoundButton>
  </v-form>
</template>

<script setup>
import BaseRoundButton from '../components/BaseRoundButton.vue';
import { ref } from 'vue';

let resetPassword = ref('');
let confirmResetPassword = ref('');
</script>
