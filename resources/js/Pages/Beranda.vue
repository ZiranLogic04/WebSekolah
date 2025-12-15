<template>
  <BerandaLayout>
    <!-- Hero Slider -->
    <HeroSlider :data="sections.hero?.content" />
    
    <!-- Feature Area -->
    <FeatureArea :data="sections.feature?.content" />
    
    <!-- About Area -->
    <AboutArea :data="sections.about?.content" />
    
    <!-- Counter Area -->
    <CounterArea :stats="stats" />
    
    <!-- Department Area (Program) -->
    <DepartmentArea :data="sections.program?.content" />
    
    <!-- Video Area -->
    <VideoArea :data="sections.video?.content" />
    
    <!-- Team / Guru Area -->
    <TeamArea :data="sections.team?.content" />
    
    <!-- Choose Area -->
    <ChooseArea :data="sections.choose?.content" />
    
    <!-- Portfolio Area -->
    <PortofolioArea :data="sections.portfolio?.content" />
    
    <!-- Event Area -->
    <EventArea :data="sections.event?.content" />
    
    <!-- Testimonial Area -->
    <TestimonialArea :data="sections.testimonial?.content" />
    
    <!-- Blog Area -->
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
    sections: Object,
    stats: Object 
});
</script>