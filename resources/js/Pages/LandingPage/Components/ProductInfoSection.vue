<script setup lang="ts">
import { ref } from "vue";
import { Section } from "../Types";

const sections = ref<Section[]>([
    {
        title: "What's in the box?",
        icon: "i-ph:package-duotone",
        details: [
            {
                title: "The Codebase.",
                description:
                    " When you purchase an Supersaas license, you get invited to our Github repository. You will own access to the codebase for life, including future ehnancements and updates.",
            },
            {
                title: "The License.",
                description:
                    " A License Key with lifetime access to the codebase.",
            },
            {
                title: "Saas recipes (Coming soon).",
                description:
                    " I am planning on making regularly used saas recipes like Background Jobs, AI Image generation, Websockets, User onboarding, Realtime notifications using SSE, Caching data etc. Which you will get access to without any cost.",
            },
        ],
    },
    {
        title: "Made to ship fast",
        icon: "i-ph:rocket-launch-duotone",
        details: [
            {
                title: "Easy to customise.",
                description:
                    " Written in a way that makes it extremly easy to customise. No complex abstractions, open whatever file you like and just edit it directly.",
            },
            {
                title: "Thoroughly tested.",
                description:
                    " Every piece of code I write, I make sure to test it with a variety of inputs and edge cases.",
            },
            {
                title: "Less Opinionated.",
                description:
                    " I've not added my personal coding style, patterns and tried to keep it as generic as possible.",
            },
        ],
    },
    {
        title: "Lifetime access",
        icon: "i-ph:infinity-duotone",
        details: [
            {
                title: "No Subscriptions.",
                description:
                    " This is a one time purchase, we will never charge you for updates or subscriptions.",
            },
            {
                title: "Unlimited Projects.",
                description:
                    " Create as many projects as you like. Make saas apps, create freelance projects or just learn how to build a saas app from scratch.",
            },
            {
                title: "Free updates.",
                description:
                    " All future updates and releases will be free. You don't need to pay for updates.",
            },
        ],
    },
]);
</script>

<template>
    <section class="py-24 px-4 relative">
        <div class="relative max-w-6xl mx-auto">
            <div
                class="grid grid-cols-1 gap-x-12 gap-y-8 lg:gap-y-24 pb-20 xl:grid-cols-3"
            >
                <!-- Product Info Sections -->
                <template v-for="(section, index) in sections" :key="index">
                    <!-- Section Header -->
                    <div>
                        <h2
                            class="max-w-2xl text-4xl font-semibold tracking-tight text-gray-900 font-display flex justify-center gap-2"
                        >
                            <span
                                :class="['iconify', section.icon, 'w-10 h-10']"
                                aria-hidden="true"
                            ></span>
                            {{ section.title }}
                        </h2>
                    </div>

                    <!-- Section Content -->
                    <div
                        class="max-w-[54rem] text-lg leading-8 text-slate-600 col-span-2 prose"
                    >
                        <template
                            v-for="(detail, detailIndex) in section.details"
                            :key="detailIndex"
                        >
                            <p>
                                <strong>{{ detail.title }}</strong>
                                {{ detail.description }}
                            </p>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </section>
</template>

<style scoped>
.font-display {
    font-family:
        system-ui,
        -apple-system,
        sans-serif;
}

.prose {
    max-width: 54rem;
}

.prose p {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
}

.prose strong {
    color: #111827;
    font-weight: 600;
}

/* Animation for content appearance */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.prose p {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

/* Stagger animation for paragraphs */
.prose p:nth-child(1) {
    animation-delay: 0.1s;
}
.prose p:nth-child(2) {
    animation-delay: 0.2s;
}
.prose p:nth-child(3) {
    animation-delay: 0.3s;
}
</style>
