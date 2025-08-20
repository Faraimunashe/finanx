<template>
    <vue3-snackbar top right :duration="4000"></vue3-snackbar>
    <Head title="Login" />

    <!-- Futuristic Login Page -->
    <div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-green-900 via-emerald-900 to-green-900 overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.1) 1px, transparent 0); background-size: 60px 60px;"></div>

        <!-- Floating Finance Icons -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-icon" style="top: 15%; left: 10%; animation-delay: 0s;">💰</div>
            <div class="floating-icon" style="top: 25%; right: 15%; animation-delay: 1s;">📈</div>
            <div class="floating-icon" style="top: 70%; left: 20%; animation-delay: 2s;">💎</div>
            <div class="floating-icon" style="top: 80%; right: 25%; animation-delay: 3s;">🏦</div>
            <div class="floating-icon" style="top: 45%; left: 85%; animation-delay: 4s;">📊</div>
        </div>

        <!-- Main Login Container -->
        <div class="relative z-10 w-full max-w-md mx-6">
            <!-- Logo and Branding -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-2xl font-bold">₿</span>
                    </div>
                    <h1 class="text-4xl font-black bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">Finanx</h1>
                </div>
                <p class="text-emerald-100 text-lg font-light">Welcome back to the future of finance</p>
            </div>

            <!-- Login Form Card -->
            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-2xl">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-black text-white mb-2">Secure Login</h2>
                    <p class="text-emerald-100 text-sm">Access your financial dashboard</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-emerald-200 mb-3 group-focus-within:text-emerald-400 transition-colors duration-300">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-emerald-400">📧</span>
                            </div>
                            <input
                                type="email"
                                v-model="form.email"
                                id="email"
                                name="email"
                                placeholder="Enter your email"
                                class="w-full pl-12 pr-4 py-4 bg-white/10 border border-emerald-500/30 rounded-xl text-white placeholder-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-emerald-200 mb-3 group-focus-within:text-emerald-400 transition-colors duration-300">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-emerald-400">🔒</span>
                            </div>
                            <input
                                type="password"
                                v-model="form.password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                class="w-full pl-12 pr-4 py-4 bg-white/10 border border-emerald-500/30 rounded-xl text-white placeholder-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm"
                                required
                            >
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-emerald-200 hover:text-emerald-300 transition-colors duration-300 cursor-pointer">
                            <input type="checkbox" class="mr-2 w-4 h-4 text-emerald-600 bg-white/10 border-emerald-500/30 rounded focus:ring-emerald-500 focus:ring-2">
                            Remember me
                        </label>
                        <a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors duration-300 font-medium">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group relative w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-4 px-6 rounded-xl font-bold shadow-2xl hover:shadow-emerald-500/25 transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-3"
                    >
                        <div class="absolute inset-0 bg-gradient-to-r from-green-700 to-emerald-700 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg v-if="form.processing" class="relative z-10 animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span class="relative z-10">{{ form.processing ? 'Authenticating...' : 'Access Dashboard' }}</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-emerald-500/30"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-emerald-200">New to Finanx?</span>
                    </div>
                </div>

                <!-- Sign Up Link -->
                <div class="text-center">
                    <a href="#" class="inline-flex items-center justify-center w-full py-3 px-6 bg-transparent text-emerald-400 font-semibold rounded-xl border-2 border-emerald-400 hover:bg-emerald-400 hover:text-green-900 transition-all duration-300 transform hover:scale-105">
                        Create Account
                    </a>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="mt-8 text-center">
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md rounded-full px-4 py-2 border border-emerald-500/30">
                    <span class="text-emerald-400">🛡️</span>
                    <span class="text-emerald-100 text-sm font-medium">256-bit SSL Encryption</span>
                </div>
            </div>
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
                    text: 'Login successful! Welcome back to Finanx.',
                });
            }
        });

        watch(() => form.errors.error, (error) => {
            if (error) {
                snackbar.add({
                    type: 'error',
                    text: error ?? 'Authentication failed. Please check your credentials.',
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

<style scoped>
.floating-icon {
    position: absolute;
    font-size: 1.5rem;
    opacity: 0.3;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.3;
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 0.6;
    }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #14532d;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #22c55e, #10b981);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #16a34a, #059669);
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Input focus animations */
input:focus {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
}

/* Button hover effects */
button:hover:not(:disabled) {
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
}

/* Glassmorphism effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
</style>

