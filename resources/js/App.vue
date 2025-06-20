<template>
  <div class="w-full min-h-screen bg-yellow-100 p-6">
    <div
      ref="exportSection"
      class="w-full max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow space-y-8"
    >
      <h1 class="text-2xl font-bold text-gray-800">Specifi Profit Optimizer</h1>

      <QuoteForm
        :loading="loading"
        @calculated="onCalculated"
        @export="exportPDF"
      />

      <QuoteSummary
        v-if="hasResult"
        ref="summaryRef"
        :result="result"
        :productNames="productNames"
        :adjustment="adjustment"
        :loading="loading"
        @update:loading="loading = $event"
      />
    </div>
  </div>
</template>
<style>
@media print {
  .no-print { display: none !important; }
  html, body {
    margin: 0 !important;
    padding: 0 !important;
  }
}
</style>
<script>
import QuoteForm from './components/QuoteForm/QuoteForm.vue'
import QuoteSummary from './components/QuoteSummary/QuoteSummary.vue'
import html2canvas from 'html2canvas'
import jsPDF from 'jspdf'

export default {
  components: { QuoteForm, QuoteSummary },
  data() {
    return {
      result: null,
      loading: false,
      productNames: [],
      adjustment: []
    }
  },
  computed: {
    hasResult() {
      return this.result?.lines?.length > 0
    }
  },
  methods: {
    onCalculated(data) {
  if (!data || typeof data !== 'object') return

  const { result, productNames, adjustment } = data

  if (!result) return

  this.result = result
  this.productNames = productNames || []
  this.adjustment = adjustment || []

  this.$nextTick(() => {
    this.$refs.summaryRef?.getAI?.()
  })
},

    async exportPDF() {
      const section = this.$refs.exportSection
      if (!section) return

      const hiddenEls = [...section.querySelectorAll('.no-print')]
      hiddenEls.forEach(el => (el.style.display = 'none'))

      try {
        const canvas = await html2canvas(section, {
          scale: 2,
          useCORS: true,
          scrollY: -window.scrollY,
          backgroundColor: '#fff'
        })

        this.generatePDF(canvas)
      } catch (err) {
        console.error('PDF export failed:', err)
      } finally {
        hiddenEls.forEach(el => (el.style.display = ''))
      }
    },

    generatePDF(canvas) {
  const pdf = new jsPDF('p', 'mm', 'a4')
  const pageWidth = pdf.internal.pageSize.getWidth()
  const pageHeight = pdf.internal.pageSize.getHeight()

  const imgData = canvas.toDataURL('image/png')
  const imgProps = pdf.getImageProperties(imgData)

  const imgWidth = pageWidth
  const imgHeight = (imgProps.height * imgWidth) / imgProps.width

  let heightLeft = imgHeight
  let position = 0

  pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight)
  heightLeft -= pageHeight

  while (heightLeft > 0) {
    position = heightLeft - imgHeight
    pdf.addPage()
    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight)
    heightLeft -= pageHeight
  }

  pdf.save('quote-summary.pdf')
}

  }
}
</script>
