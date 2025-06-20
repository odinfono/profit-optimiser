<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div v-for="input in inputs" :key="input.label">
      <label class="text-sm font-semibold text-gray-700">{{ input.label }}</label>
      <input
        v-model.number="input.model.value"
        type="number"
        class="w-full p-2 border rounded-md text-right"
      />
    </div>
  </div>
</template>

<script setup>
defineProps({
  inputs: Array
})
</script>
