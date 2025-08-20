<template>
  <div v-if="show && organization" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h3 class="text-2xl font-bold text-gray-800">Edit Organization</h3>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="p-6 space-y-6">
        <!-- Organization Name -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Organization Name <span class="text-red-500">*</span>
          </label>
          <input
            type="text"
            v-model="form.name"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            :class="{ 'border-red-500': errors.name }"
            placeholder="Enter organization name"
            required
          />
          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Email Address
          </label>
          <input
            type="email"
            v-model="form.email"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            :class="{ 'border-red-500': errors.email }"
            placeholder="Enter email address"
          />
          <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Phone Number
          </label>
          <input
            type="tel"
            v-model="form.phone"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            :class="{ 'border-red-500': errors.phone }"
            placeholder="Enter phone number"
          />
          <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</p>
        </div>

        <!-- Address -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Address
          </label>
          <textarea
            v-model="form.address"
            rows="3"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none"
            :class="{ 'border-red-500': errors.address }"
            placeholder="Enter organization address..."
          ></textarea>
          <p v-if="errors.address" class="text-red-500 text-sm mt-1">{{ errors.address }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
          <button
            type="button"
            @click="$emit('close')"
            class="px-6 py-3 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium flex items-center gap-2"
          >
            <svg v-if="form.processing" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            {{ form.processing ? 'Updating...' : 'Update Organization' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  organization: {
    type: Object,
    default: null
  },
  errors: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['close']);

const form = useForm({
  name: '',
  email: '',
  phone: '',
  address: ''
});

// Watch for organization changes and populate form
watch(() => props.organization, (newOrganization) => {
  if (newOrganization) {
    form.name = newOrganization.name;
    form.email = newOrganization.email || '';
    form.phone = newOrganization.phone || '';
    form.address = newOrganization.address || '';
  }
}, { immediate: true });

const submit = () => {
  form.put(`/organizations/${props.organization.id}`, {
    onSuccess: () => {
      emit('close');
    }
  });
};
</script>
