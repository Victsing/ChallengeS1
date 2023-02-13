<template>
  <BaseNaveBar title="Admin panel" />
  <h1>NewCompanySector</h1>

  <div class="form-group">
    <label for="sectorInput">Sector</label>
    <input 
      id="sectorInput"
      type="text"
      v-model="sector" 
    />
    <BaseRoundButton 
      @click="createSector"
      text="Create Sector"
    />
  </div>

  <BaseSnackbar
    v-model="snackbar"
    :text="snackbarText"
    :color="snackbarColor"
    :timeout="3000"
    @closeSnackbar="closeSnackbar"
  />
</template>

<script>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import BaseRoundButton from "@/components/BaseRoundButton.vue";
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import AdminApi from '@/backend/AdminApi';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

export default {
  components: {
    BaseNaveBar,
    BaseRoundButton,
    BaseSnackbar
  },
  data() {
    return {
      sector: "",
      snackbar: false,
      snackbarText: "",
      snackbarColor: ""
    };
  },
  methods: {
    async createSector(e) {
      e.preventDefault();
      try {
        await AdminApi.createCompanySector(this.sector);
        router.push("/admin/company/sector");
      } catch (error) {
        this.snackbarText = "Error while creating company sector";
        this.snackbarColor = "error";
        this.snackbar = true;
      }
    },
    closeSnackbar() {
      this.snackbar = false;
    }
  }
};
</script>
