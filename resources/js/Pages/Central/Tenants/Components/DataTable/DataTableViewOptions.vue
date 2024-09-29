<script setup lang="ts">
import { ref, onMounted, onUnmounted, h, watch, computed } from "vue";
import { useToast } from "@/Components/ui/toast/use-toast";
import type { Table } from "@tanstack/vue-table";
import type { TenantDetails } from "../../Types/tenantTypes";

import {
    RotateCw,
    Settings,
    Download,
    ChevronDown,
    SlidersHorizontal,
} from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuPortal,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger,
    DropdownMenuCheckboxItem,
} from "@/Components/ui/dropdown-menu";

interface DataTableViewOptionsProps {
    table: Table<TenantDetails>;
}

const props = defineProps<DataTableViewOptionsProps>();

const columns = computed(() =>
    props.table
        .getAllColumns()
        .filter(
            (column) =>
                typeof column.accessorFn !== "undefined" && column.getCanHide(),
        ),
);
const { toast } = useToast();
const isImporting = ref(false);
const importProgress = ref(0);
const importStatus = ref("");
const lastImportTime = ref<Date | null>(null);

const startProcessing = async () => {
    if (isImporting.value) return;

    try {
        isImporting.value = true;
        importProgress.value = 0;
        importStatus.value = "Starting import...";
        showToast("Import Started", "The import process has begun.");

        await router.post(route("userTenant.import"));
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
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="outline"
                    size="sm"
                    class="ml-auto h-8 lg:flex relative overflow-hidden min-w-[120px]"
                    :disabled="isImporting"
                >
                    <div
                        class="absolute top-0 left-0 h-full bg-emerald-500 transition-all duration-300 ease-in-out"
                        :style="{ width: `${importProgress}%` }"
                    ></div>
                    <div
                        class="relative z-10 flex items-center justify-center w-full"
                    >
                        <template v-if="!isImporting && importProgress === 0">
                            <Settings class="mr-2 h-4 w-4" />
                            <span>Sync</span>
                        </template>
                        <template v-else>
                            <RotateCw class="mr-2 h-4 w-4 animate-spin" />
                            <span class="text-xs whitespace-nowrap">
                                {{ importStatus }} ({{
                                    Math.round(importProgress)
                                }}%)
                            </span>
                        </template>
                    </div>
                    <ChevronDown class="ml-2 h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-[250px]">
                <DropdownMenuLabel>Import Users</DropdownMenuLabel>
                <DropdownMenuSeparator />

                <DropdownMenuGroup>
                    <DropdownMenuSub>
                        <DropdownMenuSubTrigger>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="1em"
                                height="1em"
                                viewBox="0 0 256 256"
                                class="mr-2 h-4 w-4"
                            >
                                <path
                                    fill="currentColor"
                                    d="M168 152c0 17.65-17.94 32-40 32s-40-14.35-40-32a8 8 0 0 1 16 0c0 8.67 11 16 24 16s24-7.33 24-16c0-9.48-8.61-13-26.88-18.26c-15.75-4.54-35.34-10.19-35.34-29.74c0-18.24 16.43-32 38.22-32c15.72 0 29.18 7.3 35.12 19a8 8 0 1 1-14.27 7.22C145.64 91.94 137.65 88 128 88c-12.67 0-22.22 6.88-22.22 16c0 7 9 10.1 23.77 14.36C145.78 123 168 129.45 168 152m56-104v160a16 16 0 0 1-16 16H48a16 16 0 0 1-16-16V48a16 16 0 0 1 16-16h160a16 16 0 0 1 16 16m-16 160V48H48v160z"
                                />
                            </svg>

                            <span>Stripe Users</span>
                        </DropdownMenuSubTrigger>
                        <DropdownMenuPortal>
                            <DropdownMenuSubContent>
                                <DropdownMenuItem
                                    :disabled="isImporting"
                                    @click="startProcessing"
                                >
                                    <Download class="mr-2 h-4 w-4" />
                                    <span>Import</span>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />

                                <DropdownMenuLabel
                                    v-if="lastImportTime"
                                    class="mt-2 text-xs text-gray-400"
                                >
                                    {{
                                        new Date(
                                            lastImportTime,
                                        ).toLocaleString()
                                    }}
                                </DropdownMenuLabel>
                            </DropdownMenuSubContent>
                        </DropdownMenuPortal>
                    </DropdownMenuSub>
                </DropdownMenuGroup>
                <DropdownMenuSeparator />
            </DropdownMenuContent>
        </DropdownMenu>

        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button
                    variant="outline"
                    size="sm"
                    class="ml-auto hidden h-8 lg:flex"
                >
                    <SlidersHorizontal class="mr-2 h-4 w-4" />
                    View
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-[150px]">
                <DropdownMenuLabel>Toggle columns</DropdownMenuLabel>
                <DropdownMenuSeparator />

                <DropdownMenuCheckboxItem
                    v-for="column in columns"
                    :key="column.id"
                    class="capitalize"
                    :checked="column.getIsVisible()"
                    @update:checked="
                        (value) => column.toggleVisibility(!!value)
                    "
                >
                    {{ column.id }}
                </DropdownMenuCheckboxItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>
