<script setup lang="ts">
import type { Table } from "@tanstack/vue-table";
import { computed } from "vue";
import type { Payments } from "../data/schema";

import DataTableViewOptions from "./DataTableViewOptions.vue";
import { Cross2Icon } from "@radix-icons/vue";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";

interface DataTableToolbarProps {
    table: Table<Payments>;
}

const props = defineProps<DataTableToolbarProps>();

const isFiltered = computed(
    () => props.table.getState().columnFilters.length > 0,
);
</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex flex-1 items-center space-x-2">
            <Input
                placeholder="Filter payments..."
                :model-value="
                    (table
                        .getColumn('description')
                        ?.getFilterValue() as string) ?? ''
                "
                class="h-8 w-[150px] lg:w-[250px]"
                @input="
                    table
                        .getColumn('description')
                        ?.setFilterValue($event.target.value)
                "
            />

            <Button
                v-if="isFiltered"
                variant="ghost"
                class="h-8 px-2 lg:px-3"
                @click="table.resetColumnFilters()"
            >
                Reset
                <Cross2Icon class="ml-2 h-4 w-4" />
            </Button>
        </div>
        <DataTableViewOptions :table="table" />
    </div>
</template>
