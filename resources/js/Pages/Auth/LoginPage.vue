<template>
    <vue3-snackbar top right :duration="4000"></vue3-snackbar>
    <Head title="Login" />
    <div class="bg-green-50 flex items-center justify-center h-screen">
        <div class="bg-white shadow-2xl rounded-2xl p-10 max-w-md w-full">
            <h2 class="text-4xl font-extrabold text-green-800 text-center mb-6">Login</h2>
            <p class="text-gray-600 text-center mb-8">Access your account to manage financial reports.</p>
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="email" class="block text-lg font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" v-model="form.email" id="email" name="email" placeholder="Enter your email" class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600" required>
                </div>
                <div>
                    <label for="password" class="block text-lg font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" v-model="form.password" id="password" name="password" placeholder="Enter your password" class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600" required>
                </div>
                <button type="submit" :disabled="form.processing" class="bg-green-700 text-white py-4 px-10 rounded-full font-bold shadow-lg w-full hover:bg-green-800 transition-all flex items-center justify-center gap-2">
                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Logging in...' : 'Login' }}</span>
                </button>
            </form>
            <p class="text-gray-600 text-center mt-6">Don't have an account? <a href="#" class="text-green-700 font-semibold hover:underline">Sign Up</a></p>
            <p class="text-gray-600 text-center mt-2"><a href="#" class="text-green-700 font-semibold hover:underline">Forgot Password?</a></p>
        </div>
    </div>
</template>


<script>
import { watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useSnackbar } from 'vue3-snackbar';

export default {
    setup() {
        const form = useForm({
            email: '',
            password: '',
        });

        const snackbar = useSnackbar();

        watch(() => form.wasSuccessful, (wasSuccessful) => {
            if (wasSuccessful) {
                snackbar.add({
                    type: 'success',
                    text: 'Login successful!',
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
            form.post('/login', {
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

