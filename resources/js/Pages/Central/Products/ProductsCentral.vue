<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps } from "vue";

import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

console.log(props.products);
</script>

<template>
    <Head title="Products" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Products</h2>
                </div>
            </div>

            <DataTable :data="props.products" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
