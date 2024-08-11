<script setup lang="ts">
import type { Table } from "@tanstack/vue-table";
import { computed } from "vue";
import type { Order } from "../data/schema";
import { MixerHorizontalIcon, PlusIcon } from "@radix-icons/vue";
import ProductCreateForm from "../Partils/ProductCreateForm.vue";

import { Button } from "@/Components/ui/button";
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Separator } from "@/Components/ui/separator";

import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet";

interface DataTableViewOptionsProps {
    table: Table<Task>;
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
    <Sheet>
        <SheetTrigger
            ><Button size="sm" class="ml-auto h-8 mr-2">
                <PlusIcon class="mr-2 h-4 w-4" />
                Product
            </Button></SheetTrigger
        >
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Add Product</SheetTitle>
                <SheetDescription><Separator /></SheetDescription>
            </SheetHeader>

            <ProductCreateForm />
        </SheetContent>
    </Sheet>

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
                @update:checked="(value) => column.toggleVisibility(!!value)"
            >
                {{ column.id }}
            </DropdownMenuCheckboxItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
