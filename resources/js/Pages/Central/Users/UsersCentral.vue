<script setup lang="ts">
import tasks from "./data/tasks.json";
import DataTable from "./Components/DataTable.vue";
import UserNav from "./Components/UserNav.vue";
import { columns } from "./Components/columns";
import AuthenticatedLayout from "../../../Layouts/AuthenticatedLayout.vue";
</script>

<template>
    <AuthenticatedLayout>
        <div class="hidden h-full flex-1 flex-col space-y-8 p-8 md:flex">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Users</h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>
            <DataTable :data="tasks" :columns="columns" />
        </div>
    </AuthenticatedLayout>
</template>
