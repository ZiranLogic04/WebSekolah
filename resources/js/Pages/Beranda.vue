<template>
  <BerandaLayout>
    <!-- Hero Slider -->
    <HeroSlider />
    
    <!-- Feature Area -->
    <FeatureArea />
    
    <!-- About Area -->
    <AboutArea />
    
    <!-- Counter Area (TETAP DINAMIS) -->
    <CounterArea :stats="stats" />
    
    <!-- Department Area (Program) -->
    <DepartmentArea />
    
    <!-- Video Area -->
    <VideoArea />
    
    <!-- Team / Guru Area -->
    <TeamArea />
    
    <!-- Choose Area -->
    <ChooseArea />
    
    <!-- Portfolio Area -->
    <PortofolioArea />
    
    <!-- Event Area -->
    <EventArea />
    
    <!-- Testimonial Area -->
    <TestimonialArea />
    
    <!-- Blog Area (TETAP DINAMIS) -->
    <BlogArea :posts="posts" />
    
    <!-- Partner Area -->
    <PartnerArea />
  </BerandaLayout>
</template>

<script setup>
import BerandaLayout from '@/Layouts/BerandaLayout.vue'
import HeroSlider from '@/Components/Beranda/Partial/Slider.vue'
import FeatureArea from '@/Components/Beranda/FeatureArea.vue'
import AboutArea from '@/Components/Beranda/AboutArea.vue'
import CounterArea from '@/Components/Beranda/CounterArea.vue'
import DepartmentArea from '@/Components/Beranda/DepartmentArea.vue'
import VideoArea from '@/Components/Beranda/VideoArea.vue'
import TeamArea from '@/Components/Beranda/TeamArea.vue'
import ChooseArea from '@/Components/Beranda/ChooseArea.vue'
import PortofolioArea from '@/Components/Beranda/PortofolioArea.vue'
import EventArea from '@/Components/Beranda/EventArea.vue'
import TestimonialArea from '@/Components/Beranda/TestimonialArea.vue'
import BlogArea from '@/Components/Beranda/BlogArea.vue'
import PartnerArea from '@/Components/Beranda/PartnerArea.vue'


defineProps({
    posts: Array,
    stats: Object 
});
</script>