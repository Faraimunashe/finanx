<template>
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
        <span class="hover:text-emerald-600 cursor-pointer" @click="$inertia.get('/accounts')">
          <i class="fas fa-home"></i>
        </span>
        <span>/</span>
        <span class="hover:text-emerald-600 cursor-pointer" @click="$inertia.get('/accounts')">Accounts</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">{{ account.name }}</span>
      </nav>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">{{ account.name }}</h1>
        <div class="flex gap-3">
          <button
            @click="showEditModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-colors font-medium flex items-center gap-2"
          >
            <i class="fas fa-edit"></i> Edit Account
          </button>
          <button
            @click="showCreateTransactionModal = true"
            class="bg-emerald-600 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-700 transition-colors font-medium flex items-center gap-2"
          >
            <i class="fas fa-plus"></i> Add Transaction
          </button>
        </div>
      </div>
    </div>

    <!-- Account Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-layer-group text-blue-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Account Type</p>
            <p class="text-lg font-semibold text-gray-800">{{ account.type }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-chart-line text-green-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Transactions</p>
            <p class="text-lg font-semibold text-gray-800">{{ account.transactions_count || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-arrow-up text-emerald-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Credits</p>
            <p class="text-lg font-semibold text-gray-800">{{ totalCredits }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-arrow-down text-red-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Debits</p>
            <p class="text-lg font-semibold text-gray-800">{{ totalDebits }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-md">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Recent Transactions</h2>
      </div>

      <div class="overflow-x-auto">
        <template v-if="transactions.data && transactions.data.length > 0">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(transaction.transaction_date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      transaction.type === 'Credit'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800'
                    ]"
                  >
                    {{ transaction.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ formatCurrency(transaction.amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ transaction.currency.code }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ transaction.description || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex gap-2">
                    <button
                      @click="openEditModal(transaction)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="openDeleteModal(transaction)"
                      class="text-red-600 hover:text-red-900"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </template>
        <template v-else>
          <div class="text-center py-12">
            <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
            <p class="text-lg font-medium text-gray-600">No transactions yet</p>
            <p class="text-sm text-gray-500 mb-4">Start by adding your first transaction to this account.</p>
            <button
              @click="showCreateTransactionModal = true"
              class="bg-emerald-600 text-white px-6 py-2 rounded-lg shadow hover:bg-emerald-700 transition-colors font-medium"
            >
              <i class="fas fa-plus mr-2"></i>Add Transaction
            </button>
          </div>
        </template>
      </div>

      <!-- Pagination -->
      <div v-if="transactions.data && transactions.data.length > 0" class="px-6 py-4 border-t border-gray-200">
        <Pagination :links="transactions.links" />
      </div>
    </div>

    <!-- Modals -->
    <EditAccountModal
      :show="showEditModal"
      :account="account"
      :errors="$page.props.errors"
      @close="showEditModal = false"
    />

    <CreateTransactionModal
      :show="showCreateTransactionModal"
      :accounts="[account]"
      :currencies="currencies"
      :errors="$page.props.errors"
      @close="showCreateTransactionModal = false"
    />

    <EditTransactionModal
      :show="showEditTransactionModal"
      :transaction="editingTransaction"
      :accounts="[account]"
      :currencies="currencies"
      :errors="$page.props.errors"
      @close="showEditTransactionModal = false"
    />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      title="Delete Transaction"
      message="Are you sure you want to delete this transaction? This action cannot be undone."
      :details="transactionToDelete ? {
        'Date': formatDate(transactionToDelete.transaction_date),
        'Type': transactionToDelete.type,
        'Amount': formatCurrency(transactionToDelete.amount) + ' ' + transactionToDelete.currency?.code
      } : null"
      :delete-url="transactionToDelete ? `/transactions/${transactionToDelete.id}` : ''"
      @close="showDeleteModal = false"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Layout from '../../Shared/Layout.vue';
import Pagination from '../../Shared/Components/Pagination.vue';
import EditAccountModal from './Components/EditAccountModal.vue';
import CreateTransactionModal from './Components/CreateTransactionModal.vue';
import EditTransactionModal from './Components/EditTransactionModal.vue';
import DeleteConfirmationModal from './Components/DeleteConfirmationModal.vue';

defineOptions({
  layout: Layout
});

const props = defineProps({
  account: Object,
  transactions: Object,
  currencies: Array
});

// Modal states
const showEditModal = ref(false);
const showCreateTransactionModal = ref(false);
const showEditTransactionModal = ref(false);
const showDeleteModal = ref(false);
const editingTransaction = ref(null);
const transactionToDelete = ref(null);

// Computed properties
const totalCredits = computed(() => {
  if (!props.transactions.data) return '0.00';
  return props.transactions.data
    .filter(t => t.type === 'Credit')
    .reduce((sum, t) => sum + parseFloat(t.amount), 0)
    .toFixed(2);
});

const totalDebits = computed(() => {
  if (!props.transactions.data) return '0.00';
  return props.transactions.data
    .filter(t => t.type === 'Debit')
    .reduce((sum, t) => sum + parseFloat(t.amount), 0)
    .toFixed(2);
});

// Methods
const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const formatCurrency = (amount) => {
  return parseFloat(amount).toFixed(2);
};

const openEditModal = (transaction) => {
  editingTransaction.value = transaction;
  showEditTransactionModal.value = true;
};

const openDeleteModal = (transaction) => {
  transactionToDelete.value = transaction;
  showDeleteModal.value = true;
};
</script>
