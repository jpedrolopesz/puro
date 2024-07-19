<script setup lang="ts">
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

interface Item {
    title: string;
    href: string;
}
const page = usePage();
const currentRoute = computed(() => page.url);

const sidebarNavItems: Item[] = [
    {
        title: "Profile",
        href: "/profile",
    },
    {
        title: "Account",
        href: "/profile/account",
    },
    {
        title: "Plans",
        href: "/profile/plans",
    },
];

function isActive(itemHref: string): boolean {
    return currentRoute.value.endsWith(itemHref);
}
</script>

<template>
    <nav class="flex lg:flex-col space-x-2 md:space-x-1 md:space-y-1">
        <Link
            v-for="item in sidebarNavItems"
            :key="item.title"
            :href="item.href"
            class="w-full text-left rounded-md py-2 px-4"
            :class="{
                'bg-muted text-gray-800 hover:bg-muted-600': isActive(
                    item.href,
                ),
                'text-gray-700 hover:bg-gray-100': !isActive(item.href),
            }"
        >
            {{ item.title }}
        </Link>
    </nav>
</template>
