<script setup lang="ts">
import { defineEmits } from "vue";
import { useForm } from "@inertiajs/vue3";

import { MoreHorizontal } from "lucide-vue-next";
import { Button } from "@/Components/ui/button";
import { Switch } from "@/Components/ui/switch";
import { Label } from "@/Components/ui/label";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

const props = defineProps<{
    price: {
        id: string;
    };
    priceDefaultId: string;
}>();

const form = useForm({
    active: props.price.active,
    priceDefault: props.priceDefaultId,
});

const emit = defineEmits(["expand"]);

const handleExpand = () => {
    emit("expand");
};
function copy(id: string) {
    navigator.clipboard.writeText(id);
}

function handleChange(checked: boolean) {
    form.put(route("price.update", { priceId: props.price.id }), {
        active: checked,
        onSuccess: () => {
            emit("update");
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}
function handleDefaultPriceChange() {
    form.priceDefault = props.price.id; // Atualiza o valor localmente

    form.put(route("product.update.default", { priceId: props.price.id }), {
        priceDefault: form.priceDefault,
        onSuccess: () => {
            emit("update");
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
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
                    @click="handleChange"
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
                    @click="handleDefaultPriceChange"
                />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
