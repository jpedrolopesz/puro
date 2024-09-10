<script setup lang="ts">
import type { Row } from "@tanstack/vue-table";
import { computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { productSchema } from "../data/schema";
import type { Product } from "../data/schema";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import { Button } from "@/Components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
    DropdownMenuLabel,
} from "@/Components/ui/dropdown-menu";

interface DataTableRowActionsProps {
    row: Row<Product>;
}

const props = defineProps<DataTableRowActionsProps>();
const productsSchema = computed(() => productSchema.parse(props.row.original));

function archiveProduct() {
    if (
        confirm(
            "Are you sure you want to archive this product? This will deactivate all associated prices.",
        )
    ) {
        router.patch(
            route("product.archive", { productId: props.row.original.id }),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    alert("Product archived successfully");
                    // Você pode querer atualizar a UI aqui para refletir o novo estado do produto
                },
                onError: (errors) => {
                    alert(
                        "Failed to archive product: " +
                            (errors.message || "Unknown error"),
                    );
                },
            },
        );
    }
}
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
            <DropdownMenuItem>
                <Link
                    :href="
                        route('product.details', {
                            productID: props.row.original.id,
                        })
                    "
                >
                    Edit Product
                </Link>
            </DropdownMenuItem>
            <DropdownMenuItem @click="archiveProduct">
                Archive
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
