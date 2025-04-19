<template>
    <vue3-snackbar top right :duration="4000"></vue3-snackbar>
    <transition name="fade">
      <div v-if="visible" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-end">
        <transition name="slide">
          <div
            class="w-full sm:w-1/2 h-full bg-white shadow-xl overflow-y-auto flex flex-col"
            @click.stop
          >
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
              <h2 class="text-xl font-semibold text-gray-800">Create Organization</h2>
              <button @click="$emit('close')" class="text-gray-500 hover:text-red-600 text-xl">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <form @submit.prevent="submit" class="p-6 flex-1 flex flex-col justify-between">
              <div class="space-y-5">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                  <div class="relative">
                    <i class="fas fa-building absolute top-2.5 left-3 text-emerald-500"></i>
                    <input
                      type="text"
                      v-model="form.name"
                      placeholder="e.g. Acme Corp"
                      class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                  <div class="relative">
                    <i class="fas fa-map-marker-alt absolute top-2.5 left-3 text-emerald-500"></i>
                    <input
                      type="text"
                      v-model="form.address"
                      placeholder="123 Main Street"
                      class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                  <div class="relative">
                    <i class="fas fa-phone absolute top-2.5 left-3 text-emerald-500"></i>
                    <input
                      type="text"
                      v-model="form.phone"
                      placeholder="+263 77 000 0000"
                      class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <div class="relative">
                    <i class="fas fa-envelope absolute top-2.5 left-3 text-emerald-500"></i>
                    <input
                      type="email"
                      v-model="form.email"
                      placeholder="email@example.com"
                      class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition"
                    />
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="pt-6 mt-6 border-t border-gray-100 flex justify-end gap-3">
                <button :disabled="form.processing" type="submit" class="px-4 py-2 rounded-md text-sm text-white bg-emerald-600 hover:bg-emerald-700 shadow">
                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Creating organization...' : 'Create Organization' }}</span>
                </button>
              </div>
            </form>
          </div>
        </transition>
      </div>
    </transition>
</template>

<script>
import { watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useSnackbar } from 'vue3-snackbar';

export default {
    props: {
      visible: Boolean,
    },
    emits: ["close"],
    setup() {
        const form = useForm({
          name: "",
          address: "",
          phone: "",
          email: "",
        });

        const snackbar = useSnackbar();

        watch(() => form.wasSuccessful, (wasSuccessful) => {
            if(wasSuccessful){
                snackbar.add({
                    type: 'success',
                    text: 'Organization was created successfully',
                });
            }
        });

        watch(() => form.errors.error, (error) => {
            if (error) {
                snackbar.add({
                    type: 'error',
                    text: error ?? 'An error occurred. Please try again later!',
                });
            }
        });

        const submit = () => {
            form.post('/organizations', {
                preserveScroll: true,
                clearHistory: true,
                onFinish: () => form.reset(),
            });
        };

        return {
            form,
            submit,
        };
    },
};
</script>

<style scoped>
  .slide-enter-active,
  .slide-leave-active {
    transition: transform 0.4s ease;
  }
  .slide-enter-from {
    transform: translateX(100%);
  }
  .slide-enter-to {
    transform: translateX(0%);
  }
  .slide-leave-from {
    transform: translateX(0%);
  }
  .slide-leave-to {
    transform: translateX(100%);
  }

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  .fade-enter-to,
  .fade-leave-from {
    opacity: 1;
  }
</style>
