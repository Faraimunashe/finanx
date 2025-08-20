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
        <span class="text-gray-700 font-medium">Cash Flow</span>
      </nav>
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Cash Flow Statement</h1>
          <p class="text-gray-600 mt-1">For the period {{ formatDate(startDate) }} to {{ formatDate(endDate) }}</p>
        </div>
        <div class="flex gap-3">
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">From:</label>
            <input
              type="date"
              v-model="selectedStartDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">To:</label>
            <input
              type="date"
              v-model="selectedEndDate"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
            />
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Currency:</label>
            <select
              v-model="selectedCurrencyId"
              @change="updateFilters"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
            >
              <option value="">All Currencies</option>
              <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                {{ currency.code }}
              </option>
            </select>
          </div>
          <PdfExport
            ref="pdfExport"
            :report-title="'Cash Flow Statement'"
            :report-date="'For the period ' + formatDate(startDate) + ' to ' + formatDate(endDate)"
            :filename="'cash-flow-' + startDate + '-to-' + endDate + '.pdf'"
            @export-complete="onExportComplete"
            @export-error="onExportError"
          >
            <template #content>
              <!-- PDF Content for Cash Flow -->
              <div class="pdf-cash-flow">
                <!-- Operating Activities -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">Operating Activities</h2>
                  <table>
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Account</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(activity, index) in operatingActivities" :key="index">
                        <td>{{ activity.description || 'Cash transaction' }}</td>
                        <td>{{ activity.account }}</td>
                        <td class="text-right" :class="activity.amount >= 0 ? 'text-green-600' : 'text-red-600'">
                          {{ formatCurrency(activity.amount) }}
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Net Operating</strong></td>
                        <td class="text-right" :class="totalOperating >= 0 ? 'text-green-600' : 'text-red-600'">
                          <strong>{{ formatCurrency(totalOperating) }}</strong>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Investing Activities -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">Investing Activities</h2>
                  <table>
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Account</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(activity, index) in investingActivities" :key="index">
                        <td>{{ activity.description || 'Investment transaction' }}</td>
                        <td>{{ activity.account }}</td>
                        <td class="text-right" :class="activity.amount >= 0 ? 'text-green-600' : 'text-red-600'">
                          {{ formatCurrency(activity.amount) }}
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Net Investing</strong></td>
                        <td class="text-right" :class="totalInvesting >= 0 ? 'text-green-600' : 'text-red-600'">
                          <strong>{{ formatCurrency(totalInvesting) }}</strong>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Financing Activities -->
                <div class="pdf-section">
                  <h2 class="pdf-section-title">Financing Activities</h2>
                  <table>
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Account</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(activity, index) in financingActivities" :key="index">
                        <td>{{ activity.description || 'Financing transaction' }}</td>
                        <td>{{ activity.account }}</td>
                        <td class="text-right" :class="activity.amount >= 0 ? 'text-green-600' : 'text-red-600'">
                          {{ formatCurrency(activity.amount) }}
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="pdf-total-row">
                        <td colspan="2"><strong>Net Financing</strong></td>
                        <td class="text-right" :class="totalFinancing >= 0 ? 'text-green-600' : 'text-red-600'">
                          <strong>{{ formatCurrency(totalFinancing) }}</strong>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Cash Flow Summary -->
                <div class="pdf-summary-section">
                  <h2 class="pdf-section-title">Cash Flow Summary</h2>
                  <div class="pdf-summary-grid">
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Net Cash from Operating Activities:</span>
                      <span class="pdf-summary-value" :class="totalOperating >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatCurrency(totalOperating) }}
                      </span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Net Cash from Investing Activities:</span>
                      <span class="pdf-summary-value" :class="totalInvesting >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatCurrency(totalInvesting) }}
                      </span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Net Cash from Financing Activities:</span>
                      <span class="pdf-summary-value" :class="totalFinancing >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatCurrency(totalFinancing) }}
                      </span>
                    </div>
                    <div class="pdf-summary">
                      <span class="pdf-summary-label">Net Increase (Decrease) in Cash:</span>
                      <span class="pdf-summary-value" :class="netCashFlow >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatCurrency(netCashFlow) }}
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

    <!-- Cash Flow Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-cogs text-blue-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Operating</p>
            <p class="text-xl font-bold" :class="totalOperating >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ formatCurrency(totalOperating) }}
            </p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Investing</p>
            <p class="text-xl font-bold" :class="totalInvesting >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ formatCurrency(totalInvesting) }}
            </p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
            <i class="fas fa-handshake text-orange-600 text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Financing</p>
            <p class="text-xl font-bold" :class="totalFinancing >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ formatCurrency(totalFinancing) }}
            </p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" :class="netCashFlow >= 0 ? 'bg-green-100' : 'bg-red-100'">
            <i :class="netCashFlow >= 0 ? 'fas fa-arrow-up text-green-600' : 'fas fa-arrow-down text-red-600'" class="text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-600">Net Cash Flow</p>
            <p class="text-xl font-bold" :class="netCashFlow >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ formatCurrency(netCashFlow) }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Cash Flow Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Operating Activities -->
      <div class="bg-white rounded-lg shadow-md">
        <div class="bg-blue-600 text-white p-6 rounded-t-lg">
          <h2 class="text-xl font-bold">Operating Activities</h2>
          <p class="text-blue-100 text-sm">Core business operations</p>
        </div>
        <div class="p-6">
          <template v-if="operatingActivities && operatingActivities.length > 0">
            <div class="space-y-3">
              <div v-for="(activity, index) in operatingActivities" :key="index" class="border-b border-gray-100 pb-3">
                <div class="flex justify-between items-start mb-1">
                  <p class="font-medium text-gray-800 text-sm">{{ activity.description || 'Cash transaction' }}</p>
                  <p class="font-semibold text-sm" :class="activity.amount >= 0 ? 'text-green-600' : 'text-red-600'">
                    {{ formatCurrency(activity.amount) }}
                  </p>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-500">
                  <span>{{ formatDate(activity.date) }}</span>
                  <span>{{ activity.account }}</span>
                </div>
              </div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-blue-200">
              <div class="flex justify-between items-center">
                <p class="font-bold text-blue-800">Net Operating</p>
                <p class="font-bold text-lg" :class="totalOperating >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(totalOperating) }}
                </p>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-center py-8">
              <i class="fas fa-cogs text-3xl text-gray-300 mb-2"></i>
              <p class="text-gray-600">No operating activities</p>
            </div>
          </template>
        </div>
      </div>

      <!-- Investing Activities -->
      <div class="bg-white rounded-lg shadow-md">
        <div class="bg-purple-600 text-white p-6 rounded-t-lg">
          <h2 class="text-xl font-bold">Investing Activities</h2>
          <p class="text-purple-100 text-sm">Capital investments</p>
        </div>
        <div class="p-6">
          <template v-if="investingActivities && investingActivities.length > 0">
            <div class="space-y-3">
              <div v-for="(activity, index) in investingActivities" :key="index" class="border-b border-gray-100 pb-3">
                <div class="flex justify-between items-start mb-1">
                  <p class="font-medium text-gray-800 text-sm">{{ activity.description || 'Investment transaction' }}</p>
                  <p class="font-semibold text-sm" :class="activity.amount >= 0 ? 'text-green-600' : 'text-red-600'">
                    {{ formatCurrency(activity.amount) }}
                  </p>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-500">
                  <span>{{ formatDate(activity.date) }}</span>
                  <span>{{ activity.account }}</span>
                </div>
              </div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-purple-200">
              <div class="flex justify-between items-center">
                <p class="font-bold text-purple-800">Net Investing</p>
                <p class="font-bold text-lg" :class="totalInvesting >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(totalInvesting) }}
                </p>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-center py-8">
              <i class="fas fa-chart-line text-3xl text-gray-300 mb-2"></i>
              <p class="text-gray-600">No investing activities</p>
            </div>
          </template>
        </div>
      </div>

      <!-- Financing Activities -->
      <div class="bg-white rounded-lg shadow-md">
        <div class="bg-orange-600 text-white p-6 rounded-t-lg">
          <h2 class="text-xl font-bold">Financing Activities</h2>
          <p class="text-orange-100 text-sm">Debt & equity</p>
        </div>
        <div class="p-6">
          <template v-if="financingActivities && financingActivities.length > 0">
            <div class="space-y-3">
              <div v-for="(activity, index) in financingActivities" :key="index" class="border-b border-gray-100 pb-3">
                <div class="flex justify-between items-start mb-1">
                  <p class="font-medium text-gray-800 text-sm">{{ activity.description || 'Financing transaction' }}</p>
                  <p class="font-semibold text-sm" :class="activity.amount >= 0 ? 'text-green-600' : 'text-red-600'">
                    {{ formatCurrency(activity.amount) }}
                  </p>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-500">
                  <span>{{ formatDate(activity.date) }}</span>
                  <span>{{ activity.account }}</span>
                </div>
              </div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-orange-200">
              <div class="flex justify-between items-center">
                <p class="font-bold text-orange-800">Net Financing</p>
                <p class="font-bold text-lg" :class="totalFinancing >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(totalFinancing) }}
                </p>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-center py-8">
              <i class="fas fa-handshake text-3xl text-gray-300 mb-2"></i>
              <p class="text-gray-600">No financing activities</p>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- Cash Flow Summary -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
      <h3 class="text-xl font-bold text-gray-800 mb-4">Cash Flow Summary</h3>
      <div class="space-y-4">
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="font-medium text-gray-700">Net Cash from Operating Activities</span>
          <span class="font-semibold" :class="totalOperating >= 0 ? 'text-green-600' : 'text-red-600'">
            {{ formatCurrency(totalOperating) }}
          </span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="font-medium text-gray-700">Net Cash from Investing Activities</span>
          <span class="font-semibold" :class="totalInvesting >= 0 ? 'text-green-600' : 'text-red-600'">
            {{ formatCurrency(totalInvesting) }}
          </span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="font-medium text-gray-700">Net Cash from Financing Activities</span>
          <span class="font-semibold" :class="totalFinancing >= 0 ? 'text-green-600' : 'text-red-600'">
            {{ formatCurrency(totalFinancing) }}
          </span>
        </div>
        <div class="flex justify-between items-center py-3 border-t-2 border-gray-300">
          <span class="text-lg font-bold text-gray-800">Net Increase (Decrease) in Cash</span>
          <span class="text-xl font-bold" :class="netCashFlow >= 0 ? 'text-green-600' : 'text-red-600'">
            {{ formatCurrency(netCashFlow) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import PdfExport from '../../../Shared/Components/PdfExport.vue';

defineOptions({
  layout: Layout
});

const props = defineProps({
  startDate: String,
  endDate: String,
  operatingActivities: Array,
  investingActivities: Array,
  financingActivities: Array,
  totalOperating: Number,
  totalInvesting: Number,
  totalFinancing: Number,
  netCashFlow: Number,
  currencies: Array,
  selectedCurrencyId: [String, Number],
});

const selectedStartDate = ref(props.startDate);
const selectedEndDate = ref(props.endDate);
const selectedCurrencyId = ref(props.selectedCurrencyId);

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

  if (selectedCurrencyId.value) {
    params.currency_id = selectedCurrencyId.value;
  }

  router.get('/reports/cash-flow', params);
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
