<template>
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
        <span class="hover:text-emerald-600 cursor-pointer transition-colors"><i class="fas fa-home"></i></span>
        <span>/</span>
        <span class="hover:text-emerald-600 cursor-pointer transition-colors">Dashboard</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">Transactions</span>
      </nav>
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-exchange-alt text-emerald-600 mr-3"></i>
            Financial Transactions
          </h1>
          <p class="text-gray-600 mt-2">Track and manage all financial activities across your accounts</p>
        </div>
        <button
          @click="showCreateModal = true"
          class="bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-emerald-700 hover:shadow-xl transition-all font-medium flex items-center gap-2"
        >
          <i class="fas fa-plus"></i> New Transaction
        </button>
      </div>
    </div>

    <!-- Account Selector -->
    <AccountSelector
      :accounts="accounts"
      :currencies="currencies"
      :selected-account-id="filters.account_id"
      @account-changed="onAccountChanged"
    />

    <!-- Filters -->
    <div class="mb-8 bg-white rounded-xl shadow-lg border border-gray-100 p-6">
      <div class="flex items-center mb-4">
        <i class="fas fa-filter text-emerald-600 mr-2"></i>
        <h3 class="text-lg font-semibold text-gray-800">Filter Transactions</h3>
      </div>
      <form @submit.prevent="applyFilter" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
          <select
            v-model="filterForm.type"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
          >
            <option value="">All Types</option>
            <option value="Credit">Credit</option>
            <option value="Debit">Debit</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Account</label>
          <select
            v-model="filterForm.account_id"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
          >
            <option value="">All Accounts</option>
            <option v-for="account in accounts" :key="account.id" :value="account.id">
              {{ account.name }}
            </option>
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
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <template v-if="transactions.data.length > 0">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase">
            <tr>
              <th class="px-4 py-2">Date</th>
              <th class="px-4 py-2">Account</th>
              <th class="px-4 py-2">Type</th>
              <th class="px-4 py-2">Amount</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
              <td class="px-4 py-3">{{ transaction.transaction_date }}</td>
              <td class="px-4 py-3">{{ transaction.account.name }}</td>
              <td class="px-4 py-3 capitalize">{{ transaction.type }}</td>
              <td class="px-4 py-3">{{ transaction.amount }} {{ transaction.currency.code }}</td>
              <td class="px-4 py-3">
                <div class="flex gap-2">
                  <button
                    @click="openEditModal(transaction)"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md transition-colors"
                  >
                    <i class="fas fa-edit mr-1"></i> Edit
                  </button>
                  <button
                    @click="openDeleteModal(transaction)"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-md transition-colors"
                  >
                    <i class="fas fa-trash-alt mr-1"></i> Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </template>
      <template v-else>
        <div class="flex flex-col items-center justify-center py-20 text-center text-gray-600">
          <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
          <p class="text-lg font-semibold">No transactions yet</p>
          <p class="text-sm text-gray-500 mb-6">Start by adding your first transaction.</p>
          <button
            @click="openCreateModal"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded shadow-md flex items-center gap-2"
          >
            <i class="fas fa-plus-circle"></i> Create Transaction
          </button>
        </div>
      </template>
    </div>

    <!-- Modals -->
    <CreateTransactionModal
      :show="showCreateModal"
      :accounts="accounts"
      :currencies="currencies"
      :errors="$page.props.errors"
      @close="showCreateModal = false"
    />

    <EditTransactionModal
      :show="showEditModal"
      :transaction="editingTransaction"
      :accounts="accounts"
      :currencies="currencies"
      :errors="$page.props.errors"
      @close="showEditModal = false"
    />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      title="Delete Transaction"
      message="Are you sure you want to delete this transaction? This action cannot be undone."
      :details="transactionToDelete ? {
        'Account': transactionToDelete.account?.name,
        'Type': transactionToDelete.type,
        'Amount': `${transactionToDelete.amount} ${transactionToDelete.currency?.code}`,
        'Date': transactionToDelete.transaction_date
      } : null"
      :delete-url="transactionToDelete ? `/transactions/${transactionToDelete.id}` : ''"
      @close="showDeleteModal = false"
    />

    <vue3-snackbar top right :duration="4000"></vue3-snackbar>
  </div>
</template>
<script>
import { router } from "@inertiajs/vue3";
import Layout from "../../Shared/Layout.vue";
import CreateTransactionModal from './Components/CreateTransactionModal.vue';
import EditTransactionModal from './Components/EditTransactionModal.vue';
import DeleteConfirmationModal from './Components/DeleteConfirmationModal.vue';
import AccountSelector from './Components/AccountSelector.vue';

export default {
  layout: Layout,
  components: {
    CreateTransactionModal,
    EditTransactionModal,
    DeleteConfirmationModal,
    AccountSelector,
  },
  props: {
    transactions: Object,
    accounts: Array,
    currencies: Array,
    filters: Object,
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      editingTransaction: null,
      filterForm: {
        type: this.filters?.type || "",
        account_id: this.filters?.account_id || "",
        currency_id: this.filters?.currency_id || "",
      },
      showDeleteModal: false,
      transactionToDelete: null,
    };
  },
  methods: {
    openEditModal(transaction) {
      this.editingTransaction = transaction;
      this.showEditModal = true;
    },
    openDeleteModal(transaction) {
      this.transactionToDelete = transaction;
      this.showDeleteModal = true;
    },
    onAccountChanged(accountId) {
      this.filterForm.account_id = accountId;
      this.applyFilter();
    },
    applyFilter() {
      router.get("/transactions", this.filterForm, {
        preserveScroll: true,
        preserveState: true,
      });
    },
  },
};


</script>
