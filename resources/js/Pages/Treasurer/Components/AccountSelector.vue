<template>
  <div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-800">Account Selection</h3>
      <button
        @click="showCreateModal = true"
        class="bg-emerald-600 text-white px-3 py-2 rounded-lg shadow hover:bg-emerald-700 transition-colors font-medium flex items-center gap-2 text-sm"
      >
        <i class="fas fa-plus"></i> New Account
      </button>
    </div>

    <!-- Account Selection Dropdown -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Select Account</label>
        <select
          v-model="selectedAccountId"
          @change="onAccountChange"
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
        >
          <option value="">All Accounts</option>
          <option v-for="account in accounts" :key="account.id" :value="account.id">
            {{ account.name }} ({{ account.type }})
          </option>
        </select>
      </div>

      <!-- Selected Account Info -->
      <div v-if="selectedAccount" class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-800 mb-2">Selected Account</h4>
        <div class="space-y-1 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600">Name:</span>
            <span class="font-medium">{{ selectedAccount.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Type:</span>
            <span class="font-medium">{{ selectedAccount.type }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Transactions:</span>
            <span class="font-medium">{{ selectedAccount.transactions_count || 0 }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div v-if="selectedAccount" class="mt-4 flex gap-3">
      <button
        @click="showCreateTransactionModal = true"
        class="bg-emerald-600 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-700 transition-colors font-medium flex items-center gap-2"
      >
        <i class="fas fa-plus"></i> Add Transaction
      </button>
      <button
        @click="viewAccountDetails"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-colors font-medium flex items-center gap-2"
      >
        <i class="fas fa-eye"></i> View Details
      </button>
    </div>

    <!-- Create Account Modal -->
    <CreateAccountModal
      :show="showCreateModal"
      :errors="$page.props.errors"
      @close="showCreateModal = false"
    />

    <!-- Create Transaction Modal -->
    <CreateTransactionModal
      :show="showCreateTransactionModal"
      :accounts="[selectedAccount]"
      :currencies="currencies"
      :errors="$page.props.errors"
      @close="showCreateTransactionModal = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import CreateAccountModal from './CreateAccountModal.vue';
import CreateTransactionModal from './CreateTransactionModal.vue';

const props = defineProps({
  accounts: {
    type: Array,
    default: () => []
  },
  currencies: {
    type: Array,
    default: () => []
  },
  selectedAccountId: {
    type: [String, Number],
    default: ''
  }
});

const emit = defineEmits(['account-changed']);

const selectedAccountId = ref(props.selectedAccountId);
const showCreateModal = ref(false);
const showCreateTransactionModal = ref(false);

const selectedAccount = computed(() => {
  if (!selectedAccountId.value) return null;
  return props.accounts.find(account => account.id == selectedAccountId.value);
});

const onAccountChange = () => {
  emit('account-changed', selectedAccountId.value);

  // Update URL with selected account
  const params = new URLSearchParams(window.location.search);
  if (selectedAccountId.value) {
    params.set('account_id', selectedAccountId.value);
  } else {
    params.delete('account_id');
  }

  router.get(window.location.pathname + '?' + params.toString(), {}, {
    preserveState: true,
    preserveScroll: true
  });
};

const viewAccountDetails = () => {
  if (selectedAccount.value) {
    router.get(`/accounts/${selectedAccount.value.id}`);
  }
};

// Watch for prop changes
watch(() => props.selectedAccountId, (newValue) => {
  selectedAccountId.value = newValue;
});
</script>
