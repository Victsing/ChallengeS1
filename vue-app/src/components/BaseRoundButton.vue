<template>
  <v-btn v-bind="$attrs" :color="props.color" rounded="lg" class="button pa-6 ma-2">
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

<style scoped>
.button{
  background-color: white;
  color: #000;
  border-radius: 20px;
  box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
  font-weight: 600;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: all 0.3s ease-in-out;
  display: flex;
  align-items: center;
  justify-content: center;
}

.button:hover {
  transform: translateY(-2px);
  box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.2);
}
</style>