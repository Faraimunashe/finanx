<template>
    <div class="space-y-6">
      <!-- Page Header -->
      <div class="flex items-center justify-between">
        <div>
          <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
            <span class="hover:text-emerald-600 cursor-pointer"><i class="fas fa-home"></i></span>
            <span>/</span>
            <span class="text-gray-700 font-medium">Accounts</span>
          </nav>
          <h1 class="text-2xl font-bold text-gray-800">Accounts</h1>
        </div>
        <button
          @click="openModal"
          class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition shadow"
        >
          <i class="fas fa-plus mr-2"></i> Add Account
        </button>
      </div>

      <!-- Account Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div
          v-for="account in accounts"
          :key="account.id"
          class="bg-white p-5 rounded-xl border hover:shadow-md space-y-2 relative"
        >
          <div class="text-xl font-semibold text-emerald-700">{{ account.name }}</div>
          <div class="text-sm text-gray-600">Type: {{ account.type }}</div>
          <div class="text-xs text-gray-400">Created: {{ formatDate(account.created_at) }}</div>

          <!-- Actions -->
          <div class="flex space-x-4 mt-4 text-sm font-medium">
            <Link
              :href="`/accounts/${account.id}/edit`"
              class="text-blue-600 hover:underline"
            >
              <i class="fas fa-pen mr-1"></i>Edit
            </Link>
            <button
              @click="confirmDelete(account.id)"
              class="text-red-600 hover:underline"
            >
              <i class="fas fa-trash mr-1"></i>Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <CreateAccountModal v-if="showModal" @close="closeModal" @saved="refreshPage" />
    </div>
  </template>

  <script>
  import { ref } from 'vue';
  import { router} from '@inertiajs/vue3';
  import Layout from '../../Shared/Layout.vue';
  import CreateAccountModal from './Components/CreateAccountModal.vue';

  export default {
    layout: Layout,
    components: {
      CreateAccountModal,
    },
    props: {
      accounts: Array,
    },
    setup(props) {
      const showModal = ref(false)

      function openModal() {
        showModal.value = true
      }

      function closeModal() {
        showModal.value = false
      }

      function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this account?')) {
          router.delete(`/accounts/${id}`)
        }
      }

      function refreshPage() {
        router.reload({ only: ['accounts'] })
      }

      function formatDate(date) {
        return new Date(date).toLocaleDateString()
      }

      return {
        showModal,
        openModal,
        closeModal,
        confirmDelete,
        refreshPage,
        formatDate,
      }
    }
  }
  </script>
