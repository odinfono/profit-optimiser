<template>
  <div class="flex flex-wrap gap-4">
    <button
      type="button"
      @click="$emit('add')"
      :disabled="!canAdd || loading"
      :class="baseClass(canAdd && !loading, 'blue')"
    >
      + Add Product
    </button>

    <button
      type="button"
      @click="$emit('add-all')"
      :disabled="!canAdd || loading"
      :class="baseClass(canAdd && !loading, 'green')"
    >
      + Add All Products
    </button>

    <button
      type="button"
      @click="$emit('remove-all')"
      :disabled="loading"
      :class="baseClass(!loading, 'rose')"
    >
      Remove All
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  canAdd: Boolean,
  loading: Boolean
})

// Explicit class map to prevent purge issues
const colorClassMap = {
  blue: {
    enabled: 'bg-blue-600 hover:bg-blue-700',
    disabled: 'bg-blue-300',
  },
  green: {
    enabled: 'bg-green-600 hover:bg-green-700',
    disabled: 'bg-green-300',
  },
  rose: {
    enabled: 'bg-rose-600 hover:bg-rose-700',
    disabled: 'bg-rose-300',
  },
}

const baseClass = (enabled, color) => {
  const colorClass = colorClassMap[color]
  return `px-4 py-2 rounded-md text-white ${
    enabled ? colorClass.enabled : `${colorClass.disabled} cursor-not-allowed`
  }`
}
</script>
