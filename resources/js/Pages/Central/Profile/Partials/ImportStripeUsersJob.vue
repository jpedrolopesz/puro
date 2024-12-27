<script setup lang="ts">
import { ref, onMounted, onUnmounted, h, watch } from "vue";
import { useToast } from "@/Components/ui/toast/use-toast";
import { RotateCw, Settings } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";

const isImporting = ref(false);
const importProgress = ref(0);
const importStatus = ref("");
const lastImportTime = ref<Date | null>(null);
const { toast } = useToast();

const startProcessing = async () => {
    if (isImporting.value) return;

    try {
        isImporting.value = true;
        importProgress.value = 0;
        importStatus.value = "Starting import...";
        showToast("Import Started", "The import process has begun.");

        await router.post(route("admin.tenants.user.import"));

        lastImportTime.value = new Date();
    } catch (error) {
        console.error("Failed to start import:", error);
        resetImportState();
        showToast("Error", "Failed to start import. Please try again.");
    }
};

const resetImportState = () => {
    isImporting.value = false;
    importProgress.value = 0;
    importStatus.value = "";
};

onMounted(() => {
    window.Echo.channel("import-progress")
        .listen(
            ".import.progress",
            (event: { payload: { progress: number; status: string } }) => {
                const { payload } = event;
                isImporting.value = true;
                importProgress.value = payload.progress;
                importStatus.value = payload.status;
            },
        )
        .listen(".import.error", (event: { payload: { status: string } }) => {
            const { payload } = event;
            importStatus.value = `Error: ${payload.status}`;
            showToast("Error", `Import failed: ${payload.status}`);
            setTimeout(resetImportState, 3000);
        })
        .listen(".import.completed", (event: { payload: any }) => {
            importProgress.value = 100;
            importStatus.value = "Import completed";
            showToast(
                "Import Completed",
                "The import process has finished successfully.",
            );
            setTimeout(resetImportState, 3000);
        });

    const storedLastImportTime = localStorage.getItem("lastImportTime");
    if (storedLastImportTime) {
        lastImportTime.value = new Date(storedLastImportTime);
    }
});

onUnmounted(() => {
    window.Echo.leave("import-progress");
});

watch(lastImportTime, (newValue) => {
    if (newValue) {
        localStorage.setItem("lastImportTime", newValue.toISOString());
    }
});

function showToast(title: string, description: string) {
    toast({
        title,
        description: h(
            "pre",
            {
                class: `mt-2 w-[340px] rounded-md ${title.toLowerCase().includes("error") ? "bg-red-500" : "bg-slate-950"} p-4`,
            },
            h("code", { class: "text-white" }, description),
        ),
    });
}
</script>

<template>
    <div class="flex space-x-2">
        <Button
            variant="outline"
            size="sm"
            class="ml-auto h-8 lg:flex relative overflow-hidden min-w-[120px]"
            :disabled="isImporting"
            @click="startProcessing"
        >
            <div
                class="absolute top-0 left-0 h-full bg-emerald-500 transition-all duration-300 ease-in-out"
                :style="{ width: `${importProgress}%` }"
            ></div>
            <div class="relative z-10 flex items-center justify-center w-full">
                <template v-if="!isImporting && importProgress === 0">
                    <Settings class="mr-2 h-4 w-4" />
                    <span>Sync Tenants Users</span>
                </template>
                <template v-else>
                    <RotateCw class="mr-2 h-4 w-4 animate-spin" />
                    <span class="text-xs whitespace-nowrap">
                        {{ importStatus }} ({{ Math.round(importProgress) }}%)
                    </span>
                </template>
            </div>
        </Button>
    </div>
</template>
