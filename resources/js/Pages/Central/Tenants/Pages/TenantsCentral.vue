<script setup lang="ts">
import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import DataTable from "../Components/DataTable/DataTable.vue";
import { columns } from "../Components/DataTable/Columns/tenantColumns";
import { defineProps, ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    tenantsLists: {
        type: Object,
        required: true,
    },
});

const isImporting = ref(false);
const importProgress = ref(0);
const importStatus = ref("");

const startProcessing = async () => {
    try {
        isImporting.value = true;
        importProgress.value = 0;
        importStatus.value = "Starting import...";
        await router.post(route("userTenant.import"));
    } catch (error) {
        console.error("Failed to start import:", error);
        isImporting.value = false;
        importStatus.value = "Failed to start import";
    }
};

let echo: any;

onMounted(() => {
    echo = window.Echo.channel("import-progress")
        .listen(
            ".import.progress",
            (event: {
                eventName: string;
                payload: { progress: number; status: string };
            }) => {
                const { payload } = event;
                if (payload.progress !== undefined) {
                    importProgress.value = payload.progress;
                }
                if (payload.status) {
                    importStatus.value = payload.status;
                }
                if (importProgress.value >= 100) {
                    isImporting.value = false;
                    importStatus.value = "Import completed";
                }
            },
        )
        .listen(
            ".import.error",
            (event: { eventName: string; payload: { status: string } }) => {
                const { payload } = event;
                isImporting.value = false;
                importStatus.value = `Error: ${payload.status}`;
            },
        );
});

onUnmounted(() => {
    if (echo) {
        echo.stopListening(".import.progress");
        echo.stopListening(".import.error");
    }
});
</script>

<template>
    <Head title="Tenants" />
    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Tenants</h2>
                    <p class="text-muted-foreground">
                        Here's a list of your tasks for this month!
                    </p>
                </div>
                <div>
                    <button
                        @click="startProcessing"
                        :disabled="isImporting"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:bg-gray-400"
                    >
                        {{ isImporting ? "Importing..." : "Import Users" }}
                    </button>
                </div>
            </div>
            <div v-if="isImporting || importProgress > 0" class="space-y-2">
                <div
                    class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"
                >
                    <div
                        class="bg-blue-600 h-2.5 rounded-full"
                        :style="{ width: `${importProgress}%` }"
                    ></div>
                </div>
                <p class="text-sm text-gray-600">{{ importStatus }}</p>
            </div>
            <DataTable :data="tenantsLists" :columns="columns" type="tenant" />
        </main>
    </AuthenticatedCentralLayout>
</template>
