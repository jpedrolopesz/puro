<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";

import tasks from "./data/tasks.json";
import DataTable from "./Components/DataTable.vue";
import TenantNav from "./Components/TenantNav.vue";
import { columns } from "./Components/columns";

import { PropType, defineProps } from "vue";

interface Tenant {
    id: number;
    email: string;
    created_at: string;
    updated_at: string;
    data: string | null;
    name: string;
    tenancy_db_name: string;
}

interface TenantCollection {
    items: Tenant[];
    perPage: number;
    currentPage: number;
    path: string;
    query: any[];
    fragment: string | null;
    pageName: string;
    onEachSide: number;
    options: Record<string, any>;
    total: number;
    lastPage: number;
}

const props = defineProps<{
    tenants: TenantCollection;
}>();

//console.log(props.tenants.data);
</script>

<template>
    <AuthenticatedCentralLayout>
        <div class="hidden h-full flex-1 flex-col space-y-8 p-8 md:flex">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Tenants</h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>

            <ul>
                <li v-for="tenant in tenants.data" :key="tenant.id">
                    <p>Name: {{ tenant.name }}</p>
                    <p>Email: {{ tenant.email }}</p>
                    <p>Created At: {{ tenant.created_at }}</p>
                    <p>Updated At: {{ tenant.updated_at }}</p>
                    <p>Tenancy DB Name: {{ tenant.tenancy_db_name }}</p>
                    <p>Data: {{ tenant.data }}</p>
                </li>
            </ul>

            <DataTable :data="tasks" :columns="columns" />
        </div>
    </AuthenticatedCentralLayout>
</template>
