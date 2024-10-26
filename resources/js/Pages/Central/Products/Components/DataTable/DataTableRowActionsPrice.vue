<script setup lang="ts">
import type { Row } from "@tanstack/vue-table";
import { computed } from "vue"; // Adicionei o import do computed
import { useForm } from "@inertiajs/vue3";
import { MoreHorizontal } from "lucide-vue-next";
import { Button } from "@/Components/ui/button";
import { Switch } from "@/Components/ui/switch";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

// Define a interface para o tipo Price
interface Price {
    id: string;
    active: boolean;
}

interface Props {
    row: Row<{
        id: string;
        price: Price;
        priceDefaultId: string | null;
    }>;
}

const props = defineProps<Props>();
const emit = defineEmits(["expand", "update"]);

function copy(id: string) {
    navigator.clipboard.writeText(id);
}

// Certifique-se de que os dados existem antes de acessá-los
const price = computed(
    () => props.row.original?.price || { id: "", active: false },
);
const priceDefaultId = computed(
    () => props.row.original?.priceDefaultId || null,
);

const archiveForm = useForm({
    active: price.value.active,
});

function handlePriceArchivedChange(checked: boolean) {
    if (!price.value.id) return;

    archiveForm.put(
        route("price.update.archived", { priceId: price.value.id }),
        {
            active: checked,
            onSuccess: () => {
                emit("update");
                window.location.reload();
            },
            onError: (errors) => {
                console.error(errors);
            },
        },
    );
}

const defaultPriceForm = useForm({
    priceDefault: priceDefaultId.value,
});

function handlePriceDefaultChange() {
    if (!price.value.id) return;

    defaultPriceForm.priceDefault = price.value.id;
    defaultPriceForm.put(
        route("price.update.default", { priceId: price.value.id }),
        {
            onSuccess: () => {
                emit("update");
                window.location.reload();
            },
            onError: (errors) => {
                console.error(errors);
            },
        },
    );
}
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="w-8 h-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="w-4 h-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuItem @click="copy(price.id)">
                Copy payment ID
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem class="flex items-center space-x-2">
                <DropdownMenuLabel for="archiveprice"
                    >Archive price</DropdownMenuLabel
                >
                <Switch
                    id="price-active"
                    :checked="!price.active"
                    :disabled="priceDefaultId === price.id"
                    @click="handlePriceArchivedChange(!price.active)"
                />
            </DropdownMenuItem>
            <DropdownMenuItem class="flex items-center space-x-2">
                <DropdownMenuLabel for="archiveprice"
                    >Set default price</DropdownMenuLabel
                >
                <Switch
                    id="setdefault"
                    :checked="priceDefaultId === price.id"
                    :disabled="priceDefaultId === price.id || !price.active"
                    @click="handlePriceDefaultChange"
                />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
