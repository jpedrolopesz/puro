<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head, router } from "@inertiajs/vue3";

import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
import { defineProps, ref, onMounted } from "vue";

const props = defineProps({
    paymentLists: {
        type: Object,
        required: true,
    },
});

const processing = ref(false);
const progress = ref(0);

const startProcessing = async () => {
    processing.value = true;
    try {
        // Faz a requisição POST para a rota de processamento
        await router.post(route("processPayments"));
        // Aqui você pode definir a lógica para o que fazer após o sucesso
    } catch (error) {
        console.error("Failed to process payments:", error);
        // Aqui você pode definir a lógica para o que fazer em caso de erro
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <Head title="Payments" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Payments</h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>
            <button
                @click="startProcessing"
                :disabled="processing"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                {{ processing ? "Processing..." : "Process Payments" }}
            </button>

            <DataTable :data="paymentLists" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
