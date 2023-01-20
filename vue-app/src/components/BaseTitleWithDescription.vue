<template>
  <div class="toto">
    <h3 class="mb-4">
      {{ props.title }}
    </h3>
    <div class="max-witdh">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
});
</script>

<style lang="scss" scoped>
.max-witdh {
  max-width: 700px;
}
</style>