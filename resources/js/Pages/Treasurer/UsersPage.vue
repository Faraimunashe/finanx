<template>
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <nav class="text-sm text-gray-500 mb-2 flex items-center space-x-1">
        <span class="hover:text-emerald-600 cursor-pointer transition-colors"><i class="fas fa-home"></i></span>
        <span>/</span>
        <span class="hover:text-emerald-600 cursor-pointer transition-colors">Dashboard</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">User Management</span>
      </nav>
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-users text-emerald-600 mr-3"></i>
            User Management
          </h1>
          <p class="text-gray-600 mt-2">Manage organization users and roles</p>
        </div>
        <div class="flex items-center space-x-2">
          <button
            @click="showCreateModal = true"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
          >
            <i class="fas fa-user-plus mr-2"></i>
            Add User
          </button>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input
              v-model="filterForm.search"
              type="text"
              placeholder="Search by name or email..."
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select
              v-model="filterForm.role"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-all"
            >
              <option value="">All Roles</option>
              <option v-for="role in roles" :key="role.id" :value="role.name">
                {{ role.display_name || role.name }}
              </option>
            </select>
          </div>

          <div class="flex items-end">
            <button
              type="submit"
              class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all font-medium flex items-center justify-center gap-2"
            >
              <i class="fas fa-filter"></i> Apply Filters
            </button>
          </div>
        </form>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">Organization Users</h2>
            <div class="text-sm text-gray-500">
              {{ users.total }} users found
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizations</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center mr-3">
                      <i class="fas fa-user text-emerald-600"></i>
                    </div>
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                      <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ user.email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    v-for="role in user.roles"
                    :key="role.id"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mr-1"
                    :class="getRoleBadgeColor(role.name)"
                  >
                    {{ role.display_name || role.name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ user.organizations?.length || 0 }} organizations
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="editUser(user)"
                    class="text-emerald-600 hover:text-emerald-900 font-medium mr-3"
                  >
                    Edit
                  </button>
                  <button
                    @click="confirmRemoveUser(user)"
                    class="text-red-600 hover:text-red-900 font-medium"
                  >
                    Remove
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="users.data.length === 0" class="text-center py-12">
          <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
          <p class="text-gray-500">No users match your current filters.</p>
        </div>

        <!-- Pagination -->
        <div v-if="users.data.length > 0" class="px-6 py-4 border-t border-gray-200">
          <Pagination :links="users.links" />
        </div>
      </div>

    <!-- Create User Modal -->
    <CreateUserModal
      v-if="showCreateModal"
      :roles="roles"
      @close="showCreateModal = false"
      @created="onUserCreated"
    />

    <!-- Edit User Modal -->
    <EditUserModal
      v-if="showEditModal"
      :user="editingUser"
      :roles="roles"
      @close="showEditModal = false"
      @updated="onUserUpdated"
    />

    <!-- Confirm Remove User Modal -->
    <DeleteConfirmationModal
      v-if="showDeleteModal"
      :title="'Remove User'"
      :message="`Are you sure you want to remove ${userToDelete?.name} from this organization? This action cannot be undone.`"
      @confirm="removeUser"
      @cancel="showDeleteModal = false"
    />

    <!-- Confirm Add Existing User Modal -->
    <div v-if="confirmExistingUser" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">User Already Exists</h3>
          </div>

          <div class="mb-4">
            <p class="text-sm text-gray-600 mb-3">
              A user with email <strong>{{ confirmExistingUser.user.email }}</strong> already exists in the system.
            </p>
            <p class="text-sm text-gray-600">
              Do you want to add this user to your organization with the role <strong>{{ confirmExistingUser.role }}</strong>?
            </p>
          </div>

          <div class="flex items-center justify-end space-x-3">
            <button
              @click="cancelAddExisting"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
              Cancel
            </button>
            <button
              @click="confirmAddExisting"
              class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
              Add User
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from "../../Shared/Layout.vue";
import Pagination from "../../Shared/Components/Pagination.vue";
import CreateUserModal from "./Components/CreateUserModal.vue";
import EditUserModal from "./Components/EditUserModal.vue";
import DeleteConfirmationModal from "./Components/DeleteConfirmationModal.vue";
import { router } from "@inertiajs/vue3";

export default {
  layout: Layout,
  components: {
    Pagination,
    CreateUserModal,
    EditUserModal,
    DeleteConfirmationModal,
  },
  props: {
    users: Object,
    roles: Array,
    filters: Object,
    showCreateModal: Boolean,
    showEditModal: Boolean,
    editingUser: Object,
    confirmExistingUser: Object,
  },
  data() {
    return {
      showCreateModal: this.showCreateModal || false,
      showEditModal: this.showEditModal || false,
      showDeleteModal: false,
      editingUser: this.editingUser || null,
      userToDelete: null,
      confirmExistingUser: this.confirmExistingUser || null,
      filterForm: {
        search: this.filters?.search || '',
        role: this.filters?.role || '',
      },
    };
  },
  methods: {
    applyFilters() {
      const params = {};

      if (this.filterForm.search) {
        params.search = this.filterForm.search;
      }

      if (this.filterForm.role) {
        params.role = this.filterForm.role;
      }

      router.get(route('treasurer.users.index'), params);
    },
    editUser(user) {
      this.editingUser = user;
      this.showEditModal = true;
    },
    confirmRemoveUser(user) {
      this.userToDelete = user;
      this.showDeleteModal = true;
    },
    removeUser() {
      if (this.userToDelete) {
        router.delete(route('treasurer.users.destroy', this.userToDelete.id));
      }
      this.showDeleteModal = false;
      this.userToDelete = null;
    },
    onUserCreated() {
      this.showCreateModal = false;
      router.reload();
    },
    onUserUpdated() {
      this.showEditModal = false;
      this.editingUser = null;
      router.reload();
    },
    confirmAddExisting() {
      if (this.confirmExistingUser) {
        router.post(route('treasurer.users.confirm-add-existing'), {
          user_id: this.confirmExistingUser.user.id,
          role: this.confirmExistingUser.role,
        });
      }
      this.confirmExistingUser = null;
    },
    cancelAddExisting() {
      this.confirmExistingUser = null;
    },
    getRoleBadgeColor(roleName) {
      const colors = {
        'treasurer': 'bg-blue-100 text-blue-800',
        'auditor': 'bg-purple-100 text-purple-800',
        'user': 'bg-green-100 text-green-800',
        'admin': 'bg-red-100 text-red-800'
      };
      return colors[roleName] || 'bg-gray-100 text-gray-800';
    },
  },
};
</script>
