<template>
    <Head title="Welcome" />

    <!-- Futuristic Header -->
    <header class="bg-gradient-to-r from-green-900 via-emerald-900 to-green-900 text-white py-4 shadow-2xl fixed top-0 left-0 right-0 z-50 backdrop-blur-md border-b border-green-500/20">
        <div class="container mx-auto flex items-center justify-between px-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-xl font-bold">₿</span>
                </div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">Finanx</h1>
            </div>
            <nav>
                <ul class="flex space-x-8 text-lg">
                    <li><a href="#" class="hover:text-emerald-400 transition-all duration-300 relative group">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 transition-all duration-300 group-hover:w-full"></span>
                    </a></li>
                    <li><a href="#features" class="hover:text-emerald-400 transition-all duration-300 relative group">
                        Features
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 transition-all duration-300 group-hover:w-full"></span>
                    </a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-all duration-300 relative group">
                        Analytics
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 transition-all duration-300 group-hover:w-full"></span>
                    </a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-all duration-300 relative group">
                        Contact
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 transition-all duration-300 group-hover:w-full"></span>
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section with Floating Elements -->
    <main class="relative min-h-screen flex items-center justify-center text-center px-6 pt-20">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-900 via-emerald-900 to-green-900">
            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.1) 1px, transparent 0); background-size: 60px 60px;"></div>
        </div>

        <!-- Floating Finance Icons -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-icon" style="top: 20%; left: 10%; animation-delay: 0s;">💰</div>
            <div class="floating-icon" style="top: 30%; right: 15%; animation-delay: 1s;">📈</div>
            <div class="floating-icon" style="top: 60%; left: 20%; animation-delay: 2s;">💎</div>
            <div class="floating-icon" style="top: 70%; right: 25%; animation-delay: 3s;">🏦</div>
            <div class="floating-icon" style="top: 40%; left: 80%; animation-delay: 4s;">📊</div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 max-w-5xl">
            <div class="mb-8">
                <h1 class="text-6xl md:text-8xl font-black mb-6 bg-gradient-to-r from-green-400 via-emerald-400 to-green-600 bg-clip-text text-transparent animate-pulse">
                    Future of Finance
                </h1>
                <p class="text-emerald-100 text-2xl md:text-3xl leading-relaxed font-light mb-8 max-w-4xl mx-auto">
                    Revolutionizing financial management with AI-powered insights, real-time analytics, and blockchain security
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                    <div class="text-4xl font-bold text-emerald-400 mb-2">$2.5B+</div>
                    <div class="text-emerald-100">Assets Managed</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                    <div class="text-4xl font-bold text-emerald-400 mb-2">50K+</div>
                    <div class="text-emerald-100">Active Users</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                    <div class="text-4xl font-bold text-emerald-400 mb-2">99.9%</div>
                    <div class="text-emerald-100">Uptime</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/login" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-full shadow-2xl hover:shadow-emerald-500/25 transition-all duration-300 transform hover:scale-105">
                    <span class="relative z-10">Launch Dashboard</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-green-700 to-emerald-700 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
                <a href="#features" class="group inline-flex items-center justify-center px-8 py-4 bg-transparent text-emerald-400 font-bold rounded-full border-2 border-emerald-400 hover:bg-emerald-400 hover:text-green-900 transition-all duration-300 transform hover:scale-105">
                    Explore Features
                </a>
            </div>
        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gradient-to-b from-green-900 to-green-800 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">
                    Next-Gen Features
                </h2>
                <p class="text-emerald-100 text-xl max-w-3xl mx-auto">
                    Experience the future of financial technology with our cutting-edge platform
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- AI Analytics -->
                <div class="group relative bg-gradient-to-br from-green-900/50 to-emerald-900/50 backdrop-blur-md rounded-3xl p-8 border border-green-500/20 hover:border-emerald-500/40 transition-all duration-500 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-600/10 to-emerald-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-2xl">🤖</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">AI-Powered Analytics</h3>
                        <p class="text-emerald-100 leading-relaxed">
                            Advanced machine learning algorithms provide predictive insights and automated financial recommendations
                        </p>
                    </div>
                </div>

                <!-- Real-time Trading -->
                <div class="group relative bg-gradient-to-br from-emerald-900/50 to-teal-900/50 backdrop-blur-md rounded-3xl p-8 border border-emerald-500/20 hover:border-teal-500/40 transition-all duration-500 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/10 to-teal-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-2xl">⚡</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Real-time Trading</h3>
                        <p class="text-emerald-100 leading-relaxed">
                            Execute trades with millisecond precision using our high-frequency trading infrastructure
                        </p>
                    </div>
                </div>

                <!-- Blockchain Security -->
                <div class="group relative bg-gradient-to-br from-green-800/50 to-emerald-800/50 backdrop-blur-md rounded-3xl p-8 border border-green-500/20 hover:border-emerald-500/40 transition-all duration-500 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-600/10 to-emerald-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-2xl">🔒</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Blockchain Security</h3>
                        <p class="text-emerald-100 leading-relaxed">
                            Military-grade encryption and decentralized ledger technology ensure maximum security
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 bg-gradient-to-b from-green-800 to-green-900">
        <div class="container mx-auto text-center px-6">
            <h2 class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">
                Ready to Transform?
            </h2>
            <p class="text-emerald-100 text-xl mb-12 max-w-3xl mx-auto">
                Join thousands of forward-thinking investors and institutions already using Finanx
            </p>
            <div class="bg-gradient-to-br from-green-800/50 to-green-700/50 backdrop-blur-md shadow-2xl rounded-3xl p-10 max-w-4xl mx-auto border border-green-500/20">
                <form action="#" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="text" placeholder="Your Name" class="w-full p-4 rounded-xl bg-green-700/50 border border-green-500/30 text-white placeholder-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300">
                        <input type="email" placeholder="Your Email" class="w-full p-4 rounded-xl bg-green-700/50 border border-green-500/30 text-white placeholder-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300">
                    </div>
                    <textarea placeholder="Tell us about your financial goals..." rows="6" class="w-full p-4 rounded-xl bg-green-700/50 border border-green-500/30 text-white placeholder-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300"></textarea>
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-4 px-12 rounded-full font-bold shadow-2xl hover:shadow-emerald-500/25 transition-all duration-300 transform hover:scale-105">
                        Start Your Journey
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-900 text-emerald-100 text-center py-12 border-t border-green-500/20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between mb-8">
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                        <span class="text-sm font-bold">₿</span>
                    </div>
                    <p class="text-lg font-semibold">Finanx - Future of Finance</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-emerald-400 transition-all duration-300">Privacy Policy</a>
                    <a href="#" class="hover:text-emerald-400 transition-all duration-300">Terms of Service</a>
                    <a href="#" class="hover:text-emerald-400 transition-all duration-300">Contact Us</a>
                </div>
            </div>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="#" class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center hover:bg-emerald-600 transition-all duration-300">
                    <span class="text-lg">📱</span>
                </a>
                <a href="#" class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center hover:bg-emerald-600 transition-all duration-300">
                    <span class="text-lg">💬</span>
                </a>
                <a href="#" class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center hover:bg-emerald-600 transition-all duration-300">
                    <span class="text-lg">📧</span>
                </a>
            </div>
            <p class="text-emerald-300">&copy; 2024 Finanx. All rights reserved. | Built for the future of finance.</p>
        </div>
    </footer>
</template>

<script setup>
// No background image needed for the new design
</script>

<style scoped>
.floating-icon {
    position: absolute;
    font-size: 2rem;
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

/* Additional animations */
@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
    }
    50% {
        box-shadow: 0 0 40px rgba(16, 185, 129, 0.8);
    }
}

.animate-pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite;
}
</style>
