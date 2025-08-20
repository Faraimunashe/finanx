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
        <span class="text-gray-700 font-medium">Trial Balance</span>
      </nav>
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-calculator text-purple-600 mr-3"></i>
            Trial Balance
          </h1>
          <p class="text-gray-600 mt-1">As of {{ formatDate(date) }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Date:</label>
            <input
              type="date"
              v-model="selectedDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Currency:</label>
            <select
              v-model="selectedCurrencyId"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
            >
              <option value="">All Currencies</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }}
              </option>
            </select>
          </div>
          <PdfExport
            ref="pdfExport"
            :report-title="'Trial Balance'"
            :report-date="'As of ' + formatDate(date)"
            :filename="'trial-balance-' + date + '.pdf'"
            @export-complete="onExportComplete"
            @export-error="onExportError"
          >
            <template #content>
              <!-- PDF Content for Trial Balance -->
              <div class="pdf-trial-balance">
                <div class="pdf-section">
                  <h2 class="pdf-section-title">Trial Balance</h2>
                  <table>
                    <thead>
                      <tr>
                        <th>Account</th>
                        <th>Type</th>
                        <th>Debit</th>
                        <th>Credit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="account in accounts" :key="account.id">
                        <td>{{ account.name }}</td>
                        <td>{{ account.type }}</td>
                        <td class="text-right">{{ formatCurrency(account.debit || 0) }}</td>
                        <td class="text-right">{{ formatCurrency(account.credit || 0) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(totalDebits) }}</strong></td>
                        <td class="text-right"><strong>{{ formatCurrency(totalCredits) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Balance Status -->
                <div class="pdf-summary-section">
                  <h2 class="pdf-section-title">Balance Status</h2>
                  <div class="pdf-summary-grid">
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Debits:</span>
                      <span class="pdf-summary-value">{{ formatCurrency(totalDebits) }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Total Credits:</span>
                      <span class="pdf-summary-value">{{ formatCurrency(totalCredits) }}</span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Status:</span>
                      <span class="pdf-summary-value" :class="isBalanced ? 'text-green-600' : 'text-red-600'">
                        {{ isBalanced ? 'Balanced' : 'Not Balanced' }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </PdfExport>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4">
      <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Account Type</label>
          <select
            v-model="selectedType"
            @change="updateFilters"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
          >
            <option value="">All Account Types</option>
            <option value="Asset">Assets</option>
            <option value="Liability">Liabilities</option>
            <option value="Revenue">Revenue</option>
            <option value="Expense">Expenses</option>
          </select>
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">Search Accounts</label>
          <input
            type="text"
            v-model="searchTerm"
            @input="updateFilters"
            placeholder="Search account names..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
          />
        </div>
        <div class="flex items-end">
          <button
            @click="clearFilters"
            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium"
          >
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Balance Status -->
    <div class="mb-6 p-4 rounded-lg" :class="isBalanced ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
      <div class="flex items-center">
        <i :class="isBalanced ? 'fas fa-check-circle text-green-600' : 'fas fa-exclamation-triangle text-red-600'" class="mr-3 text-xl"></i>
        <div>
          <p class="font-medium" :class="isBalanced ? 'text-green-800' : 'text-red-800'">
            {{ isBalanced ? 'Trial Balance is Balanced' : 'Trial Balance is Not Balanced' }}
          </p>
          <p class="text-sm" :class="isBalanced ? 'text-green-600' : 'text-red-600'">
            Total Debits: {{ formatCurrency(totalDebits) }} | Total Credits: {{ formatCurrency(totalCredits) }}
          </p>
        </div>
      </div>
    </div>

    <!-- Trial Balance Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="bg-purple-600 text-white p-6">
        <h2 class="text-xl font-bold">Trial Balance</h2>
        <p class="text-purple-100 text-sm">As of {{ formatDate(date) }}</p>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debits</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="account in accounts" :key="account.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ account.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="[
                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                    getTypeColor(account.type)
                  ]"
                >
                  {{ account.type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                {{ formatCurrency(account.debits) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                {{ formatCurrency(account.credits) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium" :class="account.balance >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ formatCurrency(account.balance) }}
              </td>
            </tr>
          </tbody>
          <tfoot class="bg-gray-50">
            <tr>
              <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">Totals</td>
              <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">{{ formatCurrency(totalDebits) }}</td>
              <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">{{ formatCurrency(totalCredits) }}</td>
              <td class="px-6 py-4 text-sm text-right font-bold" :class="totalDebits - totalCredits === 0 ? 'text-green-600' : 'text-red-600'">
                {{ formatCurrency(totalDebits - totalCredits) }}
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-arrow-left text-red-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Debits</p>
            <p class="text-2xl font-bold text-red-600">{{ formatCurrency(totalDebits) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-arrow-right text-green-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Credits</p>
            <p class="text-2xl font-bold text-green-600">{{ formatCurrency(totalCredits) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" :class="isBalanced ? 'bg-green-100' : 'bg-red-100'">
            <i :class="isBalanced ? 'fas fa-check text-green-600' : 'fas fa-times text-red-600'" class="text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Balance Status</p>
            <p class="text-lg font-bold" :class="isBalanced ? 'text-green-600' : 'text-red-600'">
              {{ isBalanced ? 'Balanced' : 'Unbalanced' }}
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

defineOptions({
  layout: Layout
});

const props = defineProps({
  date: String,
  accounts: Array,
  totalDebits: Number,
  totalCredits: Number,
  isBalanced: Boolean,
  currencies: Array,
  selectedCurrencyId: [String, Number],
});

const selectedDate = ref(props.date);
const selectedCurrencyId = ref(props.selectedCurrencyId);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0);
};

const getTypeColor = (type) => {
  const colors = {
    'Asset': 'bg-blue-100 text-blue-800',
    'Liability': 'bg-red-100 text-red-800',
    'Revenue': 'bg-green-100 text-green-800',
    'Expense': 'bg-orange-100 text-orange-800'
  };
  return colors[type] || 'bg-gray-100 text-gray-800';
};

const updateFilters = () => {
  const params = {
    date: selectedDate.value
  };

  if (selectedCurrencyId.value) {
    params.currency_id = selectedCurrencyId.value;
  }

  router.get('/reports/trial-balance', params);
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
