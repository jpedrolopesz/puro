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
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["update"]);

const form = useForm({
    active: props.data.active,
});

function handleChange(checked: boolean) {
    form.put(route("price.update", { priceId: props.data.id }), {
        active: checked,
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
                :checked="props.data.active"
                @update:checked="handleChange"
            />
        </div>

        <div class="flex items-center space-x-2">
            <Label for="setdefault">Set default price</Label>
            <Switch
                id="setdefault"
                :checked="props.default_price"
                :disabled="props.default_price"
                @update:checked="(checked) => emit('update', checked)"
            />
        </div>
    </div>
</template>
