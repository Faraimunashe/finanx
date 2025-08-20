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
        <span class="text-gray-700 font-medium">Balance Sheet</span>
      </nav>
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Balance Sheet</h1>
          <p class="text-gray-600 mt-1">As of {{ formatDate(date) }}</p>
        </div>
        <div class="flex gap-3">
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Date:</label>
            <input
              type="date"
              v-model="selectedDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Currency:</label>
            <select
              v-model="selectedCurrencyId"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">All Currencies</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }}
              </option>
            </select>
          </div>
          <PdfExport
            ref="pdfExport"
            :report-title="'Balance Sheet'"
            :report-date="'As of ' + formatDate(date)"
            :filename="'balance-sheet-' + date + '.pdf'"
            :selected-currency="selectedCurrency"
            @export-complete="onExportComplete"
            @export-error="onExportError"
          >
            <template #content>
              <!-- PDF Content for Balance Sheet -->
              <div class="pdf-balance-sheet">
                <!-- Executive Summary -->
                <div class="highlight">
                  <h3>Executive Summary</h3>
                  <p>This balance sheet presents the financial position of {{ companyName }} as of {{ formatDate(date) }}
                  {{ selectedCurrency ? 'in ' + selectedCurrency.code + ' currency' : 'across all currencies' }}.
                  The organization's total assets amount to {{ formatCurrency(totalAssets) }}, with total liabilities of {{ formatCurrency(totalLiabilities) }}
                  and total equity of {{ formatCurrency(totalEquity) }}.</p>
                </div>

                <!-- Assets Section -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">
                    <i class="fas fa-arrow-up text-green-600 mr-2"></i>
                    Assets
                  </h2>
                  <p class="text-sm text-gray-600 mb-3">What the organization owns and controls</p>
                  <table>
                    <thead>
                      <tr>
                        <th style="width: 50%">Account Name</th>
                        <th style="width: 25%">Account Type</th>
                        <th style="width: 25%">Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="asset in assets" :key="asset.id">
                        <td><strong>{{ asset.name }}</strong></td>
                        <td>{{ asset.type }}</td>
                        <td class="text-right text-green-600">{{ formatCurrency(asset.balance) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Total Assets</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(totalAssets) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Liabilities Section -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">
                    <i class="fas fa-arrow-down text-red-600 mr-2"></i>
                    Liabilities
                  </h2>
                  <p class="text-sm text-gray-600 mb-3">What the organization owes to others</p>
                  <table>
                    <thead>
                      <tr>
                        <th style="width: 50%">Account Name</th>
                        <th style="width: 25%">Account Type</th>
                        <th style="width: 25%">Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="liability in liabilities" :key="liability.id">
                        <td><strong>{{ liability.name }}</strong></td>
                        <td>{{ liability.type }}</td>
                        <td class="text-right text-red-600">{{ formatCurrency(liability.balance) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Total Liabilities</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(totalLiabilities) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Equity Section -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">
                    <i class="fas fa-balance-scale text-blue-600 mr-2"></i>
                    Equity
                  </h2>
                  <p class="text-sm text-gray-600 mb-3">Owner's investment and retained earnings</p>
                  <table>
                    <thead>
                      <tr>
                        <th style="width: 50%">Account Name</th>
                        <th style="width: 25%">Account Type</th>
                        <th style="width: 25%">Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="equityItem in equity" :key="equityItem.id">
                        <td><strong>{{ equityItem.name }}</strong></td>
                        <td>{{ equityItem.type }}</td>
                        <td class="text-right text-blue-600">{{ formatCurrency(equityItem.balance) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Total Equity</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(totalEquity) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Financial Position Summary -->
                <div class="pdf-summary-section">
                  <h2 class="pdf-section-title">Financial Position Analysis</h2>
                  <div class="pdf-summary-grid">
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Assets:</span>
                      <span class="pdf-summary-value text-green-600">{{ formatCurrency(totalAssets) }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Liabilities:</span>
                      <span class="pdf-summary-value text-red-600">{{ formatCurrency(totalLiabilities) }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Equity:</span>
                      <span class="pdf-summary-value text-blue-600">{{ formatCurrency(totalEquity) }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Net Worth:</span>
                      <span class="pdf-summary-value" :class="totalAssets - totalLiabilities >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatCurrency(totalAssets - totalLiabilities) }}
                      </span>
                    </div>
                  </div>

                  <!-- Balance Verification -->
                  <div class="highlight" style="margin-top: 20px;">
                    <h3>Balance Verification</h3>
                    <p>
                      <strong>Assets = Liabilities + Equity</strong><br>
                      {{ formatCurrency(totalAssets) }} = {{ formatCurrency(totalLiabilities) }} + {{ formatCurrency(totalEquity) }}<br>
                      <span :class="isBalanced ? 'status-balanced' : 'status-unbalanced'">
                        {{ isBalanced ? '✓ Balance Sheet is Balanced' : '✗ Balance Sheet is Not Balanced' }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </template>
          </PdfExport>
        </div>
      </div>
    </div>

    <!-- Currency Filter Notice -->
    <CurrencyFilterNotice
      :selected-currency="selectedCurrency"
      @clear-filter="clearCurrencyFilter"
    />

    <!-- Balance Sheet Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Assets -->
      <div class="bg-white rounded-lg shadow-md">
        <div class="bg-blue-600 text-white p-6 rounded-t-lg">
          <h2 class="text-xl font-bold">Assets</h2>
          <p class="text-blue-100 text-sm">What the organization owns</p>
        </div>
        <div class="p-6">
          <template v-if="assets && assets.length > 0">
            <div class="space-y-4">
              <div v-for="asset in assets" :key="asset.id" class="flex justify-between items-center py-2 border-b border-gray-100">
                <div>
                  <p class="font-medium text-gray-800">{{ asset.name }}</p>
                  <p class="text-sm text-gray-500">{{ asset.transactions_count }} transactions</p>
                </div>
                <p class="font-semibold text-gray-900">{{ formatCurrency(asset.balance) }}</p>
              </div>
            </div>
            <div class="mt-6 pt-4 border-t-2 border-blue-200">
              <div class="flex justify-between items-center">
                <p class="text-lg font-bold text-blue-800">Total Assets</p>
                <p class="text-xl font-bold text-blue-800">{{ formatCurrency(totalAssets) }}</p>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-center py-8">
              <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
              <p class="text-gray-600">No asset accounts found</p>
            </div>
          </template>
        </div>
      </div>

      <!-- Liabilities & Equity -->
      <div class="space-y-6">
        <!-- Liabilities -->
        <div class="bg-white rounded-lg shadow-md">
          <div class="bg-red-600 text-white p-6 rounded-t-lg">
            <h2 class="text-xl font-bold">Liabilities</h2>
            <p class="text-red-100 text-sm">What the organization owes</p>
          </div>
          <div class="p-6">
            <template v-if="liabilities && liabilities.length > 0">
              <div class="space-y-4">
                <div v-for="liability in liabilities" :key="liability.id" class="flex justify-between items-center py-2 border-b border-gray-100">
                  <div>
                    <p class="font-medium text-gray-800">{{ liability.name }}</p>
                    <p class="text-sm text-gray-500">{{ liability.transactions_count }} transactions</p>
                  </div>
                  <p class="font-semibold text-gray-900">{{ formatCurrency(liability.balance) }}</p>
                </div>
              </div>
              <div class="mt-6 pt-4 border-t-2 border-red-200">
                <div class="flex justify-between items-center">
                  <p class="text-lg font-bold text-red-800">Total Liabilities</p>
                  <p class="text-xl font-bold text-red-800">{{ formatCurrency(totalLiabilities) }}</p>
                </div>
              </div>
            </template>
            <template v-else>
              <div class="text-center py-8">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">No liability accounts found</p>
              </div>
            </template>
          </div>
        </div>

        <!-- Equity -->
        <div class="bg-white rounded-lg shadow-md">
          <div class="bg-green-600 text-white p-6 rounded-t-lg">
            <h2 class="text-xl font-bold">Equity</h2>
            <p class="text-green-100 text-sm">Owner's investment and retained earnings</p>
          </div>
          <div class="p-6">
            <template v-if="equity && equity.length > 0">
              <div class="space-y-4">
                <div v-for="equityItem in equity" :key="equityItem.id" class="flex justify-between items-center py-2 border-b border-gray-100">
                  <div>
                    <p class="font-medium text-gray-800">{{ equityItem.name }}</p>
                    <p class="text-sm text-gray-500">{{ equityItem.transactions_count }} transactions</p>
                  </div>
                  <p class="font-semibold text-gray-900">{{ formatCurrency(equityItem.balance) }}</p>
                </div>
              </div>
              <div class="mt-6 pt-4 border-t-2 border-green-200">
                <div class="flex justify-between items-center">
                  <p class="text-lg font-bold text-green-800">Total Equity</p>
                  <p class="text-xl font-bold text-green-800">{{ formatCurrency(totalEquity) }}</p>
                </div>
              </div>
            </template>
            <template v-else>
              <div class="text-center py-8">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">No equity accounts found</p>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
      <h3 class="text-xl font-bold text-gray-800 mb-4">Financial Position Summary</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
          <p class="text-sm text-gray-600">Total Assets</p>
          <p class="text-2xl font-bold text-blue-600">{{ formatCurrency(totalAssets) }}</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-600">Total Liabilities</p>
          <p class="text-2xl font-bold text-red-600">{{ formatCurrency(totalLiabilities) }}</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-600">Net Worth</p>
          <p class="text-2xl font-bold" :class="totalAssets - totalLiabilities >= 0 ? 'text-green-600' : 'text-red-600'">
            {{ formatCurrency(totalAssets - totalLiabilities) }}
          </p>
        </div>
      </div>

      <!-- Balance Check -->
      <div class="mt-6 p-4 rounded-lg" :class="isBalanced ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
        <div class="flex items-center">
          <i :class="isBalanced ? 'fas fa-check-circle text-green-600' : 'fas fa-exclamation-triangle text-red-600'" class="mr-3"></i>
          <div>
            <p class="font-medium" :class="isBalanced ? 'text-green-800' : 'text-red-800'">
              {{ isBalanced ? 'Balance Sheet is Balanced' : 'Balance Sheet is Not Balanced' }}
            </p>
            <p class="text-sm" :class="isBalanced ? 'text-green-600' : 'text-red-600'">
              Assets ({{ formatCurrency(totalAssets) }}) = Liabilities ({{ formatCurrency(totalLiabilities) }}) + Equity ({{ formatCurrency(totalEquity) }})
            </p>
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
import CurrencyFilterNotice from '../../../Shared/Components/CurrencyFilterNotice.vue';

defineOptions({
  layout: Layout
});

const props = defineProps({
  date: String,
  assets: Array,
  liabilities: Array,
  equity: Array,
  totalAssets: Number,
  totalLiabilities: Number,
  totalRevenue: Number,
  totalExpenses: Number,
  netIncome: Number,
  totalEquity: Number,
  currencies: Array,
  selectedCurrencyId: [String, Number],
});

const selectedDate = ref(props.date);
const selectedCurrencyId = ref(props.selectedCurrencyId);

const isBalanced = computed(() => {
  return Math.abs(props.totalAssets - (props.totalLiabilities + props.totalEquity)) < 0.01;
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
    date: selectedDate.value
  };

  if (selectedCurrencyId.value) {
    params.currency_id = selectedCurrencyId.value;
  }

  router.get('/reports/balance-sheet', params);
};

const clearCurrencyFilter = () => {
  selectedCurrencyId.value = '';
  updateFilters();
};

const pdfExport = ref(null);

const onExportComplete = () => {
  // You can add a success notification here
  console.log('PDF exported successfully');
};

const onExportError = (error) => {
  // You can add an error notification here
  console.error('PDF export failed:', error);
};

const exportReport = () => {
  if (pdfExport.value) {
    pdfExport.value.exportToPdf();
  }
};
</script>
