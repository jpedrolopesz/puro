<script setup lang="ts">
import { ref, computed, h } from "vue";
import { router } from "@inertiajs/vue3";
import type { Payments } from "../data/schema";

import { Button } from "@/Components/ui/button";
import { MixerHorizontalIcon, GearIcon } from "@radix-icons/vue";
import { useToast } from "@/Components/ui/toast/use-toast";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuItem,
    DropdownMenuCheckboxItem,
} from "@/Components/ui/dropdown-menu";
import { Switch } from "@/Components/ui/switch";

interface DataTableViewOptionsProps {
    table: Table<Payments>;
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
const processing = ref(false);
const progress = ref(0); // Estado para o progresso

const { toast } = useToast();

const startProcessing = async () => {
    processing.value = true;
    progress.value = 0;

    try {
        await router.post(route("processPayments"));

        showToast("Price Updated", "Price updated successfully.");

        const interval = setInterval(() => {
            progress.value += 5; // Incremento do progresso
            if (progress.value >= 100) {
                clearInterval(interval);
            }
        }, 100);

        await new Promise((resolve) => setTimeout(resolve, 2000));
    } catch (error) {
        console.error("Failed to process payments:", error);
    } finally {
        processing.value = false;
        progress.value = 1000;
    }
};

function showToast(title: string, description: string) {
    toast({
        title,
        description: h(
            "pre",
            {
                class: `mt-2 w-[340px] rounded-md ${title === "Error" ? "bg-red-500" : "bg-slate-950"} p-4`,
            },
            h("code", { class: "text-white" }, description),
        ),
    });
}
</script>

<template>
    <div class="flex space-x-2">
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <div class="flex space-x-2">
                    <Button
                        :class="[
                            'ml-auto hidden h-8 lg:flex relative',
                            {
                                ' ': processing.value,
                            },
                        ]"
                        size="sm"
                        :disabled="processing"
                    >
                        <div
                            v-if="processing"
                            class="absolute top-0 left-0 h-full rounded-sm transition-width duration-100 ease-linear"
                            :style="{
                                width: progress + '%',
                                backgroundColor: '#020617',
                            }"
                        ></div>

                        <GearIcon
                            :class="{
                                '': !processing,
                                'text-white z-20': processing,
                            }"
                            class="mr-2 h-4 w-4"
                        />
                        <span v-if="!processing">Sync</span>
                        <span v-else variant="ghost" class="z-20 font-bold"
                            >Processing...</span
                        >
                    </Button>
                </div>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-[250px]">
                <DropdownMenuLabel>Sync payments</DropdownMenuLabel>
                <DropdownMenuSeparator />

                <DropdownMenuItem class="flex items-center space-x-2">
                    <DropdownMenuLabel for="stripeSync"
                        >Stripe sync payments</DropdownMenuLabel
                    >
                    <Switch
                        id="syncPayments"
                        @click="startProcessing"
                        :disabled="processing"
                    />
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>

        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button
                    variant="outline"
                    size="sm"
                    class="ml-auto hidden h-8 lg:flex"
                >
                    <MixerHorizontalIcon class="mr-2 h-4 w-4" />
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
