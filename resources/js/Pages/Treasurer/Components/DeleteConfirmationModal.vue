<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800">Confirm Deletion</h3>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Content -->
      <div class="p-6">
        <div class="flex items-center mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
          </div>
          <div>
            <h4 class="text-lg font-semibold text-gray-800">{{ title }}</h4>
            <p class="text-gray-600">{{ message }}</p>
          </div>
        </div>

        <div v-if="details" class="bg-gray-50 rounded-lg p-4 mb-6">
          <h5 class="font-medium text-gray-800 mb-2">Item Details:</h5>
          <div class="text-sm text-gray-600 space-y-1">
            <div v-for="(value, key) in details" :key="key" class="flex justify-between">
              <span class="font-medium capitalize">{{ key.replace('_', ' ') }}:</span>
              <span>{{ value }}</span>
            </div>
          </div>
        </div>

        <div v-if="warning" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
          <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-yellow-600 mr-2"></i>
            <span class="text-yellow-800 text-sm font-medium">{{ warning }}</span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
          <button
            @click="$emit('close')"
            class="px-6 py-3 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            :disabled="processing"
            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium flex items-center gap-2"
          >
            <svg v-if="processing" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            {{ processing ? 'Deleting...' : 'Delete' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Are you sure?'
  },
  message: {
    type: String,
    default: 'This action cannot be undone.'
  },
  details: {
    type: Object,
    default: null
  },
  warning: {
    type: String,
    default: ''
  },
  deleteUrl: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['close']);

const form = useForm({});

const processing = form.processing;

const confirmDelete = () => {
  form.delete(props.deleteUrl, {
    onSuccess: () => {
      emit('close');
    }
  });
};
</script>
