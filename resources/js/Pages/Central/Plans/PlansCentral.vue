<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head } from "@inertiajs/vue3";

import plans from "./data/plans.json";
import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
</script>

<template>
    <Head title="Plans" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Plans</h2>
                </div>
            </div>

            <DataTable :data="plans" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
