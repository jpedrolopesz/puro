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
    progress.value = 0;

    try {
        // Fazer uma solicitação para iniciar o processamento no backend
        await router.post(
            "/process-payments",
            {},
            {
                onSuccess: () => {
                    // Simulação de progresso para a barra de progresso
                    const interval = setInterval(() => {
                        if (progress.value >= 100) {
                            clearInterval(interval);
                            processing.value = false;
                        } else {
                            progress.value += 10;
                        }
                    }, 500);
                },
                onError: () => {
                    processing.value = false;
                    progress.value = 0;
                },
            },
        );
    } catch (error) {
        console.error("Error processing payments:", error);
        processing.value = false;
        progress.value = 0;
    }
};

let jobIds = ref([]);

onMounted(async () => {
    const response = await router.post("/process-payments");
    jobIds.value = response.data.job_ids;
});

const checkJobStatus = async (jobId) => {
    try {
        const interval = setInterval(async () => {
            const response = await fetch(`/api/jobs/${jobId}`);
            const jobData = await response.json();
            // Atualize o estado aqui com base nos dados recebidos
            if (jobData.status === "completed") {
                clearInterval(interval);
            }
        }, 2000);
    } catch (error) {
        console.error(error);
    }
};

// Chame checkJobStatus para cada jobId
jobIds.value.forEach(checkJobStatus);
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
