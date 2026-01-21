<template>
  <BerandaLayout>
    <!-- Hero Slider -->
    <HeroSlider :sliders="sliders" />
    
    <!-- Feature Area -->
    <FeatureArea :features="features" />
    
    <!-- About Area -->
    <AboutArea :about="about" />
    
    <!-- Counter Area -->
    <CounterArea :stats="stats" />
    
    <!-- Department Area (Program) -->
    <DepartmentArea :program="program" />
    
    <!-- Video Area -->
    <VideoArea :video="video" />
    
    <!-- Team / Guru Area -->
    <TeamArea :team="team" />
    
    <!-- Choose Area -->
    <ChooseArea :choose="choose" />
    
    <!-- Portfolio Area -->
    <PortofolioArea :portfolio="portfolio" />
    
    <!-- Event Area -->
    <EventArea :event="event" />
    
    <!-- Testimonial Area -->
    <TestimonialArea :section="testimonial" />
    
    <!-- Blog Area -->
    <BlogArea :posts="posts" />
    
    <!-- Partner Area -->
    <PartnerArea :section="partner" />
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
    stats: Object,
    sliders: Array,
    features: Array,
    about: Object,
    program: Object,
    video: Object,
    team: Object,
    choose: Object,
    portfolio: Object,
    event: Object,
    testimonial: Object,
    partner: Object,
})
</script>