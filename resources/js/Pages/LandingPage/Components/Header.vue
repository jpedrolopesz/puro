<script setup lang="ts">
import { ref } from "vue";
import type { NavigationItem } from "../Types";

const navigationItems = ref<NavigationItem[]>([
    { text: "Demo", link: "https://demo.supersaas.dev" },
    { text: "FAQ", link: "/#faq" },
    { text: "Docs", link: "/docs" },
]);

const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
</script>

<template>
    <header class="sticky top-0 z-50 bg-white shadow-sm">
        <div
            class="sm:px-6 lg:px-8 max-w-7xl mx-auto flex h-16 items-center justify-between px-4 transition-all"
        >
            <!-- Logo and Nav -->
            <div class="flex items-center gap-5">
                <router-link to="/" class="flex items-center gap-2">
                    <img class="h-8 w-auto" src="/logo.png" alt="Logo" />
                    <p class="text-lg sm:text-xl font-bold tracking-tight">
                        Super SaaS
                    </p>
                </router-link>

                <div
                    class="group bg-gray-100 relative hidden items-center gap-2 rounded-full px-3 py-2 text-xs text-slate-900 ring-1 ring-inset ring-black/[0.08] hover:bg-gray-50 hover:ring-black/[0.13] lg:flex"
                >
                    <span class="font-semibold">Discount Offer</span>
                    <svg
                        width="2"
                        height="2"
                        aria-hidden="true"
                        class="fill-slate-900"
                    >
                        <circle cx="1" cy="1" r="1"></circle>
                    </svg>
                    <span class="font-medium text-gray-500"
                        >Get $50 Off with code 50OFF</span
                    >
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden items-center gap-2 sm:flex">
                <router-link
                    v-for="item in navigationItems"
                    :key="item.text"
                    :to="item.link"
                    class="focus:outline-none focus-visible:outline-0 disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-1.5 text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                >
                    {{ item.text }}
                </router-link>

                <a
                    href="https://store.supersaas.dev/buy"
                    class="focus:outline-none focus-visible:outline-0 flex-shrink-0 font-medium rounded-full text-sm px-6 py-2 text-white bg-gray-900 hover:bg-gray-800 shiny-shimmer"
                >
                    Press
                    <span class="py-0.5 px-2 border border-white/30 rounded-md"
                        >B</span
                    >
                    to buy now for $199
                </a>
            </nav>

            <!-- Mobile Navigation -->
            <div class="sm:hidden flex items-center gap-2">
                <a
                    href="https://store.supersaas.dev/buy"
                    class="focus:outline-none focus-visible:outline-0 flex-shrink-0 font-medium rounded-md text-sm px-2.5 py-1.5 text-white bg-gray-900 hover:bg-gray-800"
                >
                    Buy Now for $199
                </a>

                <button
                    @click="toggleMobileMenu"
                    class="menu-toggle relative flex h-8 w-8 items-center justify-center rounded-full border bg-white hover:bg-gray-100"
                >
                    <div
                        class="menu-bar absolute h-[1.5px] w-[15px] translate-y-[-3px] bg-gray-700"
                    ></div>
                    <div
                        class="menu-bar absolute h-[1.5px] w-[15px] translate-y-[3px] bg-gray-700"
                    ></div>
                </button>
            </div>
        </div>
    </header>
</template>
