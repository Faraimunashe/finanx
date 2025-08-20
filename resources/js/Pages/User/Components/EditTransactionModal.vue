<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Edit Transaction</h3>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <!-- Account -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Account</label>
              <select
                v-model="form.account_id"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              >
                <option value="">Select Account</option>
                <option v-for="account in accounts" :key="account.id" :value="account.id">
                  {{ account.name }} ({{ account.type }})
                </option>
              </select>
              <div v-if="form.errors.account_id" class="text-red-500 text-sm mt-1">
                {{ form.errors.account_id }}
              </div>
            </div>

            <!-- Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
              <select
                v-model="form.type"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              >
                <option value="">Select Type</option>
                <option value="Debit">Debit</option>
                <option value="Credit">Credit</option>
              </select>
              <div v-if="form.errors.type" class="text-red-500 text-sm mt-1">
                {{ form.errors.type }}
              </div>
            </div>

            <!-- Amount -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
              <input
                v-model="form.amount"
                type="number"
                step="0.01"
                min="0.01"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="0.00"
              />
              <div v-if="form.errors.amount" class="text-red-500 text-sm mt-1">
                {{ form.errors.amount }}
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <input
                v-model="form.description"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Transaction description"
              />
              <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                {{ form.errors.description }}
              </div>
            </div>

            <!-- Transaction Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Date</label>
              <input
                v-model="form.transaction_date"
                type="date"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              />
              <div v-if="form.errors.transaction_date" class="text-red-500 text-sm mt-1">
                {{ form.errors.transaction_date }}
              </div>
            </div>

            <!-- Currency -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
              <select
                v-model="form.currency_id"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              >
                <option value="">Select Currency</option>
                <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                  {{ currency.code }}
                </option>
              </select>
              <div v-if="form.errors.currency_id" class="text-red-500 text-sm mt-1">
                {{ form.errors.currency_id }}
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex items-center justify-end space-x-3 mt-6">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50"
            >
              <span v-if="form.processing">Updating...</span>
              <span v-else>Update Transaction</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  transaction: Object,
  accounts: Array,
  currencies: Array,
});

const emit = defineEmits(['close', 'updated']);

const form = useForm({
  account_id: props.transaction?.account_id || '',
  type: props.transaction?.type || '',
  amount: props.transaction?.amount || '',
  description: props.transaction?.description || '',
  transaction_date: props.transaction?.transaction_date ? new Date(props.transaction.transaction_date).toISOString().split('T')[0] : new Date().toISOString().split('T')[0],
  currency_id: props.transaction?.currency_id || '',
});

const submitForm = () => {
  form.put(route('user.transactions.update', props.transaction.id), {
    onSuccess: () => {
      emit('updated');
    },
  });
};
</script>
