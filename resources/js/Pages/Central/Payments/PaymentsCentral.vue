<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { HandCoins, HandHelping, IterationCw } from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Head, router, useForm } from "@inertiajs/vue3";
import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
import { defineProps, ref, watch } from "vue";

const props = defineProps<{
    payments: any[];
    statistics: any;
    currentFilter: string;
}>();

const form = useForm({
    filter: props.currentFilter,
});

const currentFilter = ref(props.currentFilter || "succeeded");

const filterOptions = [
    { label: "Succeeded", value: "succeeded", icon: HandCoins },
    { label: "All", value: "all", icon: HandCoins },
    { label: "Refunded", value: "refunded", icon: IterationCw },
    { label: "Failed", value: "failed", icon: IterationCw },
];

const updateFilter = (newFilter: string) => {
    router.get(
        route("payments.index"),
        { filter: newFilter },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

watch(currentFilter, (newValue) => {});
</script>

<template>
    <Head title="Payments" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Payments</h2>
                    <p class="text-muted-foreground">
                        Here's a list of your tasks for this month!
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

            <DataTable :data="props.payments" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
