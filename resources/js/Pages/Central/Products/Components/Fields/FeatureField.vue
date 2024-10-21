<script setup lang="ts">
import { ref, watch } from "vue";
import { useField } from "vee-validate";
import { Plus, Trash2 } from "lucide-vue-next";
import { Button } from "@/Components/ui/button";
import {
    FormControl,
    FormField,
    FormItem,
    FormMessage,
} from "@/Components/ui/form";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";

const props = defineProps<{
    name: string;
    modelValue: string[];
}>();

const emit = defineEmits<{
    "update:modelValue": [value: string[]];
}>();

const features = ref(props.modelValue);

watch(
    features,
    (newValue) => {
        emit("update:modelValue", newValue);
    },
    { deep: true },
);

function addFeature() {
    features.value.push("");
}

function removeFeature(index: number) {
    features.value.splice(index, 1);
    if (features.value.length === 0) {
        features.value.push("");
    }
}
</script>

<template>
    <div class="space-y-2">
        <div class="flex justify-between items-center">
            <Label>Features</Label>
            <Button
                type="button"
                size="sm"
                variant="outline"
                @click="addFeature"
            >
                <Plus class="h-4 w-4" />
            </Button>
        </div>

        <div v-for="(feature, index) in features" :key="index" class="mt-2">
            <div class="flex items-center space-x-2">
                <FormField
                    :name="`${name}.${index}`"
                    v-slot="{ field, errorMessage }"
                >
                    <FormItem class="flex-grow">
                        <FormControl>
                            <Input
                                v-model="features[index]"
                                placeholder="Feature name"
                                :aria-invalid="!!errorMessage"
                                v-bind="field"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <Button
                    type="button"
                    variant="secondary"
                    @click="removeFeature(index)"
                    v-if="features.length > 1"
                    class="shrink-0"
                >
                    <Trash2 class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
</template>
