<script setup lang="ts">
import Header from "./Components/Header.vue";
import HeroSection from "./Components/HeroSection.vue";
import VideoSection from "./Components/VideoSection.vue";
import FeatureCards from "./Components/FeatureCards.vue";
import FeaturesSection from "./Components/FeaturesSection.vue";
import ProductInfoSection from "./Components/ProductInfoSection.vue";
import FaqSection from "./Components/FaqSection.vue";
import AboutMeCardSection from "./Components/AboutMeCardSection.vue";
import { ref, onMounted } from "vue";

const encodedSvg = ref("");

onMounted(() => {
    // O SVG precisa ser uma string
    const svgContent = `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" preserveAspectRatio="none">
        <defs>
          <filter id="paper-grain">
            <feTurbulence type="fractalNoise" baseFrequency="0.8" numOctaves="3" seed="1" result="noise"/>
            <feColorMatrix type="matrix" in="noise" values="0.1 0 0 0 0, 0 0.1 0 0 0, 0 0 0.1 0 0, 0 0 0 0.05 0.95" result="grainTexture"/>
          </filter>

          <filter id="paper-texture">
            <feTurbulence type="fractalNoise" baseFrequency="0.04" numOctaves="5" seed="2" result="noise"/>
            <feDiffuseLighting in="noise" lighting-color="#fff" surfaceScale="3">
              <feDistantLight azimuth="45" elevation="60"/>
            </feDiffuseLighting>
          </filter>

          <filter id="crumple-main">
            <feTurbulence type="turbulence" baseFrequency="0.015" numOctaves="3" seed="3" result="turbulence"/>
            <feDisplacementMap in="SourceGraphic" in2="turbulence" scale="25" xChannelSelector="R" yChannelSelector="G"/>
          </filter>

          <filter id="fold-shadows">
            <feTurbulence type="turbulence" baseFrequency="0.02" numOctaves="4" seed="4"/>
            <feGaussianBlur stdDeviation="2"/>
            <feComponentTransfer>
              <feFuncA type="linear" slope="0.3"/>
            </feComponentTransfer>
          </filter>

          <linearGradient id="paper-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#ffffff"/>
            <stop offset="50%" style="stop-color:#f8fafc"/>
            <stop offset="100%" style="stop-color:#f1f5f9"/>
          </linearGradient>
        </defs>

        <rect width="100%" height="100%" fill="url(#paper-gradient)"/>

        <rect width="100%" height="100%" fill="#ffffff" filter="url(#paper-texture)" opacity="0.9"/>

        <rect width="100%" height="100%" fill="#000000" filter="url(#paper-grain)" opacity="0.05"/>

        <g filter="url(#crumple-main)">
          <path d="M0 250 Q 250 230, 500 250 T 1000 250" stroke="#e2e8f0" stroke-width="0.8" fill="none"/>
          <path d="M0 500 Q 250 480, 500 500 T 1000 500" stroke="#e2e8f0" stroke-width="0.8" fill="none"/>
          <path d="M0 750 Q 250 730, 500 750 T 1000 750" stroke="#e2e8f0" stroke-width="0.8" fill="none"/>

          <path d="M200 0 Q 220 500, 200 1000" stroke="#e2e8f0" stroke-width="0.6" fill="none"/>
          <path d="M800 0 Q 780 500, 800 1000" stroke="#e2e8f0" stroke-width="0.6" fill="none"/>
        </g>

        <rect width="100%" height="100%" filter="url(#fold-shadows)" opacity="0.15"/>

        <g opacity="0.1" filter="url(#crumple-main)">
          <path d="M0 100 Q 500 80, 1000 100" stroke="#94a3b8" stroke-width="0.3" fill="none"/>
          <path d="M0 300 Q 500 320, 1000 300" stroke="#94a3b8" stroke-width="0.3" fill="none"/>
          <path d="M0 600 Q 500 580, 1000 600" stroke="#94a3b8" stroke-width="0.3" fill="none"/>
          <path d="M0 900 Q 500 920, 1000 900" stroke="#94a3b8" stroke-width="0.3" fill="none"/>
        </g>
      </svg>
    `;

    // Removendo espaços em branco extras e quebras de linha antes do encode
    const cleanSvgContent = svgContent.trim().replace(/\s+/g, " ");

    // Encode do SVG para base64
    encodedSvg.value = btoa(cleanSvgContent);
});
</script>

<template>
    <div class="relative">
        <!-- Background com SVG -->
        <div
            class="fixed inset-0 w-full h-full bg-no-repeat bg-cover bg-center transition-opacity duration-300"
            :style="`background-image: url('data:image/svg+xml;base64,${encodedSvg}')`"
            :class="{
                'opacity-30 dark:opacity-20': true, // Ajuste a opacidade conforme necessário
                'bg-blend-multiply': true, // Ajuda na mesclagem com o fundo
            }"
        ></div>

        <!-- Conteúdo -->
        <div class="relative z-10">
            <Header />
            <main class="min-h-screen">
                <svg
                    width="200"
                    height="200"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M20 50 C40 30, 80 70, 100 50 S160 70, 180 40"
                        fill="#f0f0f0"
                        stroke="#cccccc"
                        stroke-width="2"
                    />
                    <path
                        d="M30 80 Q50 60, 70 75 T110 85 Q90 95, 60 110 Z"
                        fill="#e0e0e0"
                        stroke="#b0b0b0"
                        opacity="0.8"
                    />
                    <ellipse
                        cx="100"
                        cy="130"
                        rx="30"
                        ry="10"
                        fill="#dcdcdc"
                        opacity="0.4"
                    />
                </svg>

                <HeroSection />
                <VideoSection />
                <AboutMeCardSection />
                <FeaturesSection />
                <FeatureCards />
                <ProductInfoSection />
                <FaqSection />
            </main>
            <footer
                class="bg-gray-900 rounded-3xl overflow-hidden relative"
            ></footer>
        </div>
    </div>
</template>
