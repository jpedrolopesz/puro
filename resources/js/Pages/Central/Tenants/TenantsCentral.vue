<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head } from "@inertiajs/vue3";

import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";

import { PropType, defineProps } from "vue";

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
        <main class="space-y-8">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Tenants</h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>

            <DataTable :data="tenantsLists" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
