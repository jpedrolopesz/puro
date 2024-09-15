<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import {
    HandCoins,
    Handshake,
    HandHelping,
    IterationCw,
    Speech,
    ScanEye,
} from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Head, router, useForm } from "@inertiajs/vue3";
import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
import { defineProps, ref, watch, computed } from "vue";

const props = defineProps<{
    payments: any[];
    statistics: any;
    currentFilter: string;
}>();

console.log(props.payments);

const form = useForm({
    filter: props.currentFilter,
});

const currentFilter = ref(props.currentFilter || "succeeded");

const filterOptions = computed(() => [
    {
        label: "All",
        value: "all",
        icon: HandCoins,
        statistic: props.statistics.total_payments,
    },
    {
        label: "Succeeded",
        value: "succeeded",
        icon: Handshake,
        statistic: props.statistics.succeeded_payments,
    },
    {
        label: "Failed",
        value: "failed",
        icon: HandHelping,
        statistic: props.statistics.failed_payments,
    },
    {
        label: "Refunded",
        value: "refunded",
        icon: IterationCw,
        statistic: props.statistics.refunded_payments,
    },
    {
        label: "Disputed",
        value: "disputed",
        icon: Speech,
        statistic: props.statistics.disputed_payments,
    },
    {
        label: "Uncaptured",
        value: "uncaptured",
        icon: ScanEye,
        statistic: props.statistics.uncaptured_payments,
    },
]);

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

watch(currentFilter, (newValue) => {
    updateFilter(newValue);
});
</script>

<template>
    <Head title="Payments" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Payments</h2>
                    <p class="text-muted-foreground">
                        Here's a summary of your payments
                    </p>
                </div>
            </div>

            <div class="py-2">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4"
                >
                    <div v-for="option in filterOptions" :key="option.value">
                        <button
                            class="w-full"
                            @click="currentFilter = option.value"
                        >
                            <Card
                                :for="option.value"
                                class="py-2 cursor-pointer transition-all duration-200 ease-in-out"
                                :class="{
                                    'border-gray-800 border-2 text-gray-800':
                                        currentFilter === option.value,
                                    'border-gray-200 hover:border-gray-800 text-muted-foreground':
                                        currentFilter !== option.value,
                                }"
                            >
                                <CardHeader
                                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                                >
                                    <CardTitle class="text-md font-medium">
                                        {{ option.label }}
                                    </CardTitle>
                                    <component
                                        :is="option.icon"
                                        class="h-4 w-4"
                                        :class="{
                                            'text-gray-800':
                                                currentFilter === option.value,
                                            ' text-muted-foreground':
                                                currentFilter !== option.value,
                                        }"
                                    />
                                </CardHeader>

                                <CardContent class="flex justify-start">
                                    <div
                                        class="text-xs font-semibold text-muted-foreground"
                                    >
                                        {{ option.statistic }}
                                    </div>
                                </CardContent>
                            </Card>
                        </button>
                    </div>
                </div>
            </div>

            <DataTable :data="payments" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
