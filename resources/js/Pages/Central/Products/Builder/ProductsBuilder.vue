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
                        Builder Rearrange
                    </h2>
                    <p class="text-muted-foreground">
                        On this page, you can rearrange the products, and the
                        end user will see them in the same order you have set.
                    </p>
                </div>
            </div>
            <Builder :products="props.products" />
        </main>
    </AuthenticatedCentralLayout>
</template>
