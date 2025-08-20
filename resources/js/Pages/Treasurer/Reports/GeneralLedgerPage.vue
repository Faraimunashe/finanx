<template>
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
        <span class="hover:text-emerald-600 cursor-pointer" @click="$inertia.get('/reports')">
          <i class="fas fa-home"></i>
        </span>
        <span>/</span>
        <span class="hover:text-emerald-600 cursor-pointer" @click="$inertia.get('/reports')">Reports</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">General Ledger</span>
      </nav>
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">General Ledger</h1>
          <p class="text-gray-600 mt-1">For the period {{ formatDate(startDate) }} to {{ formatDate(endDate) }}</p>
        </div>
        <div class="flex gap-3">
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Account:</label>
            <select
              v-model="selectedAccountId"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            >
              <option value="">All Accounts</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.name }} ({{ account.type }})
              </option>
            </select>
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">From:</label>
            <input
              type="date"
              v-model="selectedStartDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">To:</label>
            <input
              type="date"
              v-model="selectedEndDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Currency:</label>
            <select
              v-model="selectedCurrencyId"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            >
              <option value="">All Currencies</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }}
              </option>
            </select>
          </div>
          <PdfExport
            ref="pdfExport"
            :report-title="'General Ledger'"
            :report-date="'For the period ' + formatDate(startDate) + ' to ' + formatDate(endDate)"
            :filename="'general-ledger-' + startDate + '-to-' + endDate + '.pdf'"
            orientation="landscape"
            @export-complete="onExportComplete"
            @export-error="onExportError"
          >
            <template #content>
              <!-- PDF Content for General Ledger -->
              <div class="pdf-general-ledger">
                <div v-for="account in accounts" :key="account.id" class="pdf-account-section">
                  <h2 class="pdf-section-title">{{ account.name }} ({{ account.type }})</h2>
                  <table>
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="transaction in account.transactions" :key="transaction.id">
                        <td>{{ formatDate(transaction.date) }}</td>
                        <td>{{ transaction.description || '-' }}</td>
                        <td class="text-right">{{ formatCurrency(transaction.debit) }}</td>
                        <td class="text-right">{{ formatCurrency(transaction.credit) }}</td>
                        <td class="text-right">{{ formatCurrency(transaction.balance) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Account Total</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(account.transactions.reduce((sum, t) => sum + t.debit, 0)) }}</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(account.transactions.reduce((sum, t) => sum + t.credit, 0)) }}</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(account.closing_balance) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Summary -->
                <div class="pdf-summary-section">
                  <h2 class="pdf-section-title">General Ledger Summary</h2>
                  <div class="pdf-summary-grid">
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Transactions:</span>
                      <span class="pdf-summary-value">{{ totalTransactions }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Debits:</span>
                      <span class="pdf-summary-value">{{ formatCurrency(totalDebits) }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Credits:</span>
                      <span class="pdf-summary-value">{{ formatCurrency(totalCredits) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </PdfExport>
        </div>
      </div>
    </div>

    <!-- General Ledger Content -->
    <div v-for="account in accounts" :key="account.id" class="mb-8 bg-white rounded-lg shadow-md overflow-hidden">
      <!-- Account Header -->
      <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-xl font-bold">{{ account.name }}</h2>
            <p class="text-orange-100 text-sm">{{ account.type }} Account</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-orange-100">Opening Balance</p>
            <p class="text-lg font-bold">{{ formatCurrency(account.opening_balance) }}</p>
          </div>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Credit</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <template v-if="account.transactions && account.transactions.length > 0">
              <tr v-for="transaction in account.transactions" :key="transaction.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(transaction.date) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ transaction.description || 'No description' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                  {{ transaction.debit > 0 ? formatCurrency(transaction.debit) : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                  {{ transaction.credit > 0 ? formatCurrency(transaction.credit) : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium" :class="transaction.balance >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(transaction.balance) }}
                </td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                  <i class="fas fa-box-open text-3xl text-gray-300 mb-2 block"></i>
                  No transactions found for this account in the selected period
                </td>
              </tr>
            </template>
          </tbody>
          <tfoot class="bg-gray-50">
            <tr>
              <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">Closing Balance</td>
              <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">
                {{ formatCurrency(account.transactions?.filter(t => t.debit > 0).reduce((sum, t) => sum + t.debit, 0) || 0) }}
              </td>
              <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">
                {{ formatCurrency(account.transactions?.filter(t => t.credit > 0).reduce((sum, t) => sum + t.credit, 0) || 0) }}
              </td>
              <td class="px-6 py-4 text-sm text-right font-bold" :class="account.closing_balance >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ formatCurrency(account.closing_balance) }}
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Summary -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
      <h3 class="text-xl font-bold text-gray-800 mb-4">General Ledger Summary</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center">
          <p class="text-sm text-gray-600">Total Accounts</p>
          <p class="text-2xl font-bold text-orange-600">{{ accounts.length }}</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-600">Total Transactions</p>
          <p class="text-2xl font-bold text-orange-600">{{ totalTransactions }}</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-600">Total Debits</p>
          <p class="text-2xl font-bold text-red-600">{{ formatCurrency(totalDebits) }}</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-600">Total Credits</p>
          <p class="text-2xl font-bold text-green-600">{{ formatCurrency(totalCredits) }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import PdfExport from '../../../Shared/Components/PdfExport.vue';

defineOptions({
  layout: Layout
});

const props = defineProps({
  accounts: Array,
  startDate: String,
  endDate: String,
  selectedAccountId: [String, Number],
  currencies: Array,
  selectedCurrencyId: [String, Number],
});

const selectedStartDate = ref(props.startDate);
const selectedEndDate = ref(props.endDate);
const selectedAccountId = ref(props.selectedAccountId);
const selectedCurrencyId = ref(props.selectedCurrencyId);

const totalTransactions = computed(() => {
  return props.accounts.reduce((total, account) => {
    return total + (account.transactions?.length || 0);
  }, 0);
});

const totalDebits = computed(() => {
  return props.accounts.reduce((total, account) => {
    return total + (account.transactions?.reduce((sum, t) => sum + t.debit, 0) || 0);
  }, 0);
});

const totalCredits = computed(() => {
  return props.accounts.reduce((total, account) => {
    return total + (account.transactions?.reduce((sum, t) => sum + t.credit, 0) || 0);
  }, 0);
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0);
};

const updateFilters = () => {
  const params = {
    start_date: selectedStartDate.value,
    end_date: selectedEndDate.value
  };

  if (selectedAccountId.value) {
    params.account_id = selectedAccountId.value;
  }

  if (selectedCurrencyId.value) {
    params.currency_id = selectedCurrencyId.value;
  }

  router.get('/reports/general-ledger', params);
};

const pdfExport = ref(null);

const onExportComplete = () => {
  console.log('PDF exported successfully');
};

const onExportError = (error) => {
  console.error('PDF export failed:', error);
};

const exportReport = () => {
  if (pdfExport.value) {
    pdfExport.value.exportToPdf();
  }
};
</script>
