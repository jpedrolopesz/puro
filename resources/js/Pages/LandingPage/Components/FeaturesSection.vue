<script setup lang="ts">
import { ref } from "vue";
import type { Feature } from "../Types";

const features = ref<Feature[]>([
    {
        title: "Easy Login and User Management",
        description:
            "We're all famililar with the pain of writing auth logic and the amount of use cases that come with it. I wanted to make it stupid simple and easy to use.",
        link: "/authentication",
        videoUrl: "https://essentials.supersaas.dev/essentials_passkeys.mp4",
        items: [
            { icon: "i-ph:password-duotone", text: "Email/Password" },
            { icon: "i-ph:numpad-duotone", text: "One-time Password" },
            { icon: "i-ph:link-simple-duotone", text: "Magic Link" },
            { icon: "i-logos:google-icon", text: "15+ Social Logins" },
            { icon: "i-ph:fingerprint-simple-duotone", text: "Passkeys" },
            {
                icon: "i-ph:user-circle-check-duotone",
                text: "Email Verification",
            },
            {
                icon: "i-ph:arrow-counter-clockwise-duotone",
                text: "Password Reset",
            },
            { icon: "i-ph:plugs-connected-duotone", text: "Link Multiple IDs" },
        ],
    },
    // ... outros features aqui
]);
</script>

<template>
    <section class="py-12 px-4 relative">
        <div class="relative max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center">
                <h2
                    class="mx-auto max-w-xl text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 font-display"
                >
                    Explore todos os recursos e veja como PuroSaaS pode acelerar
                    seu projeto SaaS!
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-gray-600 text-2xl">
                    I've purposely not added unnecessary things.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="mt-24 space-y-32">
                <!-- Feature sections -->
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="grid grid-cols-1 md:grid-cols-2 gap-8"
                >
                    <div>
                        <h3 class="text-xl font-semibold text-black">
                            {{ feature.title }}
                        </h3>
                        <p class="mt-4">{{ feature.description }}</p>

                        <!-- Code examples if they exist -->
                        <div
                            v-if="feature.codeExamples"
                            class="flex items-center gap-x-2 gap-y-1 flex-wrap"
                        >
                            <p
                                v-for="(
                                    code, codeIndex
                                ) in feature.codeExamples"
                                :key="codeIndex"
                                class="font-mono px-1 py-0.5 bg-gray-200 rounded text-sm mt-2 text-gray-700"
                            >
                                {{ code }}
                            </p>
                        </div>

                        <!-- Feature List -->
                        <ul
                            v-if="feature.items && feature.items.length > 0"
                            class="mt-6 grid grid-cols-2 gap-2 [&>li]:bg-gray-100 [&>li]:rounded-md [&>li]:p-2"
                        >
                            <li
                                v-for="(item, itemIndex) in feature.items"
                                :key="itemIndex"
                                class="flex items-center gap-2"
                            >
                                <span
                                    :class="['iconify', item.icon, 'h-6 w-6']"
                                    aria-hidden="true"
                                ></span>
                                {{ item.text }}
                            </li>
                        </ul>

                        <!-- Read More Link -->
                        <div v-if="feature.link" class="mt-8">
                            <router-link
                                :to="feature.link"
                                class="focus:outline-none focus-visible:outline-0 disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100 inline-flex items-center"
                            >
                                <span>Read more</span>
                                <span
                                    class="iconify i-ph:arrow-right flex-shrink-0 h-5 w-5"
                                    aria-hidden="true"
                                ></span>
                            </router-link>
                        </div>
                    </div>

                    <!-- Video Demo -->
                    <div
                        v-if="feature.videoUrl"
                        class="h-full w-full relative rounded-xl overflow-hidden ring-8 ring-gray-500/10"
                    >
                        <video
                            autoplay
                            loop
                            muted
                            playsinline
                            class="h-full w-full object-cover"
                        >
                            <source :src="feature.videoUrl" type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.grid > div {
    animation: fadeIn 0.5s ease-out forwards;
}

/* Stagger animation for multiple elements */
.grid > div:nth-child(1) {
    animation-delay: 0.1s;
}
.grid > div:nth-child(2) {
    animation-delay: 0.2s;
}

/* Ring hover effect */
.ring-gray-500\/10 {
    transition: all 0.3s ease;
}

.ring-gray-500\/10:hover {
    box-shadow: 0 0 0 8px rgba(107, 114, 128, 0.15);
}
</style>
