<template>
  <div>
    <h2 class="text-lg font-bold text-gray-800 mb-2">Product Breakdown</h2>
    <table class="table-auto w-full text-sm text-gray-700 border-collapse mb-4">
      <thead>
        <tr class="text-sm font-semibold bg-gray-100">
          <th class="text-left py-1 pr-2">Product</th>
          <th class="text-left py-1 pr-2">Gross Profit</th>
          <th class="text-left py-1 pr-2">Margin</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(line, index) in lines"
          :key="index"
          class="align-top odd:bg-white even:bg-gray-50"
        >
          <td class="pr-5 pb-3 break-words max-w-xs whitespace-normal">
            {{ productNames[index] }}
          </td>
          <td class="pr-5 whitespace-normal">{{ formatCurrency(line.gross) }}</td>
          <td class="whitespace-normal">{{ line.margin }}%</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
defineProps({
  lines: Array,
  productNames: Array
})

const formatCurrency = (value) => {
  const number = Number(value)
  return isNaN(number)
    ? '-'
    : new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
      }).format(number)
}
</script>