<script setup lang="ts">
import Header from "./Components/Header.vue";
import HeroSection from "./Components/HeroSection.vue";
import VideoSection from "./Components/VideoSection.vue";
import FeatureCards from "./Components/FeatureCards.vue";
import FeaturesSection from "./Components/FeaturesSection.vue";
import ProductInfoSection from "./Components/ProductInfoSection.vue";
import FaqSection from "./Components/FaqSection.vue";
</script>

<template>
    <div>
        <Header />
        <main class="min-h-screen">
            <HeroSection />
            <VideoSection />
            <FeaturesSection />
            <FeatureCards />
            <ProductInfoSection />
            <FaqSection />
        </main>
        <footer
            class="bg-gray-900 rounded-3xl overflow-hidden relative"
        ></footer>
    </div>
</template>

<style>
.font-display {
    font-family:
        system-ui,
        -apple-system,
        sans-serif;
}

.metal-shadow {
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.shiny-shimmer {
    position: relative;
    overflow: hidden;
}

.shiny-shimmer::after {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.3) 50%,
        rgba(255, 255, 255, 0) 100%
    );
    transform: rotate(30deg);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    from {
        transform: rotate(30deg) translateX(-100%);
    }
    to {
        transform: rotate(30deg) translateX(100%);
    }
}

/* Other styles... */
</style>
