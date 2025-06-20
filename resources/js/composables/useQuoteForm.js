import { ref } from 'vue'
import { calculateProfitability } from '@/services/quoteService'

export function useQuoteForm() {
  const lines = ref([{ product: '', cost: 0, price: 0, qty: 1 }])
  const loading = ref(false)
  const result = ref(null)

  const addLine = () => {
    lines.value.push({ product: '', cost: 0, price: 0, qty: 1 })
  }

  const removeLine = (index) => {
    lines.value.splice(index, 1)
  }

  const submit = async () => {
    loading.value = true
    try {
      const payload = {
        lines: lines.value,
        labor_hours: 1,
        labor_rate: 50,
        overhead: 20,
        target_margin: 25,
      }
      const res = await calculateProfitability(payload)
      result.value = res.data
    } finally {
      loading.value = false
    }
  }

  return { lines, loading, result, addLine, removeLine, submit }
}
