<template>
  <v-btn v-bind="$attrs" :color="props.color" rounded="pill">
    <slot />
  </v-btn>
</template>

<script setup>
import { defineProps } from "vue";
const props = defineProps({
  color: {
    type: String,
    required: true,
  },
});
</script>
