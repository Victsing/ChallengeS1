<template>
  <v-snackbar v-bind="attrs" :timeout="props.timeout" :color="props.color">
    {{ props.text }}
    <template v-slot:actions>
      <v-btn
        color="blue"
        variant="text"
        @click="$emit('closeSnackbar')"
      >
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineEmits(['closeSnackbar']);
const props = defineProps({
  text: {
    type: String,
    required: true,
  },
  timeout: {
    type: Number,
    default: 3000,
  },
  color: {
    type: String,
    required: true,
  },
});
</script>