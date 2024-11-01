<script setup lang="ts">
import { h, defineProps, watch, ref } from "vue";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { router } from "@inertiajs/vue3";

import { Button } from "@/Components/ui/button";
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import FeatureField from "../Fields/FeatureField.vue";

import { Input } from "@/Components/ui/input";
import { toast } from "@/Components/ui/toast";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(255),
        description: z.string().max(255).optional(),
        active: z.string().nonempty("Please select a status."),
        features: z
            .array(z.string().min(1, "Feature name is required"))
            .min(1, "At least one feature is required"),
    }),
);

const features = ref([""]);

console.log(props.data);
const initialValues = ref({
    name: props.data.name || "",
    description: props.data.description || "",
    active: props.data.active ? "true" : "false",
    metadata: props.data.metadata || [""],
});

const { handleSubmit, resetForm, setValues, setFieldValue, values } = useForm({
    validationSchema: formSchema,
    initialValues: initialValues.value,
});

// Inicializa as features com os dados existentes
watch(
    () => props.data,
    (newData) => {
        if (newData && newData.features) {
            features.value = Array.isArray(newData.features)
                ? newData.features
                : Object.values(newData.features);
        }
    },
    { immediate: true, deep: true },
);

watch(
    features,
    (newFeatures) => {
        setFieldValue("features", newFeatures);
    },
    { deep: true },
);

const onSubmit = handleSubmit(async (formValues) => {
    try {
        const metadata = {};
        formValues.features.forEach((feature, index) => {
            metadata[`feature_${index + 1}`] = feature;
        });

        const submitData = {
            ...formValues,
            metadata,
        };

        await router.put(
            route("product.update", { productId: props.data.id }),
            submitData,
        );
        showToast("Product Updated", "Product updated successfully.");
    } catch (error) {
        showToast("Error", error.message || "An error occurred.");
    }
});

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
    <form class="w-full space-y-3" @submit="onSubmit">
        <div>
            <FormField v-slot="{ componentField }" name="name">
                <FormItem>
                    <FormLabel>Name</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Enter product name"
                            v-model="values.name"
                            v-bind="componentField"
                        />
                    </FormControl>
                    <FormDescription class="text-xs">
                        Nome do produto ou serviço, visível para os clientes.
                    </FormDescription>
                    <FormMessage class="" />
                </FormItem>
            </FormField>
        </div>
        <div>
            <FormField v-slot="{ componentField }" name="description">
                <FormItem>
                    <FormLabel>Description</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Enter product description"
                            v-model="values.description"
                            v-bind="componentField"
                        />
                    </FormControl>
                    <FormDescription class="text-xs">
                        Aparece no checkout, no portal do cliente e entre aspas.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>
        </div>

        <div>
            <FormField v-slot="{ componentField }" name="features">
                <FormItem>
                    <FormControl>
                        <FeatureField
                            v-model="features"
                            :name="componentField.name"
                        />
                        <FormMessage />
                    </FormControl>
                </FormItem>
            </FormField>
        </div>

        <div>
            <FormField v-slot="{ componentField }" name="active">
                <FormItem>
                    <FormLabel>Status</FormLabel>
                    <FormControl>
                        <Select v-model="values.active" v-bind="componentField">
                            <SelectTrigger
                                id="status"
                                aria-label="Select status"
                            >
                                <SelectValue
                                    :placeholder="
                                        values.active === 'true'
                                            ? 'Active'
                                            : 'Inactive'
                                    "
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="true">Active</SelectItem>
                                <SelectItem value="false">Inactive</SelectItem>
                            </SelectContent>
                        </Select>
                        <FormMessage />
                    </FormControl>
                </FormItem>
            </FormField>
        </div>

        <div>
            <Button class="mt-10" type="submit">Update Product</Button>
        </div>
    </form>
</template>
