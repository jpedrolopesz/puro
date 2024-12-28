<script setup lang="ts">
import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";

import { Head, useForm, router } from "@inertiajs/vue3";
import { defineProps, watch, ref } from "vue";
import DataTable from "../Components/DataTable/DataTable.vue";
import { columns } from "../Components/DataTable/Columns/productColumns";
import { Boxes, PackageCheck, PackageX } from "lucide-vue-next";
import { Card, CardHeader, CardTitle } from "@/Components/ui/card";

const props = defineProps<{
    products: any[];
    currentFilter: string;
}>();

const form = useForm({
    filter: props.currentFilter,
});
const currentFilter = ref(props.currentFilter || "active");

const filterOptions = [
    { label: "All", value: "all", icon: Boxes },
    { label: "Active", value: "active", icon: PackageCheck },
    { label: "Archived", value: "archived", icon: PackageX },
];

const updateFilter = (newFilter: string) => {
    currentFilter.value = newFilter;
    router.get(
        route("admin.products.index"),
        { filter: newFilter },
        {
            preserveScroll: true,
            replace: true,
        },
    );
};

watch(currentFilter, (newValue) => {});
</script>

<template>
    <Head title="Products" />
    <AuthenticatedCentralLayout>
        <main class="space-y-4 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Products</h2>
                    <p class="text-muted-foreground">
                        This page displays your products. If you wish to
                        rearrange them, you will need to click the 'Rearrange'
                        button.
                    </p>
                </div>
            </div>

            <div class="py-2">
                <div
                    class="flex flex-col items-center sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
                >
                    <div
                        v-for="option in filterOptions"
                        :key="option.value"
                        class="flex-1"
                    >
                        <button
                            class="w-full"
                            @click="updateFilter(option.value)"
                        >
                            <Card
                                :for="option.value"
                                class="py-2 cursor-pointer transition-all duration-200 ease-in-out"
                                :class="{
                                    'border-gray-800 ':
                                        form.filter === option.value,
                                    'border-gray-200 hover:border-gray-800 ':
                                        form.filter !== option.value,
                                }"
                            >
                                <CardHeader
                                    class="flex flex-row items-center justify-between space-y-0"
                                >
                                    <CardTitle class="text-md font-medium">
                                        {{ option.label }}
                                    </CardTitle>
                                    <component
                                        :is="option.icon"
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                </CardHeader>
                            </Card>
                        </button>
                    </div>
                </div>
            </div>
            <DataTable :data="props.products" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
