<template>
  <BaseNaveBar
    title="Admin panel"
  />
  <div class="text-center">
    <v-img
    max-height="70vh"
      :src="AdminImage"
    />
    <h2 class="mt-n16">Home</h2>
    <div>
      <v-btn
        color="primary"
        class="mt-4"
        @click="this.$router.push('/admin/company/sizes')"
      >
        Company Sizes
      </v-btn>
    </div>
    <div>
      <v-btn
        color="primary"
        class="mt-4"
        @click="this.$router.push('/admin/company/sectors')"
      >
        Company Sectors
      </v-btn>
    </div>
    <div>
      <v-btn
        color="primary"
        class="mt-4"
        @click="this.$router.push('/admin/company/revenues')"
      >
        Company Revenues
      </v-btn>
    </div>
  </div>
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import decodeMixin from '@/mixins/decode';
import { onMounted, ref } from 'vue';
import AdminImage from '@/assets/admin_image.svg';

let tokenData = ref('');
onMounted(() => {
  console.log('Home mounted');
  tokenData.value = decodeMixin.getDataFromToken();
});
</script>