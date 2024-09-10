<script setup lang="ts">
import { defineEmits } from "vue";
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

interface Props {
    price: {
        id: string;
        active: boolean;
    };
    priceDefaultId: string | null;
}

const props = defineProps<Props>();
const emit = defineEmits(["expand", "update"]);

function copy(id: string) {
    navigator.clipboard.writeText(id);
}

const archiveForm = useForm({
    active: props.price.active,
});

function handlePriceArchivedChange(checked: boolean) {
    archiveForm.put(
        route("price.update.archived", { priceId: props.price.id }),
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
    priceDefault: props.priceDefaultId,
});

function handlePriceDefaultChange() {
    defaultPriceForm.priceDefault = props.price.id;
    defaultPriceForm.put(
        route("price.update.default", { priceId: props.price.id }),
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
                    :checked="!props.price.active"
                    :disabled="props.priceDefaultId === props.price.id"
                    @click="handlePriceArchivedChange"
                />
            </DropdownMenuItem>

            <DropdownMenuItem class="flex items-center space-x-2">
                <DropdownMenuLabel for="archiveprice"
                    >Set default price</DropdownMenuLabel
                >
                <Switch
                    id="setdefault"
                    :checked="props.priceDefaultId === props.price.id"
                    :disabled="
                        props.priceDefaultId === props.price.id ||
                        !props.price.active
                    "
                    @click="handlePriceDefaultChange"
                />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
