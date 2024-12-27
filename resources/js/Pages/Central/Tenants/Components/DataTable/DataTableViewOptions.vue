<script setup lang="ts">
import { ref, onMounted, onUnmounted, h, watch, computed } from "vue";
import { useToast } from "@/Components/ui/toast/use-toast";
import type { Table } from "@tanstack/vue-table";
import type { TenantDetails } from "../../Types/tenantTypes";

import { SlidersHorizontal } from "lucide-vue-next";

import { Button } from "@/Components/ui/button";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuLabel,
    DropdownMenuPortal,
    DropdownMenuSeparator,
    DropdownMenuCheckboxItem,
    DropdownMenuTrigger,
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
</script>

<template>
    <div class="flex space-x-2">
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
