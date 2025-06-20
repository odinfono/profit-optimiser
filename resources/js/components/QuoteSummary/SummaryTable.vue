<template>
  <div>
    <h2 class="text-lg font-bold text-gray-800 mt-4 mb-2">Summary</h2>
    <table class="table-auto w-full text-sm text-gray-700 border-collapse">
      <tbody>
        <tr class="odd:bg-white even:bg-gray-50">
          <td class="font-semibold py-1 pr-2 whitespace-nowrap">Gross Profit:</td>
          <td>{{ formatCurrency(result.total) }}</td>
        </tr>
        <tr class="odd:bg-white even:bg-gray-50">
          <td class="font-semibold py-1 pr-2 whitespace-nowrap">Margin:</td>
          <td>{{ result.net_margin }}%</td>
        </tr>
        <tr class="odd:bg-white even:bg-gray-50">
          <td class="font-semibold py-1 pr-2 whitespace-nowrap">Net Profit:</td>
          <td>{{ formatCurrency(result.net_profit) }}</td>
        </tr>
        <tr class="odd:bg-white even:bg-gray-50">
          <td class="font-semibold py-1 pr-2 whitespace-nowrap">Health:</td>
          <td>
            <span :class="healthClass + ' capitalize font-semibold'">
              {{ result.health }}
            </span>
          </td>
        </tr>
        <tr v-if="result.exceedsLaborThreshold" class="odd:bg-white even:bg-gray-50">
          <td colspan="2" class="font-semibold text-lg py-1 pr-2 whitespace-nowrap text-red-800">Labor hours has exceeded a sustainable threshold</td>
          
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
import { computed } from 'vue'

const props = defineProps({
  result: Object,
  formatCurrency: Function
})

const healthClass = computed(() => ({
  green: 'text-green-700',
  amber: 'text-yellow-700',
  red: 'text-red-700'
})[props.result?.health || ''])
</script>