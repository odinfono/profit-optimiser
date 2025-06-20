<template>
  <div class="p-6 max-w-7xl mx-auto border rounded-xl space-y-6">
    <div class="p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <InfoItem label="Labor Hours" :value="adjustment.labor_hours" />
      <InfoItem label="Labor Rate" :value="formatCurrency(adjustment.labor_rate)" />
      <InfoItem label="Overhead" :value="formatCurrency(adjustment.overhead)" />
      <InfoItem label="Target Margin" :value="adjustment.target_margin + '%'" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="overflow-x-auto">
        <ProductTable :lines="result.lines" :productNames="productNames" />
        <SummaryTable :result="result" :formatCurrency="formatCurrency" />
      </div>

      <div>
        <h2 class="text-lg font-bold text-gray-800 mb-2">AI Suggestions</h2>
        <div v-if="loading" class="text-gray-500 text-sm italic">Generating suggestions...</div>
        <div
          v-else
          ref="editor"
          contenteditable
          class="text-sm w-full outline-none bg-gray-50 border rounded-md font-mono p-2"
          style="min-height: 3rem; overflow: hidden"
          @input="onInput"
        ></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'
import axios from 'axios'
import InfoItem from './InfoItem.vue'
import ProductTable from './ProductTable.vue'
import SummaryTable from './SummaryTable.vue'

const props = defineProps({
  result: Object,
  productNames: Array,
  adjustment: Object,
  loading: Boolean
})

const emit = defineEmits(['update:loading'])
const ai = ref('')
const rawText = ref('')
const editor = ref(null)

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

const getAI = async () => {
  emit('update:loading', true)
  try {
    const res = await axios.post('/suggestions', { quote: props.result })
    ai.value = res.data.suggestions
    rawText.value = ai.value
  } catch (err) {
    console.error('AI suggestion error:', err)
  } finally {
    emit('update:loading', false)
    await nextTick()
    renderFormatted()
  }
}

const onInput = () => {
  rawText.value = editor.value?.innerText || ''
  renderFormatted()
}

const renderFormatted = () => {
  if (!editor.value) return
  const caretOffset = getCaretOffset(editor.value)
  const html = rawText.value
    .replace(/\s*###\s.+$/gm, '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/\*\*(.+?)\*\*/g, '$1')
    .replace(/\n/g, '<br>')

  editor.value.innerHTML = html
  setCaretOffset(editor.value, caretOffset)
}

const getCaretOffset = (el) => {
  const sel = window.getSelection()
  if (!sel?.rangeCount) return 0
  const range = sel.getRangeAt(0)
  const preCaretRange = range.cloneRange()
  preCaretRange.selectNodeContents(el)
  preCaretRange.setEnd(range.endContainer, range.endOffset)
  return preCaretRange.toString().length
}

const setCaretOffset = (el, offset) => {
  const range = document.createRange()
  const sel = window.getSelection()
  let charCount = 0, nodeStack = [el]

  while (nodeStack.length) {
    const node = nodeStack.pop()
    if (node.nodeType === 3) {
      const nextCount = charCount + node.length
      if (offset <= nextCount) {
        range.setStart(node, offset - charCount)
        range.collapse(true)
        break
      }
      charCount = nextCount
    } else {
      for (let i = node.childNodes.length - 1; i >= 0; i--) {
        nodeStack.push(node.childNodes[i])
      }
    }
  }

  sel.removeAllRanges()
  sel.addRange(range)
}

defineExpose({ getAI })
</script>