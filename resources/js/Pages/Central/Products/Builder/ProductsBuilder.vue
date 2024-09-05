<script setup lang="ts">
import { defineProps } from "vue";

import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";
import Builder from "./Builder.vue";

interface Product {
    id: number;
    name: string;
}

const props = defineProps<{
    products: Product[];
}>();
</script>

<template>
    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Product Builder
                    </h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>
            <Builder :products="props.products" />
        </main>
    </AuthenticatedCentralLayout>
</template>
