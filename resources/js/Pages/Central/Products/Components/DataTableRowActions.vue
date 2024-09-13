<script setup lang="ts">
import type { Row } from "@tanstack/vue-table";
import { h, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { productSchema } from "../data/schema";
import type { Product } from "../data/schema";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import { toast } from "@/Components/ui/toast";

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
    if (props.row.original.has_subscription) {
        alert("Cannot archive a product with an active subscription.");
        return;
    }
    toggleProductArchived(true);
}

function unarchiveProduct() {
    toggleProductArchived(false);
}

function toggleProductArchived(archive: boolean) {
    const action = archive ? "archive" : "unarchive";

    if (
        confirm(
            `Are you sure you want to ${action} this product? This will ${action} all associated prices in Stripe.`,
        )
    ) {
        router.patch(
            route(archive ? "product.archive" : "product.unarchive", {
                productId: props.row.original.id,
            }),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    showToast(
                        `Product ${action}d`,
                        "Product updated successfully.",
                    );
                },
                onError: (errors) => {
                    showToast(
                        `Failed to ${action}d`,
                        errors.message || "An error occurred.",
                    );
                },
            },
        );
    }
}

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
                            productId: props.row.original.id,
                        })
                    "
                >
                    Edit Product
                </Link>
            </DropdownMenuItem>
            <DropdownMenuItem
                v-if="props.row.original.active"
                @click="archiveProduct"
            >
                Archive in Stripe
            </DropdownMenuItem>
            <DropdownMenuItem v-else @click="unarchiveProduct">
                Unarchive in Stripe
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
