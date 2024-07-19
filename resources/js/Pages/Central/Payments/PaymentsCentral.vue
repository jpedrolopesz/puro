<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head } from "@inertiajs/vue3";
import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
import { defineProps } from "vue";

const props = defineProps({
    paymentLists: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Payments" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Payments</h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>

            <DataTable :data="paymentLists" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
