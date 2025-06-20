<template>
  <div>
          <label class="text-sm font-bold text-gray-700">{{ label }}</label>
                    <label class="w-full p-2 text-right">{{ value }}</label>
  </div>
</template>
<script setup>
defineProps({ label: String, value: [String, Number] })
</script>