<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps, watch } from "vue";
import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
import { Label } from "@/Components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";

const props = defineProps<{
    products: any[];
    currentFilter: string;
}>();

const form = useForm({
    filter: props.currentFilter,
});

const filterOptions = [
    { label: "All", value: "all" },
    { label: "Active", value: "active" },
    { label: "Archived", value: "archived" },
];

watch(
    () => form.filter,
    (newValue) => {
        form.get(route("products.index"), {
            preserveState: true,
            preserveScroll: true,
        });
    },
);
</script>

<template>
    <Head title="Products" />
    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Products</h2>
                </div>
            </div>
            <div class="py-6">
                <RadioGroup
                    v-model="form.filter"
                    class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
                >
                    <div
                        v-for="option in filterOptions"
                        :key="option.value"
                        class="flex-1"
                    >
                        <RadioGroupItem
                            :id="option.value"
                            :value="option.value"
                            class="peer sr-only"
                        />
                        <Label
                            :for="option.value"
                            class="flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 ease-in-out text-center"
                            :class="{
                                'border-gray-800 bg-primary-50 text-primary-700':
                                    form.filter === option.value,
                                'border-gray-200 hover:border-gray-800 text-gray-700':
                                    form.filter !== option.value,
                            }"
                        >
                            {{ option.label }}
                        </Label>
                    </div>
                </RadioGroup>
            </div>
            <DataTable :data="props.products" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
