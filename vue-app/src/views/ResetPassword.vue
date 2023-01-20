<template>
  <BaseNaveBar color="black"/>
  <h2 class="mb-4">Reset you password</h2>
  <v-row>
    <v-col>
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
        <BaseRoundButton @click="resetUserPassword" type="submit" color="black">Confirmer</BaseRoundButton>
      </v-form>
    </v-col>
    <v-col>
      <v-img :src="FluFace" alt="flu_face" />
    </v-col>
  </v-row>
  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    :timeout="3000"
    @close-snackbar="snackbar = false"
  />
</template>

<script setup>
import BaseRoundButton from '../components/BaseRoundButton.vue';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import AuthentificationApi from '../backend/AuthentificationApi';
import FluFace from '@/assets/flu_face.svg';
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import { ref } from 'vue';
import { useRoute } from 'vue-router';

let resetPassword = ref('');
let confirmResetPassword = ref('');

let snackbar = ref(false);
let snackbarColor = ref('');
let snackbarText = ref('');

const route = useRoute();
const token = route.params.token;

const resetUserPassword = (e) => {
  e.preventDefault();
  if (resetPassword.value === confirmResetPassword.value) {
    AuthentificationApi.resetPassword(token, resetPassword.value)
      .then((response) => {
        snackbar.value = true;
        snackbarText.value = "Password reset successfully!";
        snackbarColor.value = 'success';
        console.log(response.data);
        console.log('Password reset successfully!');
      })
      .catch((error) => {
        console.log(error);
        snackbar.value = true;
        snackbarText.value = "Password reset failed!";
        snackbarColor.value = 'error';
      });
  } else {
    snackbar.value = true;
    snackbarText.value = "Passwords do not match!";
    snackbarColor.value = 'error';
  }
};

</script>
