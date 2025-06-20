<template>
  <tr class="border-t hover:bg-gray-50">
    <td class="p-2 min-w-[180px]">
      <select
        v-model="line.product_id"
        @change="onProductChange(line)"
        :disabled="!productsLoaded"
        class="w-full p-2 border rounded-md bg-white disabled:opacity-50"
      >
        <option value="" disabled>
          {{ productsLoaded ? '— Select Product —' : 'Loading…' }}
        </option>
        <option
          v-for="p in availableProducts(index)"
          :key="p.id"
          :value="p.id"
        >
          {{ p.product_name }}
        </option>
      </select>
    </td>
    <td class="p-2 text-right">
      <input v-model.number="line.cost" readonly class="w-full p-2 border rounded-md text-right bg-gray-100" />
    </td>
    <td class="p-2 text-right">
      <input v-model.number="line.price" readonly class="w-full p-2 border rounded-md text-right bg-gray-100" />
    </td>
    <td class="p-2 text-right">
      <input v-model.number="line.qty" readonly class="w-full p-2 border rounded-md text-right bg-gray-100" />
    </td>
    <td class="p-2 text-right">
      <button
        type="button"
        @click="emit('remove', index)"
        :disabled="loading"
        class="text-red-600 hover:underline disabled:text-gray-400"
      >
        Remove
      </button>
    </td>
  </tr>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  line: Object,
  index: Number,
  products: Array,
  lines: Array,
  loading: Boolean
})
const emit = defineEmits(['remove', 'change'])
const productsLoaded = computed(() => props.products.length > 0)

function onProductChange(line) {
  emit('change', line)
}
function availableProducts(idx) {
  const selected = props.lines
    .filter((_, i) => i !== idx)
    .map(l => l.product_id)
    .filter(Boolean)
  return props.products.filter(p =>
    !selected.includes(p.id) || p.id === props.lines[idx].product_id
  )
}
</script>
