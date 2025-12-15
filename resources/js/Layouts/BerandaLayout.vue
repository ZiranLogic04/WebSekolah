<template>
    <div class="main-layout">
        <!-- header area -->
        <header class="header w-100">
            <HeaderTop />
            <Navbar />
        </header>
        <!-- header area end -->

        <!-- popup search -->
        <div class="search-popup">
            <button class="close-search"><span class="far fa-times"></span></button>
            <form action="#">
                <div class="form-group">
                    <input type="search" name="search-field" placeholder="Search Here..." required="">
                    <button type="submit"><i class="far fa-search"></i></button>
                </div>
            </form>
        </div>
        <!-- popup search end -->

        <main class="main">
            <slot />
        </main>

        <!-- footer area -->
        <Footer />
        <!-- footer area end -->

        <!-- scroll-top -->
        <a href="#" id="scroll-top" :class="{ active: showScrollTop }" @click.prevent="scrollToTop"><i class="fa fa-arrow-up"></i></a>
        <!-- scroll-top end -->
    </div>
</template>

<script setup>
import HeaderTop from '@/Components/Beranda/Partial/HeaderTop.vue';
import Navbar from '@/Components/Beranda/Partial/Navbar.vue';
import Footer from '@/Components/Beranda/Partial/Footer.vue';
import { onMounted, onUnmounted, ref } from 'vue';

const showScrollTop = ref(false);

const handleScroll = () => {
    showScrollTop.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style>
/* Force Global Header to be Relative (Not overlaying) */
.header {
    position: relative !important;
    background-color: #ffffff !important;
    z-index: 1000;
    width: 100%;
}
</style>
