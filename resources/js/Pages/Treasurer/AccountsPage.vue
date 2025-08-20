<template>
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
        <span class="hover:text-emerald-600 cursor-pointer transition-colors"><i class="fas fa-home"></i></span>
        <span>/</span>
        <span class="hover:text-emerald-600 cursor-pointer transition-colors">Dashboard</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">Accounts</span>
      </nav>
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-layer-group text-emerald-600 mr-3"></i>
            Chart of Accounts
          </h1>
          <p class="text-gray-600 mt-2">Manage your organization's financial accounts and categories</p>
        </div>
        <button
          @click="showCreateModal = true"
          class="bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-emerald-700 hover:shadow-xl transition-all font-medium flex items-center gap-2"
        >
          <i class="fas fa-plus"></i> New Account
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="mb-8 bg-white rounded-xl shadow-lg border border-gray-100 p-6">
      <div class="flex items-center mb-4">
        <i class="fas fa-filter text-emerald-600 mr-2"></i>
        <h3 class="text-lg font-semibold text-gray-800">Filter Accounts</h3>
      </div>
      <form @submit.prevent="applyFilter" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Account Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
              <i class="fas fa-search"></i>
            </span>
            <input
              type="text"
              v-model="filterForm.name"
              class="pl-10 pr-4 py-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
              placeholder="Search account names..."
            />
          </div>
        </div>

        <!-- Account Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
              <i class="fas fa-layer-group"></i>
            </span>
            <select
              v-model="filterForm.type"
              class="pl-10 pr-4 py-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            >
              <option value="">All Account Types</option>
              <option value="Asset">Assets</option>
              <option value="Liability">Liabilities</option>
              <option value="Revenue">Revenue</option>
              <option value="Expense">Expenses</option>
            </select>
          </div>
        </div>

        <!-- Filter Button -->
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


    <!-- Accounts Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-6">
        <h2 class="text-xl font-semibold flex items-center">
          <i class="fas fa-layer-group mr-3"></i> Chart of Accounts
        </h2>
        <p class="text-emerald-100 text-sm mt-1">Manage your organization's financial structure</p>
      </div>

      <div class="overflow-x-auto">
        <template v-if="accounts.length > 0">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-for="account in accounts" :key="account.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" :class="getTypeBgColor(account.type)">
                      <i :class="[getTypeIcon(account.type), getTypeTextColor(account.type)]"></i>
                    </div>
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ account.name }}</div>
                      <div class="text-sm text-gray-500">Account #{{ account.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex px-3 py-1 text-xs font-semibold rounded-full',
                      getTypeColor(account.type)
                    ]"
                  >
                    {{ account.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div class="flex items-center">
                    <i class="fas fa-chart-line text-gray-400 mr-2"></i>
                    {{ account.transactions_count || 0 }} transactions
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex gap-2">
                    <button
                      @click="viewAccountDetails(account)"
                      class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition-colors"
                    >
                      <i class="fas fa-eye mr-1"></i> View
                    </button>
                    <button
                      @click="openEditModal(account)"
                      class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors"
                    >
                      <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button
                      @click="openDeleteModal(account)"
                      class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors"
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
            <p class="text-lg font-semibold">No accounts yet</p>
            <p class="text-sm text-gray-500 mb-6">Start by creating your first financial account for this organisation.</p>
            <button
              @click="showCreateModal = true"
              class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded shadow-md flex items-center gap-2"
            >
              <i class="fas fa-plus-circle"></i> Create Account
            </button>
          </div>
        </template>
      </div>
    </div>




    <!-- Modals -->
    <CreateAccountModal
      :show="showCreateModal"
      :errors="$page.props.errors"
      @close="showCreateModal = false"
    />

    <EditAccountModal
      :show="showEditModal"
      :account="editingAccount"
      :errors="$page.props.errors"
      @close="showEditModal = false"
    />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      title="Delete Account"
      message="Are you sure you want to delete this account? This action cannot be undone."
      :details="accountToDelete ? {
        'Name': accountToDelete.name,
        'Type': accountToDelete.type
      } : null"
      :warning="accountToDelete && accountToDelete.transactions_count > 0 ? 'This account has transactions and cannot be deleted.' : ''"
      :delete-url="accountToDelete ? `/accounts/${accountToDelete.id}` : ''"
      @close="showDeleteModal = false"
    />

  </div>
</template>

<script>
import { router } from "@inertiajs/vue3";
import Layout from '../../Shared/Layout.vue';
import CreateAccountModal from './Components/CreateAccountModal.vue';
import EditAccountModal from './Components/EditAccountModal.vue';
import DeleteConfirmationModal from './Components/DeleteConfirmationModal.vue';

export default {
  layout: Layout,
  components: {
    CreateAccountModal,
    EditAccountModal,
    DeleteConfirmationModal,
  },
  props: {
    accounts: Array,
    filters: Object,
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      editingAccount: null,
      filterForm: {
        name: this.filters?.name || "",
        type: this.filters?.type || "",
      },
      showDeleteModal: false,
      accountToDelete: null,
    };
  },
  methods: {
    openEditModal(account) {
      this.editingAccount = account;
      this.showEditModal = true;
    },
    openDeleteModal(account) {
        this.accountToDelete = account;
        this.showDeleteModal = true;
    },
    getTypeColor(type) {
      const colors = {
        'Asset': 'bg-blue-100 text-blue-800',
        'Liability': 'bg-red-100 text-red-800',
        'Revenue': 'bg-green-100 text-green-800',
        'Expense': 'bg-orange-100 text-orange-800'
      };
      return colors[type] || 'bg-gray-100 text-gray-800';
    },
    getTypeBgColor(type) {
      const colors = {
        'Asset': 'bg-blue-100',
        'Liability': 'bg-red-100',
        'Revenue': 'bg-green-100',
        'Expense': 'bg-orange-100'
      };
      return colors[type] || 'bg-gray-100';
    },
    getTypeTextColor(type) {
      const colors = {
        'Asset': 'text-blue-600',
        'Liability': 'text-red-600',
        'Revenue': 'text-green-600',
        'Expense': 'text-orange-600'
      };
      return colors[type] || 'text-gray-600';
    },
    getTypeIcon(type) {
      const icons = {
        'Asset': 'fas fa-coins',
        'Liability': 'fas fa-hand-holding-usd',
        'Revenue': 'fas fa-arrow-up',
        'Expense': 'fas fa-arrow-down'
      };
      return icons[type] || 'fas fa-layer-group';
    },
    viewAccountDetails(account) {
        router.get(`/accounts/${account.id}`);
    },

    applyFilter() {
      router.get(route("/accounts"), this.filterForm, {
        preserveScroll: true,
        preserveState: true,
      });
    },
  },
};
</script>
