<script setup lang="ts">
import SidebarNav from "./Components/SidebarNav.vue";
import { Separator } from "@/Components/ui/separator";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6 p-10 pb-16">
            <div class="space-y-0.5">
                <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
                <p class="text-muted-foreground">
                    Manage your account settings and set e-mail preferences.
                </p>
            </div>
            <Separator class="my-6" />
            <div
                class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0"
            >
                <aside class="-mx-4 lg:w-1/5">
                    <SidebarNav />
                </aside>
                <div class="flex-1 lg:max-w-2xl">
                    <div class="space-y-6">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
