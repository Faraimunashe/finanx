<template>
    <div class="space-y-6">
      <!-- Breadcrumb & Page Title -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
                <span class="hover:text-emerald-600 cursor-pointer">
                    <i class="fas fa-home"></i>
                </span>
                <span>/</span>
                <span class="text-gray-700 font-medium">My Organizations</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-800">My Organizations</h1>
            </div>

            <button
                @click="showModal = true"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg shadow-md transition"
            >
                <i class="fas fa-plus mr-2"></i> Create Organization
            </button>
        </div>

      <div v-if="organizations.length > 0" class="space-y-4">
        <div
            v-for="org in organizations"
            :key="org.id"
            class="bg-white shadow-sm rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300"
        >
            <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <!-- Left: Org Info -->
            <div class="flex items-start gap-4 flex-1">
                <!-- Icon Badge -->
                <div class="bg-emerald-100 text-emerald-600 rounded-full h-12 w-12 flex items-center justify-center shadow-inner">
                <i class="fas fa-building text-xl"></i>
                </div>
                <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ org.name }}</h2>
                <p class="text-sm text-gray-600">{{ org.address }}</p>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-phone-alt mr-1 text-gray-400"></i> {{ org.phone }} |
                    <i class="fas fa-envelope ml-2 mr-1 text-gray-400"></i> {{ org.email }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Created on {{ formatDate(org.created_at) }}</p>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex flex-col sm:items-end gap-3">
                <!-- Account Count Badge -->
                <div class="flex items-center gap-2">
                <div class="bg-indigo-100 text-indigo-600 rounded-full h-10 w-10 flex items-center justify-center">
                    <i class="fas fa-wallet text-sm"></i>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Accounts</p>
                    <p class="text-lg font-bold text-indigo-700">{{ org.accounts_count }}</p>
                </div>
                </div>

                <!-- Enter Button -->
                <a
                :href="`/organizations/${org.id}`"
                class="mt-2 inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-800 transition"
                >
                <i class="fas fa-arrow-right mr-1"></i> Enter Organization
                </a>
            </div>
            </div>
        </div>
        </div>


      <div v-else class="text-gray-500 italic text-sm">No organizations found.</div>

      <!-- Create Modal -->
      <CreateOrganizationModal
        :visible="showModal"
        @close="showModal = false"
        @submit="handleNewOrganization"
    />
    </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import CreateOrganizationModal from './Components/CreateOrganizationModal.vue';

  export default {
    layout: Layout,
    components: {
        CreateOrganizationModal
    },
    props: {
        organizations: Array
    },
    data() {
      return {
        showModal: false,
      };
    },
    methods: {
        formatDate(dateStr) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString(undefined, options);
        },

        handleNewOrganization(org) {
            org.id = Date.now();
            org.created_at = new Date().toISOString();
            org.accounts_count = 0;
            this.organizations.unshift(org);
            this.showModal = false;
        },
    }
  };
</script>

