<script setup lang="ts">
import { defineProps } from "vue";
import NavMenu from "./Components/NavMenu.vue";
import { Separator } from "@/Components/ui/separator";
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    title: {
        type: String,
        default: "Settings",
    },
    description: {
        type: String,
        default: "Manage your account settings.",
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6">
            <div class="space-y-0.5">
                <h2 class="text-2xl font-bold tracking-tight">{{ title }}</h2>
                <p class="text-muted-foreground">{{ description }}</p>
            </div>
            <Separator class="my-6" />
            <div
                class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0"
            >
                <aside class="-mx-4 lg:w-1/5">
                    <NavMenu />
                </aside>
                <div class="flex-1">
                    <div class="space-y-2">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
