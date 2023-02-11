<template>
  <div>
    <BaseNaveBar
      :title="`Welcome ${decoded.firstname} ${decoded.lastname}`"
      :employer="isEmployer"
    />
    <v-row class="align-center">
      <v-col>
        <h2 class="mb-16 text-center">Mes informations</h2>
        <form>
          <div class="d-flex">
            <v-text-field
              label="Firstname"
              variant="outlined"
              prepend-icon="mdi-account"
              v-model="firstname"
            />
            <v-text-field
              label="Lastname"
              variant="outlined"
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
            v-model="birthday"
          />
          <v-text-field
            label="Email"
            type="email"
            prepend-icon="mdi-email"
            variant="outlined"
            v-model="email"
            disabled
          />
          <v-text-field
            label="Current Password"
            type="password"
            prepend-icon="mdi-lock"
            variant="outlined"
            v-model="currentPassword"
          />
          <v-text-field
            label="Change Password"
            type="password"
            prepend-icon="mdi-lock"
            variant="outlined"
            v-model="password"
            :disabled="emptyCurrentPassword"
          />
          <v-text-field
            label="Confirm New Password"
            type="password"
            prepend-icon="mdi-lock"
            variant="outlined"
            v-model="confirmPassword"
            :disabled="emptyCurrentPassword"
          />
          <BaseRoundButton
            @click="updateProfile"
            type="submit"
            color="black"
            :disabled="disableButton"
          >
            Update
          </BaseRoundButton>
        </form>
      </v-col>
      <v-col>
        <v-img :src="ProfileInfo" />
      </v-col>
    </v-row>
    <BaseSnackbar
      v-model="snackbar"
      :text="snackbarText"
      :color="snackbarColor"
      :timeout="3000"
      @closeSnackbar="snackbar = false"
  />
  </div>
</template>

<script setup>
import BaseNaveBar from '@/components/BaseNaveBar.vue';
import jwt_decode from 'jwt-decode';
import ProfileInfo from '@/assets/profile_info.svg';
import BaseRoundButton from '@/components/BaseRoundButton.vue';
import { ref, computed, onMounted } from 'vue';
import AuthentificationApi from '@/backend/AuthentificationApi';
import BaseSnackbar from '@/components/BaseSnackbar.vue';
import { useRouter } from 'vue-router';


let firstname = ref('');
let lastname = ref('');
let birthday = ref('');
let email = ref('');
let me = ref({});
onMounted(() => {
  AuthentificationApi.getMe(decoded.id).then((response) => {
    me.value = response.data;
    firstname.value = response.data.firstname;
    lastname.value = response.data.lastname;
    birthday.value = response.data.birthdate;
    email.value = response.data.email;
  }).catch((error) => {
    console.log(error);
  });
});

const router = useRouter();

const token = localStorage.getItem('token');
const decoded = jwt_decode(token);

let snackbar = ref(false);
let snackbarColor = ref('');
let snackbarText = ref('');

let disableButton = ref(false);

let password = ref('');
let currentPassword = ref('');
let confirmPassword = ref('');
let userId = ref(decoded.id);

let validCurrentPassword = ref(false);

const emptyCurrentPassword = computed(() => {
  return currentPassword.value === '';
});

const validPasswords = computed(() => {
  return password.value === confirmPassword.value;
});

const checkCurrentPassword = async () => {
  await AuthentificationApi.login(
    email.value,
    currentPassword.value
  ).then((response) => {
    if (response.status === 200) {
      validCurrentPassword.value = true;
    }
  });
};

const updateProfile = async (e) => {
  e.preventDefault();
  disableButton.value = true;
  if (checkBirthday(birthday.value) === false) {
    snackbar.value = true;
    snackbarColor.value = 'error';
    snackbarText.value = 'You must be between 18 and 100 years old to use this website';
    disableButton.value = false;
    return;
  }
  if (!emptyCurrentPassword.value) {
    await checkCurrentPassword();
    if (validCurrentPassword.value && validPasswords.value) {
      AuthentificationApi.updateUser(
        userId.value,
        {
          firstname: firstname.value,
          lastname: lastname.value,
          birthdate: birthday.value,
          password: password.value,
        }
      ).then(() => {
        snackbar.value = true;
        snackbarColor.value = 'success';
        snackbarText.value = 'Your profile has been updated, you will be redirected to the login page in 3 seconds';
        currentPassword.value = '';
        setTimeout(() => {
          localStorage.removeItem('token');
          router.push('/authentication');
        }, 3000);
      }).catch(() => {
        snackbar.value = true;
        snackbarColor.value = 'error';
        snackbarText.value = 'An error occured, please try again';
        disableButton.value = false;
      });
    } else {
      snackbar.value = true;
      snackbarColor.value = 'error';
      snackbarText.value = 'Please make sure that the passwords are the same and that the current password is correct';
      disableButton.value = false;
    }
  } else {
    AuthentificationApi.updateUser(
      userId.value,
      {
        firstname: firstname.value,
        lastname: lastname.value,
        birthdate: birthday.value,
      }
    ).then(() => {
      snackbar.value = true;
      snackbarColor.value = 'success';
      snackbarText.value = 'Your profile has been updated';
      currentPassword.value = '';
      setTimeout(() => {
        router.push('/home');
      }, 3000);
    }).catch(() => {
      snackbar.value = true;
      snackbarColor.value = 'error';
      snackbarText.value = 'An error occured, please try again';
      disableButton.value = false;
    });
  }
};
const isEmployer = computed(() => {
  return decoded.roles.includes('ROLE_EMPLOYER');
});
const checkBirthday = (birthday) => {
  let today = new Date();
  let birthDate = new Date(birthday);
  let age = today.getFullYear() - birthDate.getFullYear();
  let m = today.getMonth() - birthDate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age >= 18 && age <= 100;
};
</script>
