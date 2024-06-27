<script setup lang="ts">
import { buttonVariants } from "@/Components/ui/button";
import { Link } from "@inertiajs/vue3";
import { cn } from "@/lib/utils";
import LinkAuth from "./LinkAuth.vue";
import { computed, defineProps } from "vue";

const props = defineProps<{
    title: String;
}>();
</script>

<template>
    <div
        class="container relative hidden h-svh flex-col items-center justify-center md:grid lg:max-w-none lg:grid-cols-2 lg:px-0"
    >
        <LinkAuth :isRegister="props.title" />

        <div
            class="relative hidden h-full flex-col bg-muted p-10 text-white dark:border-r lg:flex"
        >
            <div class="absolute inset-0 bg-zinc-900" />
            <div class="relative z-20 flex items-center text-lg font-medium">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    class="mr-2 h-6 w-6"
                >
                    <path
                        d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3"
                    />
                </svg>
                Acme Inc
            </div>
            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg">
                        &ldquo;This library has saved me countless hours of work
                        and helped me deliver stunning designs to my clients
                        faster than ever before.&rdquo;
                    </p>
                    <footer class="text-sm">Sofia Davis</footer>
                </blockquote>
            </div>
        </div>

        <slot />
    </div>
</template>
