<script setup lang="ts">
import DataTable from "./DataTable/DataTable.vue";
import { columns } from "./DataTable/Columns/userColumns";
import { UserSchema } from "../Types/userTypes";
import { Head } from "@inertiajs/vue3";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

const props = defineProps<{
    tenantUsers: UserSchema;
}>();
</script>

<template>
    <Head title="Customer Details" />

    <div>
        <Card>
            <CardHeader class="px-7">
                <CardTitle>Users</CardTitle>
                <CardDescription>
                    Here are the users within the tenant.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <DataTable :data="tenantUsers" :columns="columns" />
            </CardContent>
        </Card>
    </div>
</template>
