<script setup lang="ts">
import { Switch } from "@/Components/ui/switch";
import { Label } from "@/Components/ui/label";
import { defineProps, defineEmits } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    default_price: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(["update"]);

// Formulário para atualizar o preço
const form = useForm({
    active: props.data.active,
    default_price: props.default_price, // Inicializa com o valor atual
});

console.log("Initial default_price:", props.default_price);

// Função para atualizar o estado ativo do preço
function handleChange(checked: boolean) {
    form.put(route("plan.update", { priceId: props.data.id }), {
        active: checked,
        onSuccess: () => {
            emit("update");
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}

// Função para atualizar a propriedade default_price
function handleDefaultPriceChange() {
    form.default_price = props.data.id; // Atualiza o valor localmente

    console.log("Updating default_price to:", form.default_price);

    form.put(route("plan.update.default", { priceId: props.data.id }), {
        default_price: form.default_price,
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
    <div class="flex flex-col space-y-4">
        <div class="flex items-center space-x-2">
            <Label for="archiveprice">Archive price</Label>
            <Switch
                id="price-active"
                :checked="!props.data.active"
                :disabled="props.default_price === props.data.id"
                @click="handleChange"
            />
        </div>

        <div class="flex items-center space-x-2">
            <Label for="setdefault">Set default price</Label>
            <Switch
                id="setdefault"
                :checked="props.default_price === props.data.id"
                :disabled="
                    props.default_price === props.data.id || !props.data.active
                "
                @click="handleDefaultPriceChange"
            />
        </div>
    </div>
</template>
