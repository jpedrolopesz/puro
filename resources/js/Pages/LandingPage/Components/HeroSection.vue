<script setup lang="ts">
import { ref } from "vue";
import type { User, Feature } from "../Types";

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

const features = ref<Feature[]>([
    {
        title: "Authentication",
        description: "Password, Passcode, Magic Link, Social login, Passkey.",
        icon: "LockIcon",
        bgColor: "bg-rose-500",
    },
    {
        title: "Database",
        description: "Supports major databases with Drizzle ORM.",
        icon: "DatabaseIcon",
        bgColor: "bg-teal-500",
    },
    {
        title: "Payments/Subscription",
        description: "Simple payment management with Stripe.",
        icon: "DatabaseIcon",
        bgColor: "bg-purple-500",
    },
    {
        title: "Multi-tenancy",
        description: "Simple payment management with Stripe.",
        icon: "DatabaseIcon",
        bgColor: "bg-sky-500",
    },
]);
</script>

<template>
    <section class="max-w-6xl mx-auto px-4 py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 place-items-center gap-16">
            <div>
                <h1
                    class="text-5xl sm:text-6xl font-bold tracking-tight text-gray-800 font-display"
                >
                    Build Your Own SaaS Quickly and Easily
                </h1>
                <div class="space-y-4 mt-6 text-lg text-gray-700 max-w-lg">
                    <p>
                        Built with
                        <span class="underline decoration-rose-500"
                            >Laravel</span
                        >,
                        <span class="underline decoration-green-500"
                            >Vue.js</span
                        >
                        and
                        <span class="underline decoration-purple-500"
                            >Inertia.js</span
                        >, and coming soon with
                        <span class="underline decoration-sky-500"
                            >React.js</span
                        >
                        .
                    </p>

                    <p>
                        Save time and focus on what matters: growing your
                        business. With PuroSaaS, you don’t have to start from
                        scratch! Get everything ready to launch your SaaS
                        without spending hours setting up the basics.
                    </p>
                </div>

                <div class="mt-8 relative">
                    <div class="max-w-md">
                        <a
                            href="https://store.supersaas.dev/buy"
                            class="shiny-shimmer bg-black text-white focus:ring-2 focus:ring-black/20 w-full block text-center text-base sm:text-lg px-5 py-3 rounded-lg"
                        >
                            Buy Now for $199
                        </a>
                    </div>
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
                            >148+ developers</span
                        >
                        shipping apps in production.
                    </p>
                </div>
                <!-- Feature Cards -->
            </div>
            <div>
                <ul class="divide-y divide-gray-200 [&>li]:py-4">
                    <li
                        v-for="feature in features"
                        :key="feature.title"
                        class="flex gap-4"
                    >
                        <div
                            :class="[
                                'rounded-xl h-14 w-14 flex-shrink-0 text-white flex items-center justify-center border-4 border-white metal-shadow',
                                feature.bgColor,
                            ]"
                        >
                            <component :is="feature.icon" class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="font-semibold">
                                {{ feature.title }}
                            </p>
                            <p class="mt-2 text-gray-600">
                                {{ feature.description }}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>
