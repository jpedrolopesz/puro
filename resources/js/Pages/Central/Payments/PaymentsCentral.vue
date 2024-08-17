<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columns";
import { defineProps, onMounted, ref } from "vue";

// Definição das props recebidas
const props = defineProps({
    paymentLists: {
        type: Object,
        required: true,
    },
});

// Ref para o progresso
const progress = ref<number | null>(null);

onMounted(() => {
    Echo.private("sync-payment")

        .listen("SyncPaymentStripeEvent", (event) => {
            console.log("Event received:", event);
        });

    Echo.join("sync-payment").here((users) => {
        console.log("Users online:", users);
    });
});

// Função para iniciar a sincronização
const startSync = async () => {
    console.log("Iniciando sincronismo...");
    try {
        const response = await router.post("/start-sync");
        console.log("Sincronismo iniciado com sucesso.", response);
    } catch (error) {
        console.error("Erro ao iniciar sincronismo:", error);
    }
};
</script>

<template>
    <Head title="Payments" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div>
                <button @click="startSync">Start Sync</button>
                Aqui
                <p v-if="progress !== null">Progress: {{ progress }}%</p>
            </div>
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Payments</h2>
                    <p class="text-muted-foreground">
                        Here's a list of your tasks for this month!
                    </p>
                </div>
            </div>

            <DataTable :data="paymentLists" :columns="columns" />
        </main>
    </AuthenticatedCentralLayout>
</template>
