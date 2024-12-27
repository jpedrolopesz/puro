<script setup lang="ts">
import type { Table } from "@tanstack/vue-table";
import { computed, ref } from "vue";
import type { Product } from "../../data/schema";
import { SlidersHorizontal, SendToBack, Plus } from "lucide-vue-next";

import ProductCreateForm from "../Forms/ProductCreateForm.vue";
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
import { Link } from "@inertiajs/vue3";

interface DataTableViewOptionsProps {
    table: Table<Product>;
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

const isOpen = ref(false);

const handleSuccess = () => {
    isOpen.value = false;
};
</script>

<template>
    <Sheet>
        <SheetTrigger
            ><Button size="sm" class="ml-auto h-8 mr-2">
                <Plus class="mr-2 h-4 w-4" />
                Product
            </Button></SheetTrigger
        >
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Add Product</SheetTitle>
                <SheetDescription><Separator /></SheetDescription>
            </SheetHeader>

            <ProductCreateForm @success="handleSuccess" />
        </SheetContent>
    </Sheet>

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
                @update:checked="(value) => column.toggleVisibility(!!value)"
            >
                {{ column.id }}
            </DropdownMenuCheckboxItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <Link :href="route('admin.products.builder')">
        <Button variant="outline" size="sm" class="ml-2 hidden h-8 lg:flex">
            <SendToBack class="mr-2 h-4 w-4" />
            Rearrange
        </Button>
    </Link>
</template>
