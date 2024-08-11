<script setup lang="ts">
import type { Row } from "@tanstack/vue-table";
import { computed } from "vue";
import { Link, useForm } from "@inertiajs/vue3";

import { productSchema } from "../data/schema";
import type { Product } from "../data/schema";
import { DotsHorizontalIcon } from "@radix-icons/vue";

import { Button } from "@/Components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger,
    DropdownMenuLabel,
} from "@/Components/ui/dropdown-menu";

interface DataTableRowActionsProps {
    row: Row<Product>;
}
const props = defineProps<DataTableRowActionsProps>();

const productSchema = computed(() => productSchema.parse(props.row.original));
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                variant="ghost"
                class="flex h-8 w-8 p-0 data-[state=open]:bg-muted"
            >
                <DotsHorizontalIcon class="h-4 w-4" />
                <span class="sr-only">Open menu</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-[180px]">
            <DropdownMenuLabel>Connections</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem
                ><Link
                    :href="
                        route('product.details', {
                            productID: props.row.original.id,
                        })
                    "
                    >Edit Product</Link
                ></DropdownMenuItem
            >
        </DropdownMenuContent>
    </DropdownMenu>
</template>
