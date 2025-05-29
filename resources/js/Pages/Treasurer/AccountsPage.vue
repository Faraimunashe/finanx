<template>
  <div>
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Accounts</h1>
      <button
        @click="openCreateModal"
        class="bg-emerald-600 text-white px-4 py-2 rounded shadow hover:bg-emerald-700"
      >
        <i class="fas fa-plus mr-2"></i> New Account
      </button>
    </div>

    <form @submit.prevent="applyFilter" class="bg-white p-6 rounded-lg shadow-md mb-8 space-y-4 md:space-y-0 md:flex md:items-end md:gap-6">
    <!-- Account Name -->
    <div class="w-full md:w-1/3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
        <div class="relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
            <i class="fas fa-search"></i>
        </span>
        <input
            type="text"
            v-model="filterForm.name"
            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
            placeholder="e.g. Main Account"
        />
        </div>
    </div>

    <!-- Account Type -->
    <div class="w-full md:w-1/3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
        <div class="relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
            <i class="fas fa-layer-group"></i>
        </span>
        <select
            v-model="filterForm.type"
            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
        >
            <option value="">All</option>
            <option value="Asset">Asset</option>
            <option value="Liability">Liability</option>
            <option value="Revenue">Revenue</option>
            <option value="Expense">Expense</option>
        </select>
        </div>
    </div>

    <!-- Filter Button -->
    <div class="w-full md:w-auto mt-2 md:mt-0">
        <button
        type="submit"
        class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-md shadow font-medium flex items-center justify-center gap-2"
        >
        <i class="fas fa-filter"></i> Filter
        </button>
    </div>
    </form>


    <!-- Accounts Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
    <template v-if="accounts.length > 0">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase">
            <tr>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
            <tr v-for="account in accounts" :key="account.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ account.name }}</td>
            <td class="px-4 py-3">{{ account.type }}</td>
            <td class="px-4 py-3">
                <div class="flex gap-2">
                    <button
                    @click="openEditModal(account)"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md transition"
                    >
                    <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button
                        @click="openDeleteModal(account)"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-md transition"
                    >
                        <i class="fas fa-trash-alt mr-1"></i> Delete
                    </button>

                </div>
            </td>

            </tr>
        </tbody>
        </table>
    </template>

    <template v-else>
        <div class="flex flex-col items-center justify-center py-20 text-center text-gray-600">
        <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
        <p class="text-lg font-semibold">No accounts yet</p>
        <p class="text-sm text-gray-500 mb-6">Start by creating your first financial account for this organisation.</p>
        <button
            @click="openCreateModal"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded shadow-md flex items-center gap-2"
        >
            <i class="fas fa-plus-circle"></i> Create Account
        </button>
        </div>
    </template>
    </div>


    <!-- Slide-in Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex justify-end bg-black bg-opacity-30"
      @click.self="closeModal"
    >
      <div class="bg-white w-full sm:w-3/5 md:w-2/5 lg:w-1/3 h-full shadow-xl p-6 overflow-y-auto transition-all duration-300 ease-in-out">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
          {{ modalMode === 'create' ? 'Create Account' : 'Edit Account' }}
        </h2>
        <form @submit.prevent="submit" class="space-y-5">
          <!-- Account Name Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="fas fa-wallet"></i>
              </span>
              <input
                type="text"
                v-model="form.name"
                class="pl-10 pr-4 py-3 w-full rounded-md border border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="e.g. Main Cash Account"
                required
              />
            </div>
          </div>

          <!-- Account Type Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="fas fa-layer-group"></i>
              </span>
              <select
                v-model="form.type"
                class="pl-10 pr-4 py-3 w-full rounded-md border border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                required
              >
                <option disabled value="">Select type</option>
                <option value="Asset">Asset</option>
                <option value="Liability">Liability</option>
                <option value="Revenue">Revenue</option>
                <option value="Expense">Expense</option>
              </select>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-3 pt-2">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 text-gray-700 hover:text-gray-900 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-emerald-600 text-white rounded-md shadow hover:bg-emerald-700"
            >
              {{ modalMode === 'create' ? 'Create Account' : 'Update Account' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
        Confirm Deletion
        </h3>
        <p class="text-gray-600 mb-6">
        Are you sure you want to delete the account
        <span class="font-semibold text-gray-800">{{ accountToDelete?.name }}</span>?
        This action cannot be undone.
        </p>
        <div class="flex justify-end gap-3">
        <button
            @click="closeDeleteModal"
            class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200"
        >
            Cancel
        </button>
        <button
            @click="confirmDelete"
            class="px-4 py-2 bg-rose-600 text-white rounded shadow hover:bg-rose-700"
        >
            Yes, Delete
        </button>
        </div>
    </div>
    </div>

  </div>
</template>

<script>
import { router } from "@inertiajs/vue3";
import Layout from '../../Shared/Layout.vue';

export default {
  layout: Layout,
  props: {
    accounts: Array,
    filters: Object,
  },
  data() {
    return {
      showModal: false,
      modalMode: "create",
      editingId: null,
      form: {
        name: "",
        type: "",
      },
      filterForm: {
        name: this.filters?.name || "",
        type: this.filters?.type || "",
      },
      showDeleteModal: false,
      accountToDelete: null,
    };
  },
  methods: {
    openCreateModal() {
      this.modalMode = "create";
      this.form = { name: "", type: "" };
      this.editingId = null;
      this.showModal = true;
    },
    openEditModal(account) {
      this.modalMode = "edit";
      this.form = { name: account.name, type: account.type };
      this.editingId = account.id;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
    submit() {
      if (this.modalMode === "create") {
        router.post("/accounts", this.form, {
          onSuccess: () => this.closeModal(),
        });
      } else {
        router.put("/accounts/"+this.editingId, this.form, {
          onSuccess: () => this.closeModal(),
        });
      }
    },
    openDeleteModal(account) {
        this.accountToDelete = account;
        this.showDeleteModal = true;
    },

    confirmDelete() {
        if (!this.accountToDelete) return;

        this.showDeleteModal = false;

        this.$inertia.delete(route('accounts.destroy', this.accountToDelete.id), {
            onFinish: () => {
            this.accountToDelete = null;
            }
        });
    },

    closeDeleteModal() {
        this.showDeleteModal = false;
        this.accountToDelete = null;
    },

    applyFilter() {
      router.get(route("/accounts"), this.filterForm, {
        preserveScroll: true,
        preserveState: true,
      });
    },
  },
};
</script>
