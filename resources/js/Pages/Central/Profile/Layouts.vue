<script setup lang="ts">
import NavMenu from "./Components/NavMenu.vue";
import { Separator } from "@/Components/ui/separator";
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";

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
    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
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
                <div class="flex-1 lg:max-w-2xl">
                    <div class="space-y-6">
                        <slot />
                    </div>
                </div>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
