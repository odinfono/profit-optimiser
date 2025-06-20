<template>
  <form @submit.prevent="submit" class="no-print bg-white p-6 sm:p-8 space-y-6 max-w-7xl mx-auto border rounded-xl">
    <!-- Product Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm border border-gray-200">
        <thead class="bg-gray-100 text-left text-gray-700 font-semibold">
          <tr>
            <th class="p-3 w-1/3">Product</th>
            <th class="p-3 text-right w-1/6">Total Cost</th>
            <th class="p-3 text-right w-1/6">Total Price</th>
            <th class="p-3 text-right w-1/6">Qty</th>
            <th class="p-3 text-right w-1/6">Action</th>
          </tr>
        </thead>
        <tbody>
          <ProductRow
            v-for="(line, i) in lines"
            :key="i"
            :index="i"
            :line="line"
            :products="products"
            :lines="lines"
            :loading="props.loading"
            @remove="remove"
            @change="onProductChange"
          />
        </tbody>
      </table>
    </div>

    <!-- Buttons -->
    <FormActions
      :canAdd="canAddMoreProducts"
      :loading="props.loading"
      @add="addLine"
      @add-all="addAll"
      @remove-all="removeAll"
    />

    <!-- Financial Inputs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div>
    <label class="text-sm font-semibold text-gray-700">Labor Hours</label>
    <input v-model.number="labor" type="number" class="w-full p-2 border rounded-md text-right" />
  </div>
  <div>
    <label class="text-sm font-semibold text-gray-700">Labor Rate</label>
    <input v-model.number="rate" type="number" class="w-full p-2 border rounded-md text-right" />
  </div>
  <div>
    <label class="text-sm font-semibold text-gray-700">Overhead</label>
    <input v-model.number="overhead" type="number" class="w-full p-2 border rounded-md text-right" />
  </div>
  <div>
    <label class="text-sm font-semibold text-gray-700">Target Margin %</label>
    <input v-model.number="target" type="number" class="w-full p-2 border rounded-md text-right" />
  </div>
</div>


    <!-- Submit -->
    <div class="flex justify-end w-full">
      <button
        type="submit"
        :disabled="props.loading"
        class="flex items-center gap-2 text-white px-4 py-2 rounded"
        :class="props.loading ? 'bg-indigo-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
      >
        <svg
          v-if="props.loading"
          class="animate-spin h-5 w-5 text-white"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
          />
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
          />
        </svg>
        <span>{{ props.loading ? 'Generating Suggestions…' : 'Calculate Profitability' }}</span>
      </button>
    </div>

    <button
      type="button"
      @click="$emit('export')"
      class="flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
    >
      Export as PDF
    </button>
  </form>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import ProductRow from './ProductRow.vue'
import FormActions from './FormActions.vue'
import FinancialInputs from './FinancialInputs.vue'

const emit = defineEmits(['calculated'])
const props = defineProps({ loading: Boolean })

const products = ref([])
const lines = ref([{ product_id: '', cost: 0, price: 0, qty: 1 }])
const labor = ref(0), rate = ref(0), overhead = ref(0), target = ref(0)

const productsLoaded = computed(() => products.value.length > 0)
const canAddMoreProducts = computed(() =>
  productsLoaded.value &&
  products.value.some(p => !lines.value.some(line => line.product_id === p.id))
)

onMounted(async () => {
  try {
    const res = await axios.get('/products')
    products.value = Array.isArray(res.data) ? res.data : res.data.data || []
  } catch (err) {
    console.error('Failed to load products', err)
  }
})

function onProductChange(line) {
  const p = products.value.find(x => x.id === line.product_id)
  if (!p) return
  line.cost = formatCurrency(p.trade_price)
  line.price = formatCurrency(p.retail_price)
  line.qty = p.quantity
}

function addLine() {
  if (lines.value.some(l => !l.product_id)) return
  lines.value.push({ product_id: '', cost: 0, price: 0, qty: 1 })
}

function addAll() {
  lines.value = products.value.map(p => ({
    product_id: p.id,
    cost: formatCurrency(p.trade_price),
    price: formatCurrency(p.retail_price),
    qty: p.quantity
  }))
}

function remove(i) {
  lines.value.splice(i, 1)
  if (lines.value.length === 0) emit('calculated', null)
}

function removeAll() {
  lines.value = [{ product_id: '', cost: 0, price: 0, qty: 1 }]
  emit('calculated', { result: null })
}

function formatCurrency(value) {
  const number = Number(value)
  return isNaN(number) ? '-' : new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2
  }).format(number)
}

function formatCurrencyRemoval(value) {
  if (typeof value === 'number') return value
  const cleaned = String(value).replace(/[^0-9.-]+/g, '')
  return parseFloat(cleaned) || 0
}

async function submit() {
  const validLines = lines.value.filter(l => l.product_id)
  if (validLines.length === 0) return emit('calculated', null)

  const selectedProductNames = validLines.map(l =>
    products.value.find(p => p.id === l.product_id)?.product_name || ''
  )

  const payload = {
    lines: validLines.map(l => ({
      product_id: l.product_id,
      cost: formatCurrencyRemoval(l.cost),
      price: formatCurrencyRemoval(l.price),
      qty: l.qty
    })),
    labor_hours: labor.value,
    labor_rate: rate.value,
    overhead: overhead.value,
    target_margin: target.value
  }

  const adjustment = {
    labor_hours: labor.value,
    labor_rate: rate.value,
    overhead: overhead.value,
    target_margin: target.value
  }

  try {
    const { data } = await axios.post('/quote', payload)
    emit('calculated', {
      result: data.data,
      productNames: selectedProductNames,
      adjustment
    })
  } catch (err) {
    console.error('Quote calculation failed:', err)
  }
}
</script>
