<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Transactions</h1>
            <p class="text-gray-600">Manage organization transactions</p>
          </div>

          <div class="flex items-center space-x-4">
            <button
              @click="showCreateModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
              <i class="fas fa-plus mr-2"></i>
              New Transaction
            </button>
            <router-link
              :href="route('user.dashboard')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
              <i class="fas fa-arrow-left mr-2"></i>
              Back to Dashboard
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Filters -->
      <div class="mb-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Account</label>
            <select
              v-model="filterForm.account_id"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            >
              <option value="">All Accounts</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.name }} ({{ account.type }})
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
            <select
              v-model="filterForm.type"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            >
              <option value="">All Types</option>
              <option value="Debit">Debit</option>
              <option value="Credit">Credit</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
            <select
              v-model="filterForm.currency_id"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            >
              <option value="">All Currencies</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
            <input
              v-model="filterForm.start_date"
              type="date"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            />
          </div>

          <div class="flex items-end">
            <button
              type="submit"
              class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all font-medium flex items-center justify-center gap-2"
            >
              <i class="fas fa-filter"></i> Apply Filters
            </button>
          </div>
        </form>
      </div>

      <!-- Transactions Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">Organization Transactions</h2>
            <div class="text-sm text-gray-500">
              {{ transactions.total }} transactions found
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(transaction.transaction_date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ transaction.account?.name || 'N/A' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ transaction.description }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="transaction.type === 'Credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ transaction.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ formatAmount(transaction.amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ transaction.currency?.code || 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="editTransaction(transaction)"
                    class="text-emerald-600 hover:text-emerald-900 font-medium mr-3"
                  >
                    Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="transactions.data.length === 0" class="text-center py-12">
          <i class="fas fa-receipt text-4xl text-gray-400 mb-4"></i>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
          <p class="text-gray-500">No transactions match your current filters.</p>
        </div>

        <!-- Pagination -->
        <div v-if="transactions.data.length > 0" class="px-6 py-4 border-t border-gray-200">
          <Pagination :links="transactions.links" />
        </div>
      </div>
    </div>

    <!-- Create Transaction Modal -->
    <CreateTransactionModal
      v-if="showCreateModal"
      :accounts="accounts"
      :currencies="currencies"
      @close="showCreateModal = false"
      @created="onTransactionCreated"
    />

    <!-- Edit Transaction Modal -->
    <EditTransactionModal
      v-if="showEditModal"
      :transaction="editingTransaction"
      :accounts="accounts"
      :currencies="currencies"
      @close="showEditModal = false"
      @updated="onTransactionUpdated"
    />
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import Pagination from '../../Shared/Components/Pagination.vue';
import CreateTransactionModal from './Components/CreateTransactionModal.vue';
import EditTransactionModal from './Components/EditTransactionModal.vue';

const props = defineProps({
  transactions: Object,
  accounts: Array,
  currencies: Array,
  filters: Object,
  showCreateModal: Boolean,
  showEditModal: Boolean,
  editingTransaction: Object,
});

const showCreateModal = ref(props.showCreateModal || false);
const showEditModal = ref(props.showEditModal || false);
const editingTransaction = ref(props.editingTransaction || null);

const filterForm = reactive({
  account_id: props.filters?.account_id || '',
  type: props.filters?.type || '',
  currency_id: props.filters?.currency_id || '',
  start_date: props.filters?.start_date || '',
  end_date: props.filters?.end_date || '',
});

const applyFilters = () => {
  const params = {};

  if (filterForm.account_id) {
    params.account_id = filterForm.account_id;
  }

  if (filterForm.type) {
    params.type = filterForm.type;
  }

  if (filterForm.currency_id) {
    params.currency_id = filterForm.currency_id;
  }

  if (filterForm.start_date) {
    params.start_date = filterForm.start_date;
  }

  if (filterForm.end_date) {
    params.end_date = filterForm.end_date;
  }

  router.get(route('user.transactions.index'), params);
};

const editTransaction = (transaction) => {
  editingTransaction.value = transaction;
  showEditModal.value = true;
};

const onTransactionCreated = () => {
  showCreateModal.value = false;
  router.reload();
};

const onTransactionUpdated = () => {
  showEditModal.value = false;
  editingTransaction.value = null;
  router.reload();
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatAmount = (amount) => {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount || 0);
};
</script>
