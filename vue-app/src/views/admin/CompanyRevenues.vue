<template>
  <BaseNaveBar title="Admin panel" :admin="admin" />
  <v-container>
    <v-row>
      <v-col>
        <BaseTitle>Gestion des chiffres d'affaires d'entreprise</BaseTitle>

        <h2>Vous pouvez gérer les différentes valeurs de chiffres d'affaires d'entreprises que les employeurs pourront mettre.</h2>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="6">
        <v-progress-circular v-if="isLoading" color="blue-lighten-3" indeterminate :size="80" :width="6" />
        <v-list class="mb-5" v-else-if="companyRevenues.length">
          <v-list-item v-for="(item, i) in companyRevenuesList" :key="i" :value="item" active-color="primary">
            <template v-slot:prepend>
              <v-icon :icon="item.deleteIcon" @click="handleAction(item, 'delete')"></v-icon>
            </template>
            <v-list-item-title v-text="item.title"></v-list-item-title>
            <template v-slot:append>
              <v-icon
                :icon="item.editIcon"
                @click="handleAction(item, 'edit')"
                :class="{ disabled: editElement && item.id === editElement.id }"
              ></v-icon>
            </template>
          </v-list-item>
        </v-list>
        <h3 v-else class="mb-5">Vous n'avez pas encore incrit de valeurs.</h3>
      </v-col>
      <v-col cols="6" v-if="showForm">
        <v-form ref="form">
          <v-text-field v-model="editElement.title" :rules="rules" required outlined dense></v-text-field>
          <v-btn class="mr-3" color="primary" @click="edit">Modifier</v-btn>
          <v-btn variant="flat" color="error" @click="closeEdit">Close</v-btn>
        </v-form>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-btn color="primary" @click="newDialog = true"> New Company Revenue </v-btn>
      </v-col>
    </v-row>
    <base-snack-bar
      v-model="snackbar"
      :text="snackbarText"
      :color="snackbarColor"
      :timeout="3000"
      @closeSnackbar="snackbar = false"
    />
    <v-dialog v-model="dialog" persistent>
      <v-card>
        <v-card-text>
          Vous vous apprétez à <strong class="text-warning">supprimer</strong> cet élément "<strong
            class="text-warning"
            >{{ dialogElement.title }}</strong
          >". Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="handleDialog(false)">Non</v-btn>
          <v-btn color="warning" @click="remove">Oui</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="newDialog">
      <v-card>
        <v-card-text>
          <v-form>
            <v-text-field v-model="newRevenue" placeholder="Nom du nouveau chiffre d'affaires (ex: [50K-100K])" outlined dense />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="newDialog = false">Annuler</v-btn>
          <v-btn color="warning" @click="addRevenue(); newDialog = false">Ajouter</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { BaseNaveBar, BaseTitle, BaseSnackBar } from '@/components';
import AdminApi from '@/backend/AdminApi';
import { onMounted, ref, computed, reactive } from 'vue';
import { getDataFromToken } from '@/mixins';

let tokenData = getDataFromToken();
let admin = computed(() => {
  return tokenData.roles.includes('ROLE_ADMIN');
});
const companyRevenues = ref([]);
const snackbar = ref(false);
const snackbarText = ref('');
const snackbarColor = ref('');
const dialog = ref(false);
const showForm = ref(false);
const isLoading = ref(false);
let dialogElement = reactive({});
let editElement = ref({ title: '', id: null });
let newDialog = ref(false);
let newRevenue = ref('');
const rules = [
  (v) => !!v || 'Required',
  (v) => v.length >= 3 || 'Must be superior 3 characters'
];

onMounted(async () => {
  await fetchCompagnyRevenues();
});

const addRevenue = () => {
  AdminApi.createCompanyRevenue(newRevenue.value).then((response) => {
    if (response.status === 201) {
      snackbarText.value = 'Chiffre d\'affaire ajoutée avec succès';
      snackbarColor.value = 'success';
      snackbar.value = true;
      fetchCompagnyRevenues();
    } else {
      snackbarText.value = 'Une erreur est survenue';
      snackbarColor.value = 'error';
      snackbar.value = true;
    }
  });
};

const handleDialog = (value) => {
  dialog.value = value;
  if (!value) {
    dialogElement = {};
  }
};

const fetchCompagnyRevenues = async() => {
  isLoading.value = true;
  const response = await AdminApi.getCompanyRevenues();
  if (response.status === 200) {
    companyRevenues.value = response.data['hydra:member'];
  } else {
    console.error('Error while fetching company revenues');
  }
  isLoading.value = false;
};

const handleAction = (elem, status) => {
  if (status === 'delete') {
    dialogElement = elem;
    dialog.value = true;
  } else if (status === 'edit') {
    editElement.value.title = elem.title;
    editElement.value.id = elem.id;
    if (!showForm.value) showForm.value = true;
    console.log(editElement, 'ddd');
  }
};

const closeEdit = (e) => {
  e.preventDefault();
  editElement.value = null;
  showForm.value = false;
};

const edit = async (e) => {
  console.log(editElement.value);

  e.preventDefault();
  try {
    const response = await AdminApi.updateCompanyRevenue(editElement.value.id, editElement.value.title);
    console.log(response);
    if (response.status === 200) {
      snackbarText.value = 'La taille a bien été modifié';
      snackbarColor.value = 'success';
      snackbar.value = true;
      await fetchCompagnyRevenues();
    } else {
      snackbarText.value = 'Une erreur est survenue';
      snackbarColor.value = 'error';
      snackbar.value = true;
    }
  } catch (error) {
    snackbarText.value = 'Une erreur est survenue';
    snackbarColor.value = 'error';
    snackbar.value = true;
  }
  editElement.value = {title: '', id: null};
  showForm.value = false;
};

const remove = async () => {
  try {
    const response = await AdminApi.deleteCompanyRevenue(dialogElement.id);
    if (response.status === 204) {
      snackbarText.value = 'La taille a bien été supprimée';
      snackbarColor.value = 'success';
      snackbar.value = true;
      companyRevenues.value = companyRevenues.value.filter((companyRevenue) => companyRevenue.id !== dialogElement.id);
    } else {
      snackbarText.value = 'Une erreur est survenue';
      snackbarColor.value = 'error';
      snackbar.value = true;
    }
  } catch (error) {
    if (error.response.data["hydra:description"].includes('Foreign key violation')) {
      snackbarText.value = 'Le chiffre d\'affaires est peut-être utilisée par une entreprise, veuillez vérifier avant de supprimer';
      snackbarColor.value = 'error';
      snackbar.value = true;
    } else {
      snackbarText.value = 'Une erreur est survenue';
      snackbarColor.value = 'error';
      snackbar.value = true;
    }
  }

  dialog.value = false;
  dialogElement = {};
};

const companyRevenuesList = computed(() => {
  return companyRevenues.value.map((companyRevenue) => {
    return {
      title: companyRevenue.revenue,
      id: companyRevenue.id,
      deleteIcon: 'mdi-close',
      editIcon: 'mdi-pencil'
    };
  });
});
</script>
<style scoped lang="scss">
.disabled {
  pointer-events: none;
}
</style>
