<template>
    <div class="min-h-screen bg-gray-100 font-sans text-gray-800">
        <NavBar />

        <main class="pt-24 px-6 max-w-7xl mx-auto">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script>
import NavBar from './NavBar.vue';
import Footer from './Footer.vue';

export default{
    components: {
        NavBar, Footer
    }
};
</script>
<style scoped>
    body {
        @apply bg-gray-100;
    }
</style>
