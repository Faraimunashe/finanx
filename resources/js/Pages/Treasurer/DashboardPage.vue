<template>
    <Head title="Dashboard" />
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
        <span class="hover:text-emerald-600 cursor-pointer transition-colors"><i class="fas fa-home"></i></span>
        <span>/</span>
        <span class="hover:text-emerald-600 cursor-pointer transition-colors">Dashboard</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">Financial Summary</span>
      </nav>
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-chart-line text-emerald-600 mr-3"></i>
            Financial Dashboard
          </h1>
          <p class="text-gray-600 mt-2">Monitor your organization's financial performance and recent activities</p>
        </div>
        <div class="flex items-center space-x-2">
          <div class="text-right">
            <p class="text-sm text-gray-500">Last Updated</p>
            <p class="text-sm font-medium text-gray-700">{{ new Date().toLocaleDateString() }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Date Filter -->
    <div class="mb-8 bg-white rounded-xl shadow-lg border border-gray-100 p-6">
      <div class="flex items-center mb-4">
        <i class="fas fa-filter text-emerald-600 mr-2"></i>
        <h3 class="text-lg font-semibold text-gray-800">Date Range Filter</h3>
      </div>
      <form @submit.prevent="applyFilter" class="flex flex-col sm:flex-row items-end gap-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
          <input
            type="date"
            v-model="filterForm.start_date"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
          />
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
          <input
            type="date"
            v-model="filterForm.end_date"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
          />
        </div>
        <button
          type="submit"
          class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all font-medium flex items-center gap-2"
        >
          <i class="fas fa-search"></i>
          Apply Filter
        </button>
      </form>
    </div>

    <!-- Multi-Currency Summary Cards -->
    <section class="mb-10">
      <div class="flex items-center mb-6">
        <i class="fas fa-chart-pie text-emerald-600 mr-2"></i>
        <h2 class="text-xl font-semibold text-gray-800">Financial Overview</h2>
      </div>
      <div class="space-y-6">
        <div v-for="summary in summaries" :key="summary.currency_id" class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ summary.currency.code }} Summary</h3>
            <span class="text-sm text-gray-500">{{ summary.currency.name }}</span>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <TileCard
              :icon="`fa-coins`"
              :label="`Revenue`"
              :value="formatCurrency(summary.total_revenue, summary.currency.code)"
              :color="`emerald`"
            />
            <TileCard
              :icon="`fa-money-bill-wave`"
              :label="`Expenses`"
              :value="formatCurrency(summary.total_expense, summary.currency.code)"
              :color="`rose`"
            />
            <TileCard
              :icon="`fa-balance-scale`"
              :label="`Net Balance`"
              :value="formatCurrency(summary.total_revenue - summary.total_expense, summary.currency.code)"
              :color="`amber`"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Recent Transactions Table -->
    <section class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-6">
        <h2 class="text-xl font-semibold flex items-center">
          <i class="fas fa-clock mr-3"></i> Recent Transactions
        </h2>
        <p class="text-emerald-100 text-sm mt-1">Latest financial activities across all accounts</p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ new Date(transaction.date).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ transaction.account }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="[
                    'inline-flex px-3 py-1 text-xs font-semibold rounded-full',
                    transaction.type === 'Credit' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'
                  ]"
                >
                  <i :class="transaction.type === 'Credit' ? 'fas fa-arrow-up mr-1' : 'fas fa-arrow-down mr-1'"></i>
                  {{ transaction.type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold" :class="transaction.type === 'Credit' ? 'text-emerald-600' : 'text-rose-600'">
                {{ formatCurrency(transaction.amount, transaction.currency) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex justify-between items-center">
          <p class="text-sm text-gray-600">Showing {{ transactions.length }} recent transactions</p>
          <a href="/transactions" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium transition-colors">
            View All Transactions →
          </a>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Layout from "../../Shared/Layout.vue";
import TileCard from "../../Shared/Components/TileCard.vue";
import { router } from "@inertiajs/vue3";

export default {
  layout: Layout,
  components: {
    TileCard,
  },
  props: {
    recent_transactions: Array,
    summaries: Array,
    filters: Object,
  },
  data() {
    return {
      filterForm: {
        start_date: this.filters?.start_date || "",
        end_date: this.filters?.end_date || "",
      },
    };
  },
  computed: {
    transactions() {
      // Transform transactions for display
      return this.recent_transactions.map((tx) => ({
        id: tx.id,
        date: tx.transaction_date,
        account: tx.account?.name || "N/A",
        type: tx.type,
        amount: parseFloat(tx.amount).toFixed(2),
        currency: tx.currency?.code || "USD",
      }));
    },
  },
  methods: {
    formatCurrency(amount, currencyCode = "USD") {
      return `${currencyCode} ${parseFloat(amount).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })}`;
    },
    applyFilter() {
      // Trigger a GET request to /dashboard.index route with filterForm data
      router.get("/dashboard", this.filterForm, {
        preserveState: true,
        preserveScroll: true,
      });
    },
  },
};
</script>

