<script setup lang="ts">
import { ref } from "vue";
import type { Card } from "../Types";

const cards = ref<Card[]>([
    {
        title: "Always Fresh",
        description:
            "Our codebase is always current and up-to-date. I personally ensure that every piece of code is reviewed before it's published.",
        bgColor: "bg-rose-500",
    },
    // ... other cards
]);
</script>

<template>
    <section>
        <div
            class="relative max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12 px-4 lg:px-0"
        >
            <div
                v-for="(card, index) in cards"
                :key="card.title"
                :class="[
                    'rounded-2xl p-8 flex flex-col border-4 border-white metal-shadow text-white transition-all duration-500 ease-spring',
                    card.bgColor,
                    {
                        'translate-x-0 lg:translate-x-[80px] rotate-0 lg:rotate-2':
                            index === 0,
                        'translate-x-0 lg:translate-x-[40px] rotate-0 lg:-rotate-2':
                            index === 1,
                        'translate-x-0 lg:translate-x-[-40px] rotate-0 lg:rotate-2':
                            index === 2,
                        'translate-x-0 lg:translate-x-[-80px] rotate-0 lg:-rotate-2':
                            index === 3,
                    },
                ]"
            >
                <h3 class="text-4xl font-bold tracking-tight font-display mb-8">
                    {{ card.title }}
                </h3>
                <p class="mt-auto text-lg">{{ card.description }}</p>
            </div>
        </div>
    </section>
</template>
