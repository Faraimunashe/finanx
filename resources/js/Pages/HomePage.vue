<template>
    <!-- Page Header -->
    <div class="mb-8">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
            <span class="hover:text-emerald-600 cursor-pointer">
                <i class="fas fa-home"></i>
            </span>
            <span>/</span>
            <span class="hover:text-emerald-600 cursor-pointer">Dashboard</span>
            <span>/</span>
            <span class="text-gray-700 font-medium">Financial Summary</span>
        </nav>

        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-2">
            <span>Financial Summary</span>
        </h1>
    </div>


        <!-- Summary Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
          <TileCard :icon="`fa-coins`" :label="`Total Revenue`" :value="`$50,000`" :color="`emerald`" />
          <TileCard :icon="`fa-money-bill-wave`" :label="`Expenses`" :value="`$30,000`" :color="`rose`" />
          <TileCard :icon="`fa-balance-scale`" :label="`Net Balance`" :value="`$20,000`" :color="`amber`" />
          <TileCard :icon="`fa-file-invoice`" :label="`Payables`" :value="`$5,000`" :color="`indigo`" />
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
                  <td class="px-4 py-3 text-right font-semibold">${{ transaction.amount }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

  </template>

<script>
import Layout from "../Shared/Layout.vue";
import TileCard from "../Shared/Components/TileCard.vue";

export default {
    layout: Layout,
    components: {
      TileCard
    },
    data() {
      return {
        transactions: [
          { id: 1, date: "2024-09-15", account: "Main Account", type: "Credit", amount: "5,000" },
          { id: 2, date: "2024-09-14", account: "Expense Account", type: "Debit", amount: "2,500" },
          { id: 3, date: "2024-09-13", account: "Revenue Account", type: "Credit", amount: "3,000" },
        ],
      };
    },
  };
</script>
