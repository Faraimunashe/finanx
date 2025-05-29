<template>
    <Head title="Dashboard" />
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
        <span class="hover:text-emerald-600 cursor-pointer"><i class="fas fa-home"></i></span>
        <span>/</span>
        <span class="hover:text-emerald-600 cursor-pointer">Dashboard</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">Financial Summary</span>
      </nav>
      <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-2">
        <span>Financial Summary</span>
      </h1>
    </div>

    <!-- Date Filter -->
    <form @submit.prevent="applyFilter" class="mb-6 flex flex-wrap items-end gap-4 bg-white p-4 rounded-lg shadow">
      <div>
        <label class="text-sm text-gray-600">Start Date</label>
        <input
          type="date"
          v-model="filterForm.start_date"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
        />
      </div>
      <div>
        <label class="text-sm text-gray-600">End Date</label>
        <input
          type="date"
          v-model="filterForm.end_date"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
        />
      </div>
      <button
        type="submit"
        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md shadow font-medium"
      >
        Apply Filter
      </button>
    </form>

    <!-- Multi-Currency Summary Cards -->
    <section class="space-y-6 mb-10">
      <div v-for="summary in summaries" :key="summary.currency_id" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <TileCard
          :icon="`fa-coins`"
          :label="`Revenue (${summary.currency.code})`"
          :value="formatCurrency(summary.total_revenue, summary.currency.code)"
          :color="`emerald`"
        />
        <TileCard
          :icon="`fa-money-bill-wave`"
          :label="`Expenses (${summary.currency.code})`"
          :value="formatCurrency(summary.total_expense, summary.currency.code)"
          :color="`rose`"
        />
        <TileCard
          :icon="`fa-balance-scale`"
          :label="`Net Balance (${summary.currency.code})`"
          :value="formatCurrency(summary.total_revenue - summary.total_expense, summary.currency.code)"
          :color="`amber`"
        />
      </div>
    </section>

    <!-- Recent Transactions Table -->
    <section class="bg-white shadow-lg rounded-lg p-6">
      <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
        <i class="fas fa-clock mr-2 text-emerald-500"></i> Recent Transactions
      </h2>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 text-xs text-gray-600 uppercase">
            <tr>
              <th class="px-4 py-2 text-left">Date</th>
              <th class="px-4 py-2 text-left">Account</th>
              <th class="px-4 py-2 text-left">Type</th>
              <th class="px-4 py-2 text-right">Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-50">
              <td class="px-4 py-3">{{ transaction.date }}</td>
              <td class="px-4 py-3">{{ transaction.account }}</td>
              <td class="px-4 py-3">
                <span
                  :class="[
                    'text-xs font-medium px-2 py-1 rounded',
                    transaction.type === 'Credit' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'
                  ]"
                >
                  {{ transaction.type }}
                </span>
              </td>
              <td class="px-4 py-3 text-right font-semibold">
                {{ formatCurrency(transaction.amount, transaction.currency) }}
              </td>
            </tr>
          </tbody>
        </table>
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
      transactions: this.recent_transactions.map((tx) => ({
        id: tx.id,
        date: tx.transaction_date,
        account: tx.account?.name || "N/A",
        type: tx.type === "revenue" ? "Credit" : "Debit",
        amount: parseFloat(tx.amount).toFixed(2),
        currency: tx.currency?.code || "USD",
      })),
    };
  },
  methods: {
    formatCurrency(amount, currencyCode = "USD") {
      return `${currencyCode} ${parseFloat(amount).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })}`;
    },
    applyFilter() {
      router.get(route("dashboard.index"), this.filterForm, {
        preserveState: true,
        preserveScroll: true,
      });
    },
  },
};
</script>
