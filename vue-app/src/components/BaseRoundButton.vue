<template>
  <v-btn v-bind="$attrs" :color="props.color" rounded="pill">
    <slot />
  </v-btn>
</template>

<script setup>
const props = defineProps({
  color: {
    type: String,
    required: true,
  },
});
</script>
