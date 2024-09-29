<script setup lang="ts">
import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import DataTable from "../Components/DataTable/DataTable.vue";
import { columns } from "../Components/DataTable/Columns/tenantColumns";
import { defineProps } from "vue";

const props = defineProps({
    tenantsLists: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Tenants" />
    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Tenants</h2>
                    <p class="text-muted-foreground">
                        Here's a list of your tasks for this month!
                    </p>
                </div>
            </div>
            <DataTable :data="tenantsLists" :columns="columns" type="tenant" />
        </main>
    </AuthenticatedCentralLayout>
</template>
