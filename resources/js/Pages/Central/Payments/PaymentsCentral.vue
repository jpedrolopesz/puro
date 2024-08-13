<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import Echo from "laravel-echo";

import Pusher from "pusher-js"; // Adicione esta linha se ainda não estiver importado

import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
import { defineProps, onMounted, ref } from "vue";

const props = defineProps({
    paymentLists: {
        type: Object,
        required: true,
    },
});

const progress = ref<number | null>(null);

const startSync = async () => {
    console.log("Iniciando sincronismo...");
    try {
        await router.post("/start-sync");
        console.log("Sincronismo iniciado com sucesso.");
    } catch (error) {
        console.error("Erro ao iniciar sincronismo:", error);
    }
};

onMounted(() => {
    window.Echo.private("sync-payment").listen(
        "SyncPaymentStripeEvent",
        (event: { progress: number }) => {
            console.log("Recebido evento:", event);
            progress.value = event.progress;
        },
    );
});
</script>

<template>
    <Head title="Payments" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div>
                <button @click="startSync">Start Sync</button>
                <p v-if="progress !== null">Progress: {{ progress }}%</p>
            </div>
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Payments</h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>

            <DataTable :data="paymentLists" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
