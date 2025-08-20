<template>
  <div v-if="selectedCurrency" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
    <div class="flex items-center">
      <i class="fas fa-filter text-blue-600 mr-3"></i>
      <div>
        <p class="text-sm font-medium text-blue-800">
          Currency Filter Applied
        </p>
        <p class="text-sm text-blue-600">
          Showing data for {{ selectedCurrency.code }} currency only.
          <button
            @click="$emit('clear-filter')"
            class="text-blue-800 hover:text-blue-900 underline font-medium"
          >
            Clear filter
          </button>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  selectedCurrency: {
    type: Object,
    default: null
  }
});

defineEmits(['clear-filter']);
</script>
