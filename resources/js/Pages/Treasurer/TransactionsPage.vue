<template>
  <div>
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Transactions</h1>
      <button
        @click="openCreateModal"
        class="bg-emerald-600 text-white px-4 py-2 rounded shadow hover:bg-emerald-700"
      >
        <i class="fas fa-plus mr-2"></i> New Transaction
      </button>
    </div>

    <!-- Filters -->
    <form @submit.prevent="applyFilter" class="bg-white p-6 rounded-lg shadow-md mb-8 space-y-4 md:space-y-0 md:flex md:items-end md:gap-6">
      <div class="w-full md:w-1/4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
        <select
          v-model="filterForm.type"
          class="pl-3 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
        >
          <option value="">All</option>
          <option value="revenue">Revenue</option>
          <option value="expense">Expense</option>
        </select>
      </div>
      <div class="w-full md:w-1/4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Account</label>
        <select
          v-model="filterForm.account_id"
          class="pl-3 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
        >
          <option value="">All</option>
          <option v-for="account in accounts" :key="account.id" :value="account.id">
            {{ account.name }}
          </option>
        </select>
      </div>
      <div class="w-full md:w-1/4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
        <select
          v-model="filterForm.currency_id"
          class="pl-3 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
        >
          <option value="">All</option>
          <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
            {{ currency.code }}
          </option>
        </select>
      </div>
      <div class="w-full md:w-auto mt-2 md:mt-0">
        <button
          type="submit"
          class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-md shadow font-medium flex items-center justify-center gap-2"
        >
          <i class="fas fa-filter"></i> Filter
        </button>
      </div>
    </form>

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
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md transition"
                  >
                    <i class="fas fa-edit mr-1"></i> Edit
                  </button>
                  <button
                    @click="openDeleteModal(transaction)"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-md transition"
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

    <!-- Slide-in Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex justify-end bg-black bg-opacity-30"
      @click.self="closeModal"
    >
      <div class="bg-white w-full sm:w-3/5 md:w-2/5 lg:w-1/3 h-full shadow-xl p-6 overflow-y-auto transition-all duration-300 ease-in-out">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
          {{ modalMode === 'create' ? 'Create Transaction' : 'Edit Transaction' }}
        </h2>
        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account</label>
            <select
              v-model="form.account_id"
              class="pl-3 pr-4 py-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
              required
            >
              <option disabled value="">Select account</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
            <select
              v-model="form.currency_id"
              class="pl-3 pr-4 py-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
              required
            >
              <option disabled value="">Select currency</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select
              v-model="form.type"
              class="pl-3 pr-4 py-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
              required
            >
              <option disabled value="">Select type</option>
              <option value="Credit">Credit</option>
              <option value="Debit">Debit</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
            <input
              type="number"
              v-model="form.amount"
              step="0.01"
              min="0"
              class="pl-3 pr-4 py-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="form.description"
              class="pl-3 pr-4 py-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
              placeholder="Optional"
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input
              type="date"
              v-model="form.transaction_date"
              class="pl-3 pr-4 py-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
              required
            />
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 text-gray-700 hover:text-gray-900 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-emerald-600 text-white rounded-md shadow hover:bg-emerald-700"
            >
              {{ modalMode === 'create' ? 'Create Transaction' : 'Update Transaction' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
      <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
          <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
          Confirm Deletion
        </h3>
        <p class="text-gray-600 mb-6">
          Are you sure you want to delete this transaction?
        </p>
        <div class="flex justify-end gap-3">
          <button
            @click="closeDeleteModal"
            class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            class="px-4 py-2 bg-rose-600 text-white rounded shadow hover:bg-rose-700"
          >
            Yes, Delete
          </button>
        </div>
      </div>
    </div>

    <vue3-snackbar top right :duration="4000"></vue3-snackbar>
  </div>
</template>
<script>
import { router } from "@inertiajs/vue3";
import Layout from "../../Shared/Layout.vue";

export default {
  layout: Layout,
  props: {
    transactions: Object,
    accounts: Array,
    currencies: Array,
    filters: Object,
  },
  data() {
    return {
      showModal: false,
      modalMode: "create",
      editingId: null,
      form: {
        account_id: "",
        currency_id: "",
        type: "",
        amount: "",
        description: "",
        transaction_date: "",
      },
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
    openCreateModal() {
      this.modalMode = "create";
      this.form = {
        account_id: "",
        currency_id: "",
        type: "",
        amount: "",
        description: "",
        transaction_date: "",
      };
      this.editingId = null;
      this.showModal = true;
    },
    openEditModal(transaction) {
      this.modalMode = "edit";
      this.form = {
        account_id: transaction.account_id,
        currency_id: transaction.currency_id,
        type: transaction.type,
        amount: transaction.amount,
        description: transaction.description || "",
        transaction_date: transaction.transaction_date,
      };
      this.editingId = transaction.id;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
    submit() {
      if (this.modalMode === "create") {
        router.post("/transactions", this.form, {
          onSuccess: () => {
            this.closeModal();
            this.$snackbar.add({
              type: "success",
              text: "Transaction created successfully!",
            });
          },
          onError: () => {
            this.$snackbar.add({
              type: "error",
              text: "Failed to create transaction. Check your inputs.",
            });
          },
        });
      } else {
        router.put(`/transactions/${this.editingId}`, this.form, {
          onSuccess: () => {
            this.closeModal();
            this.$snackbar.add({
              type: "success",
              text: "Transaction updated successfully!",
            });
          },
          onError: () => {
            this.$snackbar.add({
              type: "error",
              text: "Failed to update transaction. Check your inputs.",
            });
          },
        });
      }
    },
    openDeleteModal(transaction) {
      this.transactionToDelete = transaction;
      this.showDeleteModal = true;
    },
    confirmDelete() {
      if (!this.transactionToDelete) return;

      router.delete(`/transactions/${this.transactionToDelete.id}`, {
        onSuccess: () => {
          this.showDeleteModal = false;
          this.$snackbar.add({
            type: "success",
            text: "Transaction deleted successfully!",
          });
        },
        onError: () => {
          this.$snackbar.add({
            type: "error",
            text: "Failed to delete transaction.",
          });
        },
      });
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.transactionToDelete = null;
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
