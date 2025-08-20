<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Account Details</h1>
            <p class="text-gray-600">View account information and transaction history</p>
          </div>

          <div class="flex items-center space-x-4">
            <router-link
              :href="route('user.accounts.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
              <i class="fas fa-arrow-left mr-2"></i>
              Back to Accounts
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Account Information -->
      <div class="mb-8 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
          <h2 class="text-lg font-medium text-gray-900">Account Information</h2>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center">
              <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" :class="getTypeBgColor(account.type)">
                <i :class="[getTypeIcon(account.type), getTypeTextColor(account.type), 'text-xl']"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900">{{ account.name }}</h3>
                <p class="text-gray-600">Account ID: {{ account.id }}</p>
              </div>
            </div>

            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Account Type:</span>
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getTypeBadgeColor(account.type)"
                >
                  {{ account.type }}
                </span>
              </div>

              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Current Balance:</span>
                <span class="text-lg font-bold" :class="balance >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(balance) }}
                </span>
              </div>

              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Total Transactions:</span>
                <span class="text-sm text-gray-900">{{ account.transactions?.length || 0 }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Transactions -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Transaction History</h3>
            <div class="text-sm text-gray-500">
              {{ account.transactions?.length || 0 }} transactions
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in account.transactions" :key="transaction.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(transaction.transaction_date) }}
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
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="!account.transactions || account.transactions.length === 0" class="text-center py-12">
          <i class="fas fa-receipt text-4xl text-gray-400 mb-4"></i>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
          <p class="text-gray-500">This account has no transaction history yet.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  account: Object,
  balance: Number,
});

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
