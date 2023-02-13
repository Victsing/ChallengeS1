<template>
  <v-snackbar v-bind="$attrs" :timeout="props.timeout" :color="props.color">
    {{ props.text }}
    <template v-slot:actions>
      <v-btn
        color="blue"
        variant="text"
        @click="$emit('closeSnackbar')"
        style="margin-left: 10px;"
      >
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>
<script setup>

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

<style scoped>
.v-snackbar {
  background-color: var(--v-snackbar-background-color, #323232);
  color: var(--v-snackbar-color, #ffffff);
  border-radius: var(--v-snackbar-border-radius, 4px);
  box-shadow: var(--v-snackbar-box-shadow, 0px 3px 5px rgba(0, 0, 0, 0.2));
  font-size: var(--v-snackbar-font-size, 14px);
  padding: var(--v-snackbar-padding, 12px);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.v-btn {
  font-size: var(--v-btn-font-size, 14px);
  color: var(--v-btn-color, #ffffff);
  background-color: var(--v-btn-background-color, #0275d8);
  border-radius: var(--v-btn-border-radius, 4px);
  padding: var(--v-btn-padding, 6px 12px);
  text-transform: uppercase;
  letter-spacing: var(--v-btn-letter-spacing, 1px);
  box-shadow: var(--v-btn-box-shadow, 0px 2px 4px rgba(0, 0, 0, 0.1));
}
</style>