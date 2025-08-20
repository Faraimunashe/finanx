<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Enter full name"
              />
              <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                {{ form.errors.name }}
              </div>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Enter email address"
              />
              <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                {{ form.errors.email }}
              </div>
            </div>

            <!-- Password (Optional) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Password (Leave blank to keep current)</label>
              <input
                v-model="form.password"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Enter new password"
              />
              <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                {{ form.errors.password }}
              </div>
            </div>

            <!-- Confirm Password (Optional) -->
            <div v-if="form.password">
              <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Confirm new password"
              />
            </div>

            <!-- Role -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
              <select
                v-model="form.role"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              >
                <option value="">Select Role</option>
                <option v-for="role in roles" :key="role.id" :value="role.name">
                  {{ role.display_name || role.name }}
                </option>
              </select>
              <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">
                {{ form.errors.role }}
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex items-center justify-end space-x-3 mt-6">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50"
            >
              <span v-if="form.processing">Updating...</span>
              <span v-else>Update User</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
  roles: Array,
});

const emit = defineEmits(['close', 'updated']);

const form = useForm({
  name: props.user?.name || '',
  email: props.user?.email || '',
  password: '',
  password_confirmation: '',
  role: props.user?.roles?.[0]?.name || '',
});

const submitForm = () => {
  form.put(route('treasurer.users.update', props.user.id), {
    onSuccess: () => {
      emit('updated');
    },
  });
};
</script>
