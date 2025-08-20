<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">User Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ $page.props.auth.user.name }}</p>
          </div>

          <!-- Organization Selector -->
          <div class="flex items-center space-x-4">
            <div v-if="userOrganizations.length > 0" class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">Organization:</label>
              <select
                v-model="selectedOrganizationId"
                @change="selectOrganization"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              >
                <option v-for="org in userOrganizations" :key="org.id" :value="org.id">
                  {{ org.name }}
                </option>
              </select>
            </div>
            <div v-else class="text-sm text-gray-500">
              No organizations assigned
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Organization Selection Notice -->
      <div v-if="!selectedOrganization" class="mb-8 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex items-center">
          <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
          <div>
            <h3 class="text-lg font-medium text-yellow-800">Select an Organization</h3>
            <p class="text-yellow-700">Please select an organization to view your dashboard data.</p>
          </div>
        </div>
      </div>

      <!-- Dashboard Content -->
      <div v-else>
        <!-- Organization Info -->
        <div class="mb-8 p-6 bg-white rounded-lg shadow-sm border border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">{{ selectedOrganization.name }}</h2>
              <p class="text-gray-600">Current Organization</p>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-500">Organization ID</p>
              <p class="font-mono text-sm text-gray-700">#{{ selectedOrganization.id }}</p>
            </div>
          </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
              <div class="p-2 bg-emerald-100 rounded-lg">
                <i class="fas fa-chart-line text-emerald-600"></i>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">
                  {{ formatCurrency(totalRevenue) }}
                </p>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
              <div class="p-2 bg-red-100 rounded-lg">
                <i class="fas fa-chart-bar text-red-600"></i>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Expenses</p>
                <p class="text-2xl font-bold text-gray-900">
                  {{ formatCurrency(totalExpenses) }}
                </p>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
              <div class="p-2 bg-blue-100 rounded-lg">
                <i class="fas fa-wallet text-blue-600"></i>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Net Income</p>
                <p class="text-2xl font-bold" :class="netIncome >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(netIncome) }}
                </p>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
              <div class="p-2 bg-purple-100 rounded-lg">
                <i class="fas fa-exchange-alt text-purple-600"></i>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Recent Transactions</p>
                <p class="text-2xl font-bold text-gray-900">{{ recentTransactions.length }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
              <router-link
                :href="route('user.transactions.index')"
                class="text-emerald-600 hover:text-emerald-700 font-medium"
              >
                View All
              </router-link>
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
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="transaction in recentTransactions" :key="transaction.id">
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
                </tr>
                <tr v-if="recentTransactions.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                    No recent transactions found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  userOrganizations: Array,
  selectedOrganization: Object,
  recentTransactions: Array,
  summaries: Array,
  filters: Object,
});

const selectedOrganizationId = ref(props.selectedOrganization?.id || '');

const totalRevenue = computed(() => {
  return props.summaries.reduce((total, summary) => total + (summary.total_revenue || 0), 0);
});

const totalExpenses = computed(() => {
  return props.summaries.reduce((total, summary) => total + (summary.total_expense || 0), 0);
});

const netIncome = computed(() => {
  return totalRevenue.value - totalExpenses.value;
});

const selectOrganization = () => {
  if (selectedOrganizationId.value) {
    router.post(route('user.select-organization', selectedOrganizationId.value));
  }
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

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0);
};
</script>
