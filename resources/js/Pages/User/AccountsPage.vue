<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Accounts</h1>
            <p class="text-gray-600">View organization accounts and balances</p>
          </div>

          <div class="flex items-center space-x-4">
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
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
            <select
              v-model="filterForm.type"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            >
              <option value="">All Types</option>
              <option value="Asset">Asset</option>
              <option value="Liability">Liability</option>
              <option value="Revenue">Revenue</option>
              <option value="Expense">Expense</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input
              v-model="filterForm.search"
              type="text"
              placeholder="Search accounts..."
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

      <!-- Accounts Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">Organization Accounts</h2>
            <div class="text-sm text-gray-500">
              {{ accounts.total }} accounts found
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="account in accounts.data" :key="account.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" :class="getTypeBgColor(account.type)">
                      <i :class="[getTypeIcon(account.type), getTypeTextColor(account.type)]"></i>
                    </div>
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ account.name }}</div>
                      <div class="text-sm text-gray-500">ID: {{ account.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="getTypeBadgeColor(account.type)"
                  >
                    {{ account.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ formatCurrency(calculateBalance(account)) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ account.transactions_count }} transactions
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <router-link
                    :href="route('user.accounts.show', account.id)"
                    class="text-emerald-600 hover:text-emerald-900 font-medium"
                  >
                    View Details
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="accounts.data.length === 0" class="text-center py-12">
          <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No accounts found</h3>
          <p class="text-gray-500">No accounts match your current filters.</p>
        </div>

        <!-- Pagination -->
        <div v-if="accounts.data.length > 0" class="px-6 py-4 border-t border-gray-200">
          <Pagination :links="accounts.links" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import Pagination from '../../Shared/Components/Pagination.vue';

const props = defineProps({
  accounts: Object,
  filters: Object,
});

const filterForm = reactive({
  type: props.filters?.type || '',
  search: props.filters?.search || '',
});

const applyFilters = () => {
  const params = {};

  if (filterForm.type) {
    params.type = filterForm.type;
  }

  if (filterForm.search) {
    params.search = filterForm.search;
  }

  router.get(route('user.accounts.index'), params);
};

const getTypeBgColor = (type) => {
  const colors = {
    'Asset': 'bg-blue-100',
    'Liability': 'bg-red-100',
    'Revenue': 'bg-green-100',
    'Expense': 'bg-orange-100'
  };
  return colors[type] || 'bg-gray-100';
};

const getTypeIcon = (type) => {
  const icons = {
    'Asset': 'fas fa-wallet',
    'Liability': 'fas fa-credit-card',
    'Revenue': 'fas fa-chart-line',
    'Expense': 'fas fa-chart-bar'
  };
  return icons[type] || 'fas fa-circle';
};

const getTypeTextColor = (type) => {
  const colors = {
    'Asset': 'text-blue-600',
    'Liability': 'text-red-600',
    'Revenue': 'text-green-600',
    'Expense': 'text-orange-600'
  };
  return colors[type] || 'text-gray-600';
};

const getTypeBadgeColor = (type) => {
  const colors = {
    'Asset': 'bg-blue-100 text-blue-800',
    'Liability': 'bg-red-100 text-red-800',
    'Revenue': 'bg-green-100 text-green-800',
    'Expense': 'bg-orange-100 text-orange-800'
  };
  return colors[type] || 'bg-gray-100 text-gray-800';
};

const calculateBalance = (account) => {
  // This would need to be calculated from transactions
  // For now, return 0 as a placeholder
  return 0;
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0);
};
</script>
