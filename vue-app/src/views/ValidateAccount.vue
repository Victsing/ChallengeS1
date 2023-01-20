<template>
  <BaseNaveBar color="black"/>
  <div class="text-center">
    <h1>Validate Account</h1>
    <div v-if="validToken">
      <img :src="HappyFace" alt="Happy Face" />
      <BaseStackedText>
        <template #topText>
          <h3>
            Your account has been validated, you can now login
          </h3>
        </template>
        <template #bottomText>
          <div>
            <router-link to="/authentication">Go to the login page</router-link>
          </div>
        </template>
      </BaseStackedText>
    </div>
    <div v-else>
      <img :src="AngryFace" alt="Angry Face" />
      <BaseStackedText>
        <template #topText>
          <h3>
            I'm sorry, your account cannot be validated, please make to click on the link in the email we sent you
          </h3>
        </template>
        <template #bottomText>
          <div>
            <router-link to="/">Get back to the homepage</router-link>
          </div>
        </template>
      </BaseStackedText>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import AngryFace from '@/assets/angry_face.svg';
import HappyFace from '@/assets/happy_face.svg';
import BaseStackedText from '@/components/BaseStackedText.vue';

let validToken = ref(false);

onMounted(() => {
  const route = useRoute();
  const token = route.params.token;
  AuthentificationApi.validateAccount(token)
    .then((response) => {
      console.log(response);
      validToken.value = true;
    })
    .catch((error) => {
      console.log(error);
    });
});
</script>