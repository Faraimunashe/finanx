<template>
  <div>
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Organization Currencies</h1>
    </div>

    <!-- Add Currency -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Add New Currency</h2>
      <form @submit.prevent="addCurrency" class="flex flex-col sm:flex-row gap-4">
        <select
          v-model="selectedCurrencyId"
          class="w-full sm:w-auto border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 p-2 bg-white"
          required
        >
          <option disabled value="">Select currency</option>
          <option
            v-for="currency in availableCurrencies"
            :key="currency.id"
            :value="currency.id"
          >
            {{ currency.code }} - {{ currency.country }}
          </option>
        </select>
        <button
          type="submit"
          class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow flex items-center gap-2"
        >
          <i class="fas fa-plus-circle"></i> Add Currency
        </button>
      </form>
    </div>

    <!-- Chosen Currencies Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <template v-if="currencies.length > 0">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase">
            <tr>
              <th class="px-4 py-2">Code</th>
              <th class="px-4 py-2">Country</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            <tr
              v-for="currency in currencies"
              :key="currency.id"
              class="hover:bg-gray-50"
            >
              <td class="px-4 py-3">{{ currency.code }}</td>
              <td class="px-4 py-3">{{ currency.country }}</td>
              <td class="px-4 py-3">
                <button
                  @click="removeCurrency(currency.id)"
                  class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-md transition"
                >
                  <i class="fas fa-trash-alt mr-1"></i> Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </template>
      <template v-else>
        <div class="flex flex-col items-center justify-center py-20 text-center text-gray-600">
          <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
          <p class="text-lg font-semibold">No currencies chosen</p>
          <p class="text-sm text-gray-500 mb-6">Select currencies for your organization.</p>
        </div>
      </template>
    </div>

    <!-- Snackbar Notifications -->
    <vue3-snackbar top right :duration="4000"></vue3-snackbar>
  </div>
</template>
<script>
import { router } from "@inertiajs/vue3";
import Layout from "../../Shared/Layout.vue";

export default {
  layout: Layout,
  props: {
    currencies: Array,
    allCurrencies: Array,
  },
  data() {
    return {
      selectedCurrencyId: "",
    };
  },
  computed: {
    availableCurrencies() {
      const chosenIds = this.currencies.map((c) => c.id);
      return this.allCurrencies.filter((c) => !chosenIds.includes(c.id));
    },
  },
  methods: {
    addCurrency() {
      if (!this.selectedCurrencyId) return;

      router.post("/currencies", { currency_id: this.selectedCurrencyId }, {
        onSuccess: () => {
          this.selectedCurrencyId = "";
          this.$snackbar.add({
            type: "success",
            text: "Currency added successfully!",
          });
        },
        onError: () => {
          this.$snackbar.add({
            type: "error",
            text: "Failed to add currency. It might already be selected.",
          });
        },
      });
    },
    removeCurrency(currencyId) {
      if (!currencyId) return;

      router.delete(`/currencies/${currencyId}`, {
        onSuccess: () => {
          this.$snackbar.add({
            type: "success",
            text: "Currency removed successfully!",
          });
        },
        onError: () => {
          this.$snackbar.add({
            type: "error",
            text: "Failed to remove currency.",
          });
        },
      });
    },
  },
};
</script>
