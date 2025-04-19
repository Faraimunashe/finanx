<template>
    <div class="fixed inset-0 z-50 flex justify-end bg-black/50" @click.self="$emit('close')">
      <div class="w-full sm:w-1/2 bg-white h-full p-8 shadow-xl overflow-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold text-gray-800">Create New Account</h2>
          <button @click="$emit('close')" class="text-gray-400 hover:text-red-600">
            <i class="fas fa-times text-lg"></i>
          </button>
        </div>

        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block text-gray-600 text-sm mb-1">Name</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-emerald-500" required />
          </div>

          <div class="mb-6">
            <label class="block text-gray-600 text-sm mb-1">Type</label>
            <select v-model="form.type" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-emerald-500" required>
              <option value="Asset">Asset</option>
              <option value="Liability">Liability</option>
              <option value="Revenue">Revenue</option>
              <option value="Expense">Expense</option>
            </select>
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 shadow">
              Create
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script>
  import { reactive } from 'vue'
  import { router } from '@inertiajs/vue3'

  export default {
    emits: ['close', 'saved'],
    setup(_, { emit }) {
      const form = reactive({
        name: '',
        type: 'Asset',
      })

      function submit() {
        router.post('/accounts', form, {
          onSuccess: () => {
            emit('close')
            emit('saved')
          },
        })
      }

      return {
        form,
        submit,
      }
    },
  }
  </script>
