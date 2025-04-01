<template>
    <Head title="Welcome" />
    <header class="bg-green-700 text-white py-6 shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto flex items-center justify-between px-6">
            <h1 class="text-3xl font-extrabold">Finanx</h1>
            <nav>
                <ul class="flex space-x-8 text-lg">
                    <li><a href="#" class="hover:text-green-300 transition-all">Home</a></li>
                    <li><a href="#features" class="hover:text-green-300 transition-all">Features</a></li>
                    <li><a href="#" class="hover:text-green-300 transition-all">Pricing</a></li>
                    <li><a href="#" class="hover:text-green-300 transition-all">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="relative h-screen flex items-center justify-center text-center px-6">
        <div class="absolute inset-0 bg-cover bg-center animate-fadeIn" :style="{ backgroundImage: `url(${background_image})` }">
            <div class="absolute inset-0 bg-green-900 bg-opacity-60"></div>
        </div>
        <div class="relative z-10 max-w-4xl">
            <p class="text-white text-3xl leading-relaxed font-semibold animate-slideInUp">
                Our platform offers comprehensive financial insights and reporting tools to help you make informed decisions.
            </p>
            <a href="/login" class="mt-8 inline-block bg-white text-green-700 px-8 py-4 rounded-full shadow-lg text-lg font-semibold hover:bg-green-100 transition-all animate-bounce">Get Started</a>
        </div>
    </main>

    <section id="features" class="bg-white py-20">
        <div class="container mx-auto grid md:grid-cols-3 gap-12 text-center px-6">
            <div class="p-8 bg-green-100 rounded-2xl shadow-xl hover:shadow-2xl transition-all">
                <img src="../../assets/img/rep.png" alt="Feature 1" class="mx-auto mb-6">
                <h3 class="text-3xl font-bold text-green-800">Accurate Reports</h3>
                <p class="text-gray-600 mt-4 text-lg">Generate detailed financial reports effortlessly with our advanced tools.</p>
            </div>
            <div class="p-8 bg-green-100 rounded-2xl shadow-xl hover:shadow-2xl transition-all">
                <img src="../../assets/img/sec.png" alt="Feature 2" class="mx-auto mb-6">
                <h3 class="text-3xl font-bold text-green-800">Data Security</h3>
                <p class="text-gray-600 mt-4 text-lg">We prioritize the security and confidentiality of your financial data.</p>
            </div>
            <div class="p-8 bg-green-100 rounded-2xl shadow-xl hover:shadow-2xl transition-all">
                <img src="../../assets/img/mob.png" alt="Feature 3" class="mx-auto mb-6">
                <h3 class="text-3xl font-bold text-green-800">Customizable Dashboard</h3>
                <p class="text-gray-600 mt-4 text-lg">Personalize your reporting dashboard to suit your specific needs.</p>
            </div>
        </div>
    </section>

    <section id="contact" class="py-24">
        <div class="container mx-auto text-center px-6">
            <h2 class="text-5xl font-extrabold text-green-800 mb-6">Get in Touch</h2>
            <p class="text-lg text-gray-700 mb-12 max-w-2xl mx-auto">We'd love to hear from you! Reach out to us with any questions or feedback.</p>
            <div class="bg-white shadow-2xl rounded-2xl p-10 max-w-3xl mx-auto">
                <form action="#" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="text" placeholder="Your Name" class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600">
                        <input type="email" placeholder="Your Email" class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <textarea placeholder="Your Message" rows="6" class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600"></textarea>
                    <button type="submit" class="bg-green-700 text-white py-4 px-10 rounded-full font-bold shadow-lg hover:bg-green-800 transition-all">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-green-900 text-white text-center py-12 mt-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <p class="text-lg">&copy; 2024 Financial Reporting. All rights reserved.</p>
                <div class="mt-6 md:mt-0">
                    <a href="#" class="mx-3 text-white hover:text-green-300 transition-all">Privacy Policy</a>
                    <a href="#" class="mx-3 text-white hover:text-green-300 transition-all">Terms of Service</a>
                    <a href="#" class="mx-3 text-white hover:text-green-300 transition-all">Contact Us</a>
                </div>
            </div>
            <div class="mt-8 flex justify-center space-x-6">
                <a href="#" class="text-white hover:text-green-300 transition-all"><i class="fab fa-facebook fa-2x"></i></a>
                <a href="#" class="text-white hover:text-green-300 transition-all"><i class="fab fa-twitter fa-2x"></i></a>
                <a href="#" class="text-white hover:text-green-300 transition-all"><i class="fab fa-linkedin fa-2x"></i></a>
            </div>
        </div>
    </footer>
</template>

<script setup>
import background_image from '../../assets/img/1.png';
</script>
