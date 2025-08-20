<template>
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Financial Reports</h1>
      <p class="text-gray-600 mt-2">Generate and view comprehensive financial statements and reports</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-layer-group text-blue-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Accounts</p>
            <p class="text-2xl font-bold text-gray-800">{{ totalAccounts }}</p>
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
            <p class="text-2xl font-bold text-gray-800">{{ totalTransactions }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-file-alt text-emerald-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Reports Available</p>
            <p class="text-2xl font-bold text-gray-800">6</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Balance Sheet -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold">Balance Sheet</h3>
              <p class="text-blue-100 text-sm">Assets, Liabilities & Equity</p>
            </div>
            <i class="fas fa-balance-scale text-3xl opacity-80"></i>
          </div>
        </div>
        <div class="p-6">
          <p class="text-gray-600 text-sm mb-4">
            Shows the financial position of your organization at a specific point in time.
          </p>
          <div class="flex gap-2">
            <Link
              href="/reports/balance-sheet"
              class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition-colors font-medium"
            >
              View Report
            </Link>
            <button
              @click="exportReport('balance_sheet')"
              class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
            >
              <i class="fas fa-download"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Income Statement -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold">Income Statement</h3>
              <p class="text-green-100 text-sm">Revenue & Expenses</p>
            </div>
            <i class="fas fa-chart-line text-3xl opacity-80"></i>
          </div>
        </div>
        <div class="p-6">
          <p class="text-gray-600 text-sm mb-4">
            Shows your organization's profitability over a specific period.
          </p>
          <div class="flex gap-2">
            <Link
              href="/reports/income-statement"
              class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg text-center hover:bg-green-700 transition-colors font-medium"
            >
              View Report
            </Link>
            <button
              @click="exportReport('income_statement')"
              class="px-4 py-2 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors"
            >
              <i class="fas fa-download"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Cash Flow Statement -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold">Cash Flow</h3>
              <p class="text-emerald-100 text-sm">Operating, Investing & Financing</p>
            </div>
            <i class="fas fa-money-bill-wave text-3xl opacity-80"></i>
          </div>
        </div>
        <div class="p-6">
          <p class="text-gray-600 text-sm mb-4">
            Tracks the movement of cash in and out of your organization.
          </p>
          <div class="flex gap-2">
            <Link
              href="/reports/cash-flow"
              class="flex-1 bg-emerald-600 text-white px-4 py-2 rounded-lg text-center hover:bg-emerald-700 transition-colors font-medium"
            >
              View Report
            </Link>
            <button
              @click="exportReport('cash_flow')"
              class="px-4 py-2 border border-emerald-600 text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors"
            >
              <i class="fas fa-download"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Trial Balance -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold">Trial Balance</h3>
              <p class="text-purple-100 text-sm">Debits & Credits</p>
            </div>
            <i class="fas fa-calculator text-3xl opacity-80"></i>
          </div>
        </div>
        <div class="p-6">
          <p class="text-gray-600 text-sm mb-4">
            Ensures your books are balanced with debits equal to credits.
          </p>
          <div class="flex gap-2">
            <Link
              href="/reports/trial-balance"
              class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg text-center hover:bg-purple-700 transition-colors font-medium"
            >
              View Report
            </Link>
            <button
              @click="exportReport('trial_balance')"
              class="px-4 py-2 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors"
            >
              <i class="fas fa-download"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- General Ledger -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold">General Ledger</h3>
              <p class="text-orange-100 text-sm">Account Details</p>
            </div>
            <i class="fas fa-book text-3xl opacity-80"></i>
          </div>
        </div>
        <div class="p-6">
          <p class="text-gray-600 text-sm mb-4">
            Detailed view of all transactions for each account.
          </p>
          <div class="flex gap-2">
            <Link
              href="/reports/general-ledger"
              class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-lg text-center hover:bg-orange-700 transition-colors font-medium"
            >
              View Report
            </Link>
            <button
              @click="exportReport('general_ledger')"
              class="px-4 py-2 border border-orange-600 text-orange-600 rounded-lg hover:bg-orange-50 transition-colors"
            >
              <i class="fas fa-download"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold">Quick Actions</h3>
              <p class="text-gray-100 text-sm">Export & Generate</p>
            </div>
            <i class="fas fa-bolt text-3xl opacity-80"></i>
          </div>
        </div>
        <div class="p-6">
          <p class="text-gray-600 text-sm mb-4">
            Generate and export reports in various formats.
          </p>
          <div class="space-y-2">
                                    <PdfExport
                          ref="allReportsPdfExport"
                          :report-title="'Financial Reports Summary'"
                          :report-date="'Generated on ' + new Date().toLocaleDateString()"
                          :filename="'financial-reports-summary.pdf'"
                          @export-complete="onAllReportsExportComplete"
                          @export-error="onAllReportsExportError"
                        >
                          <template #content>
                            <!-- PDF Content for All Reports Summary -->
                            <div class="pdf-all-reports">
                              <div class="pdf-section">
                                <h2 class="pdf-section-title">Financial Reports Summary</h2>
                                <div class="pdf-summary-grid">
                                  <div class="pdf-summary">
                                    <span class="pdf-summary-label">Total Accounts:</span>
                                    <span class="pdf-summary-value">{{ totalAccounts }}</span>
                                  </div>
                                  <div class="pdf-summary">
                                    <span class="pdf-summary-label">Total Transactions:</span>
                                    <span class="pdf-summary-value">{{ totalTransactions }}</span>
                                  </div>
                                  <div class="pdf-summary">
                                    <span class="pdf-summary-label">Reports Available:</span>
                                    <span class="pdf-summary-value">6</span>
                                  </div>
                                </div>
                              </div>

                              <div class="pdf-section">
                                <h2 class="pdf-section-title">Available Reports</h2>
                                <table>
                                  <thead>
                                    <tr>
                                      <th>Report Name</th>
                                      <th>Description</th>
                                      <th>Status</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>Balance Sheet</td>
                                      <td>Assets, Liabilities & Equity</td>
                                      <td>Available</td>
                                    </tr>
                                    <tr>
                                      <td>Income Statement</td>
                                      <td>Revenue & Expenses</td>
                                      <td>Available</td>
                                    </tr>
                                    <tr>
                                      <td>Cash Flow</td>
                                      <td>Operating, Investing & Financing</td>
                                      <td>Available</td>
                                    </tr>
                                    <tr>
                                      <td>Trial Balance</td>
                                      <td>Debits & Credits</td>
                                      <td>Available</td>
                                    </tr>
                                    <tr>
                                      <td>General Ledger</td>
                                      <td>Account Details</td>
                                      <td>Available</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>

                              <div class="pdf-section">
                                <h2 class="pdf-section-title">Recent Transactions</h2>
                                <table>
                                  <thead>
                                    <tr>
                                      <th>Date</th>
                                      <th>Account</th>
                                      <th>Type</th>
                                      <th>Amount</th>
                                      <th>Description</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="transaction in recentTransactions" :key="transaction.id">
                                      <td>{{ formatDate(transaction.transaction_date) }}</td>
                                      <td>{{ transaction.account.name }}</td>
                                      <td>{{ transaction.type }}</td>
                                      <td class="text-right">{{ formatCurrency(transaction.amount) }} {{ transaction.currency.code }}</td>
                                      <td>{{ transaction.description || '-' }}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </template>
                        </PdfExport>
            <button
              @click="generateMonthlyReport"
              class="w-full border border-gray-600 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors font-medium"
            >
              Generate Monthly Report
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-md">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Recent Transactions</h2>
      </div>

      <div class="overflow-x-auto">
        <template v-if="recentTransactions && recentTransactions.length > 0">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in recentTransactions" :key="transaction.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(transaction.transaction_date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ transaction.account.name }}
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
                  {{ formatCurrency(transaction.amount) }} {{ transaction.currency.code }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ transaction.description || '-' }}
                </td>
              </tr>
            </tbody>
          </table>
        </template>
        <template v-else>
          <div class="text-center py-12">
            <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
            <p class="text-lg font-medium text-gray-600">No recent transactions</p>
            <p class="text-sm text-gray-500">Start by adding some transactions to see them here.</p>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import PdfExport from '../../../Shared/Components/PdfExport.vue';

defineOptions({
  layout: Layout
});

const props = defineProps({
  totalAccounts: Number,
  totalTransactions: Number,
  recentTransactions: Array
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const formatCurrency = (amount) => {
  return parseFloat(amount).toFixed(2);
};

const exportReport = (type) => {
  router.post('/reports/export', {
    type: type,
    format: 'pdf'
  });
};

const allReportsPdfExport = ref(null);

const onAllReportsExportComplete = () => {
  console.log('All reports PDF exported successfully');
};

const onAllReportsExportError = (error) => {
  console.error('All reports PDF export failed:', error);
};

const exportAllReports = () => {
  if (allReportsPdfExport.value) {
    allReportsPdfExport.value.exportToPdf();
  }
};

const generateMonthlyReport = () => {
  router.post('/reports/export', {
    type: 'monthly',
    format: 'pdf'
  });
};
</script>
