<script setup lang="ts">
import { ref } from "vue";
import type { User } from "../Types";

const users = ref<User[]>([
    {
        name: "Fayaz Ahmed",
        avatar: "https://placehold.co/400x400?text=%2B140",
    },
    {
        name: "Gavin Zhou",
        avatar: "https://api.dicebear.com/9.x/glass/svg?seed=gavin",
    },
]);
</script>

<template>
    <div class="max-w-3xl mx-auto">
        <div class="text-center">
            <h2
                class="mx-auto max-w-xl text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 font-display"
            >
                It's a pleasure to have you here!
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-gray-600 text-2xl">
                Every step with you brings us closer to our dreams.
            </p>
        </div>
        <div class="p-8 space-y-6">
            <div class="flex items-start justify-between">
                <div class="space-y-1 mr-10">
                    <h1 class="prose prose-lg text-gray-700 space-y-4">
                        <p>E aí, beleza? 🇧🇷 (Hey, what's up?)</p>
                        <p>That's me in the photo, and my name is Joao 😄</p>

                        <p>
                            I'm not here to list all my skills or claim to have
                            the best or only amazing product; my mission is much
                            simpler: to help you reach your goals while I work
                            toward my own dreams.
                        </p>

                        <p>
                            I'd like you to know that every purchase represents
                            a step toward making my dreams a reality, and I'll
                            be forever grateful to have you as part of this
                            journey with me. 💙
                        </p>
                    </h1>
                </div>
                <img
                    class="h-32 w-32 rounded-3xl object-cover"
                    src="https://avatars.githubusercontent.com/u/67524586?v=4"
                    alt="João"
                />
            </div>

            <div class="flex items-center gap-4 mt-6">
                <div class="inline-flex flex-row-reverse justify-end">
                    <span
                        v-for="user in users"
                        :key="user.name"
                        class="relative inline-flex items-center justify-center flex-shrink-0 rounded-full h-6 w-6 text-xs ring-2 ring-white -me-1.5"
                    >
                        <img
                            :src="user.avatar"
                            :alt="user.name"
                            class="rounded-full h-6 w-6 text-xs"
                        />
                    </span>
                </div>
                <p class="text-sm text-gray-500 max-w-xs">
                    <span class="font-semibold text-primary-900"
                        >0+ people responsible</span
                    >
                    for making my dreams come true.
                </p>
            </div>
        </div>
    </div>
</template>
