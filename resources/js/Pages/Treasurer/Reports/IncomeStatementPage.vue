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
        <span class="text-gray-700 font-medium">Income Statement</span>
      </nav>
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Income Statement</h1>
          <p class="text-gray-600 mt-1">For the period {{ formatDate(startDate) }} to {{ formatDate(endDate) }}</p>
        </div>
        <div class="flex gap-3">
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">From:</label>
            <input
              type="date"
              v-model="selectedStartDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">To:</label>
            <input
              type="date"
              v-model="selectedEndDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Currency:</label>
            <select
              v-model="selectedCurrencyId"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
              <option value="">All Currencies</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }}
              </option>
            </select>
          </div>
          <PdfExport
            ref="pdfExport"
            :report-title="'Income Statement'"
            :report-date="'For the period ' + formatDate(startDate) + ' to ' + formatDate(endDate)"
            :filename="'income-statement-' + startDate + '-to-' + endDate + '.pdf'"
            :selected-currency="selectedCurrency"
            @export-complete="onExportComplete"
            @export-error="onExportError"
          >
            <template #content>
              <!-- PDF Content for Income Statement -->
              <div class="pdf-income-statement">
                <!-- Executive Summary -->
                <div class="highlight">
                  <h3>Executive Summary</h3>
                  <p>This income statement presents the financial performance of {{ companyName }} for the period from {{ formatDate(startDate) }} to {{ formatDate(endDate) }}
                  {{ selectedCurrency ? 'in ' + selectedCurrency.code + ' currency' : 'across all currencies' }}.
                  The organization generated total revenue of {{ formatCurrency(totalRevenue) }} and incurred total expenses of {{ formatCurrency(totalExpenses) }},
                  resulting in a net income of {{ formatCurrency(netIncome) }}.</p>
                </div>

                <!-- Revenue Section -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">
                    <i class="fas fa-arrow-up text-green-600 mr-2"></i>
                    Revenue
                  </h2>
                  <p class="text-sm text-gray-600 mb-3">Income earned from business operations</p>
                  <table>
                    <thead>
                      <tr>
                        <th style="width: 50%">Account Name</th>
                        <th style="width: 25%">Account Type</th>
                        <th style="width: 25%">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="revenue in revenues" :key="revenue.id">
                        <td><strong>{{ revenue.name }}</strong></td>
                        <td>{{ revenue.type }}</td>
                        <td class="text-right text-green-600">{{ formatCurrency(revenue.balance) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Total Revenue</strong></td>
                        <td class="text-right text-green-600"><strong>{{ formatCurrency(totalRevenue) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Expenses Section -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">
                    <i class="fas fa-arrow-down text-red-600 mr-2"></i>
                    Expenses
                  </h2>
                  <p class="text-sm text-gray-600 mb-3">Costs incurred in business operations</p>
                  <table>
                    <thead>
                      <tr>
                        <th style="width: 50%">Account Name</th>
                        <th style="width: 25%">Account Type</th>
                        <th style="width: 25%">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="expense in expenses" :key="expense.id">
                        <td><strong>{{ expense.name }}</strong></td>
                        <td>{{ expense.type }}</td>
                        <td class="text-right text-red-600">{{ formatCurrency(expense.balance) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Total Expenses</strong></td>
                        <td class="text-right text-red-600"><strong>{{ formatCurrency(totalExpenses) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Net Income Calculation -->
                <div class="pdf-summary-section">
                  <h2 class="pdf-section-title">Net Income Calculation</h2>
                  <div class="pdf-summary-grid">
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Revenue:</span>
                      <span class="pdf-summary-value text-green-600">{{ formatCurrency(totalRevenue) }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Less: Total Expenses:</span>
                      <span class="pdf-summary-value text-red-600">({{ formatCurrency(totalExpenses) }})</span>
                    </div>
                    <div class="pdf-summary" style="border-top: 2px solid #e2e8f0; padding-top: 15px; margin-top: 10px;">
                      <span class="pdf-summary-label">Net Income:</span>
                      <span class="pdf-summary-value" :class="netIncome >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatCurrency(netIncome) }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Profitability Analysis -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">Profitability Analysis</h2>
                  <div class="pdf-summary-grid">
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Profit Margin:</span>
                      <span class="pdf-summary-value" :class="profitMargin >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ profitMargin.toFixed(1) }}%
                      </span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Performance:</span>
                      <span class="pdf-summary-value" :class="netIncome >= 0 ? 'status-balanced' : 'status-unbalanced'">
                        {{ netIncome >= 0 ? 'Profitable' : 'Loss' }}
                      </span>
                    </div>
                  </div>

                  <!-- Performance Summary -->
                  <div class="highlight" style="margin-top: 20px;">
                    <h3>Performance Summary</h3>
                    <p>
                      <strong>Period:</strong> {{ formatDate(startDate) }} to {{ formatDate(endDate) }}<br>
                      <strong>Revenue Growth:</strong> {{ totalRevenue > 0 ? 'Positive revenue generation' : 'No revenue recorded' }}<br>
                      <strong>Expense Management:</strong> {{ totalExpenses > 0 ? 'Expenses properly tracked' : 'No expenses recorded' }}<br>
                      <strong>Net Result:</strong> {{ netIncome >= 0 ? 'Profitable period' : 'Loss period' }}
                    </p>
                  </div>
                </div>
              </div>
            </template>
          </PdfExport>
        </div>
      </div>
    </div>

    <!-- Income Statement Content -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6">
        <h2 class="text-2xl font-bold">Income Statement</h2>
        <p class="text-green-100">For the period {{ formatDate(startDate) }} to {{ formatDate(endDate) }}</p>
      </div>

      <div class="p-6">
        <!-- Revenue Section -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-arrow-up text-green-600 mr-2"></i>
            Revenue
          </h3>
          <template v-if="revenues && revenues.length > 0">
            <div class="space-y-3">
              <div v-for="revenue in revenues" :key="revenue.id" class="flex justify-between items-center py-2 border-b border-gray-100">
                <div>
                  <p class="font-medium text-gray-800">{{ revenue.name }}</p>
                  <p class="text-sm text-gray-500">{{ revenue.transactions_count }} transactions</p>
                </div>
                <p class="font-semibold text-green-600">{{ formatCurrency(revenue.balance) }}</p>
              </div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-green-200">
              <div class="flex justify-between items-center">
                <p class="text-lg font-bold text-green-800">Total Revenue</p>
                <p class="text-xl font-bold text-green-800">{{ formatCurrency(totalRevenue) }}</p>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-center py-6 bg-gray-50 rounded-lg">
              <i class="fas fa-chart-line text-3xl text-gray-300 mb-2"></i>
              <p class="text-gray-600">No revenue accounts found for this period</p>
            </div>
          </template>
        </div>

        <!-- Expenses Section -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-arrow-down text-red-600 mr-2"></i>
            Expenses
          </h3>
          <template v-if="expenses && expenses.length > 0">
            <div class="space-y-3">
              <div v-for="expense in expenses" :key="expense.id" class="flex justify-between items-center py-2 border-b border-gray-100">
                <div>
                  <p class="font-medium text-gray-800">{{ expense.name }}</p>
                  <p class="text-sm text-gray-500">{{ expense.transactions_count }} transactions</p>
                </div>
                <p class="font-semibold text-red-600">{{ formatCurrency(expense.balance) }}</p>
              </div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-red-200">
              <div class="flex justify-between items-center">
                <p class="text-lg font-bold text-red-800">Total Expenses</p>
                <p class="text-xl font-bold text-red-800">{{ formatCurrency(totalExpenses) }}</p>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-center py-6 bg-gray-50 rounded-lg">
              <i class="fas fa-chart-line text-3xl text-gray-300 mb-2"></i>
              <p class="text-gray-600">No expense accounts found for this period</p>
            </div>
          </template>
        </div>

        <!-- Net Income -->
        <div class="border-t-2 border-gray-300 pt-6">
          <div class="flex justify-between items-center">
            <p class="text-2xl font-bold text-gray-800">Net Income</p>
            <p class="text-3xl font-bold" :class="netIncome >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ formatCurrency(netIncome) }}
            </p>
          </div>
          <p class="text-sm text-gray-500 mt-1">
            {{ netIncome >= 0 ? 'Profit' : 'Loss' }} for the period
          </p>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-arrow-up text-green-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">{{ formatCurrency(totalRevenue) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-arrow-down text-red-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Expenses</p>
            <p class="text-2xl font-bold text-red-600">{{ formatCurrency(totalExpenses) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" :class="netIncome >= 0 ? 'bg-green-100' : 'bg-red-100'">
            <i :class="netIncome >= 0 ? 'fas fa-chart-line text-green-600' : 'fas fa-chart-line text-red-600'" class="text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Net Income</p>
            <p class="text-2xl font-bold" :class="netIncome >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ formatCurrency(netIncome) }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Profitability Analysis -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
      <h3 class="text-xl font-bold text-gray-800 mb-4">Profitability Analysis</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h4 class="font-semibold text-gray-700 mb-2">Profit Margin</h4>
          <div class="bg-gray-100 rounded-lg p-4">
            <p class="text-3xl font-bold" :class="profitMargin >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ profitMargin.toFixed(1) }}%
            </p>
            <p class="text-sm text-gray-600">
              {{ profitMargin >= 0 ? 'Profit' : 'Loss' }} margin for the period
            </p>
          </div>
        </div>
        <div>
          <h4 class="font-semibold text-gray-700 mb-2">Revenue vs Expenses</h4>
          <div class="bg-gray-100 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm text-gray-600">Revenue</span>
              <span class="text-sm font-medium text-green-600">{{ formatCurrency(totalRevenue) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Expenses</span>
              <span class="text-sm font-medium text-red-600">{{ formatCurrency(totalExpenses) }}</span>
            </div>
          </div>
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
  startDate: String,
  endDate: String,
  revenues: Array,
  expenses: Array,
  totalRevenue: Number,
  totalExpenses: Number,
  netIncome: Number,
  currencies: Array,
  selectedCurrencyId: [String, Number],
});

const selectedStartDate = ref(props.startDate);
const selectedEndDate = ref(props.endDate);
const selectedCurrencyId = ref(props.selectedCurrencyId);

const profitMargin = computed(() => {
  if (props.totalRevenue === 0) return 0;
  return (props.netIncome / props.totalRevenue) * 100;
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const selectedCurrency = computed(() => {
  if (!selectedCurrencyId.value) return null;
  return props.currencies.find(c => c.id == selectedCurrencyId.value);
});

const formatCurrency = (amount) => {
  const currencyCode = selectedCurrency.value?.code || 'USD';
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: currencyCode
  }).format(amount || 0);
};

const updateFilters = () => {
  const params = {
    start_date: selectedStartDate.value,
    end_date: selectedEndDate.value
  };

  if (selectedCurrencyId.value) {
    params.currency_id = selectedCurrencyId.value;
  }

  router.get('/reports/income-statement', params);
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
