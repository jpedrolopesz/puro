<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head } from "@inertiajs/vue3";

import plans from "./data/plans.json";
import DataTable from "./Components/DataTable.vue";
import TenantNav from "./Components/PlanstNav.vue";
import { columns } from "./Components/columns";
</script>

<template>
    <Head title="Plans" />

    <AuthenticatedCentralLayout>
        <div class="hidden h-full flex-1 flex-col space-y-8 p-8 md:flex">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Plans</h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>

            <DataTable :data="plans" :columns="columns" />
        </div>
    </AuthenticatedCentralLayout>
</template>
