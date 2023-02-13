<template>
  <base-nave-bar color="black" />
  <v-container>
    <v-row>
      <v-col>
        <base-title>Voici la page de gestion des users</base-title>
      </v-col>
    </v-row>
    <v-row class="h-100">
      <v-col v-if="loading" offset="5">
        <v-progress-circular color="blue-lighten-3" indeterminate :size="80" :width="6" />
      </v-col>
      <v-col v-else v-for="(item, index) in users" :key="index">
        <v-card :title="`${item.lastname} ${item.firstname}`">
          <v-card-text>
            <p>{{ item.email }}</p>
            <p>Roles: {{ item.roles }}</p>
            <p v-if="item.company && item.company.length">Entreprise: {{ item.company }}</p>
            <p>Anniversaire : {{ item.birthdate }}</p>
          </v-card-text>
          <v-card-actions>
            <v-btn
              @click="
                dialog = true;
                selected = item.id;
              "
              variant="flat"
              color="error"
              >Supprimer</v-btn
            >
            <v-btn
              @click="
                this.$router.push(`/admin/users/${item.id}/edit`);
              "
              variant="flat"
              color="primary"
              >Editer</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <v-dialog v-model="dialog" persistent>
      <v-card>
        <v-card-text>Vous allez supprimer cet utilisateur, êtes-vous sûr ?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="dialog = false">Non</v-btn>
          <v-btn
            color="warning"
            @click="
              remove();
              dialog = false;
              selected = null;
            "
            >Oui</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <BaseSnackBar
      v-model="snackbar"
      :text="snackbarText"
      :color="snackbarColor"
      :timeout="30000"
      @close-snackbar="snackbar = false"
    />
  </v-container>
</template>

<script setup>
import { BaseNaveBar, BaseTitle, BaseSnackBar } from '@/components';
import UserApi from '@/backend/UserApi';
import { onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

let users = reactive([]);
const loading = ref(true);
const dialog = ref(false);
const selected = ref(null);

const route = useRoute();
const router = useRouter();

const snackbar = ref(false);
const snackbarText = ref('');
const snackbarColor = ref('success');

onMounted(async () => {
  const res = await UserApi.getUsers();
  console.log(res)
  users = res?.data['hydra:member'];
  loading.value = false;
});

const remove = async () => {
  const res = await UserApi.removeUser(selected.value);
  if (res.status === 204) {
    users.splice(users.findIndex((user) => user.id === selected.value), 1);
    snackbarText.value = 'Utilisateur supprimé';
    snackbar.value = true;
  } else {
    snackbarText.value = 'Une erreur est survenue';
    snackbarColor.value = 'error';
    snackbar.value = true;
  }
  selected.value = null;
};
</script>
