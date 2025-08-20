<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h3 class="text-2xl font-bold text-gray-800">Create New Transaction</h3>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="p-6 space-y-6">
        <!-- Account Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Account <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.account_id"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            :class="{ 'border-red-500': errors.account_id }"
            required
          >
            <option value="">Select an account</option>
            <option v-for="account in accounts" :key="account.id" :value="account.id">
              {{ account.name }} ({{ account.type }})
            </option>
          </select>
          <p v-if="errors.account_id" class="text-red-500 text-sm mt-1">{{ errors.account_id }}</p>
        </div>

        <!-- Transaction Type -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Transaction Type <span class="text-red-500">*</span>
          </label>
          <div class="grid grid-cols-2 gap-4">
            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all">
              <input
                type="radio"
                v-model="form.type"
                value="Credit"
                class="mr-3 text-emerald-600 focus:ring-emerald-500"
                required
              />
              <div>
                <div class="font-medium text-gray-800">Credit (Income)</div>
                <div class="text-sm text-gray-500">Money coming in</div>
              </div>
            </label>
            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all">
              <input
                type="radio"
                v-model="form.type"
                value="Debit"
                class="mr-3 text-emerald-600 focus:ring-emerald-500"
                required
              />
              <div>
                <div class="font-medium text-gray-800">Debit (Expense)</div>
                <div class="text-sm text-gray-500">Money going out</div>
              </div>
            </label>
          </div>
          <p v-if="errors.type" class="text-red-500 text-sm mt-1">{{ errors.type }}</p>
        </div>

        <!-- Amount and Currency -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Amount <span class="text-red-500">*</span>
            </label>
            <input
              type="number"
              v-model="form.amount"
              step="0.01"
              min="0.01"
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
              :class="{ 'border-red-500': errors.amount }"
              placeholder="0.00"
              required
            />
            <p v-if="errors.amount" class="text-red-500 text-sm mt-1">{{ errors.amount }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Currency <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.currency_id"
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
              :class="{ 'border-red-500': errors.currency_id }"
              required
            >
              <option value="">Select currency</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }} - {{ currency.country }}
              </option>
            </select>
            <p v-if="errors.currency_id" class="text-red-500 text-sm mt-1">{{ errors.currency_id }}</p>
          </div>
        </div>

        <!-- Transaction Date -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Transaction Date <span class="text-red-500">*</span>
          </label>
          <input
            type="date"
            v-model="form.transaction_date"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            :class="{ 'border-red-500': errors.transaction_date }"
            required
          />
          <p v-if="errors.transaction_date" class="text-red-500 text-sm mt-1">{{ errors.transaction_date }}</p>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Description
          </label>
          <textarea
            v-model="form.description"
            rows="3"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none"
            :class="{ 'border-red-500': errors.description }"
            placeholder="Enter transaction description..."
          ></textarea>
          <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
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
            {{ form.processing ? 'Creating...' : 'Create Transaction' }}
          </button>
        </div>
      </form>
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
  accounts: {
    type: Array,
    default: () => []
  },
  currencies: {
    type: Array,
    default: () => []
  },
  errors: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['close']);

const form = useForm({
  account_id: '',
  currency_id: '',
  type: '',
  amount: '',
  description: '',
  transaction_date: new Date().toISOString().split('T')[0]
});

const submit = () => {
  form.post('/transactions', {
    onSuccess: () => {
      form.reset();
      emit('close');
    }
  });
};
</script>
