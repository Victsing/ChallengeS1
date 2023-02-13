<template>
  <div class="container">
    <h3 class="container__title mb-4">{{ props.title }}</h3>
    <div class="container__content max-width">
      <slot />
    </div>
  </div>
</template>
<script setup>
  const props = defineProps({
    title: {
      type: String,
      required: true
    }
  });
</script>
<style lang="scss" scoped>
.container {
  &__title {
    font-weight: bold;
  }

  &__content {
    max-width: 700px;
    margin: 0 auto;
  }
}

.max-width {
  max-width: 700px;
  margin: 0 auto;
}
</style>