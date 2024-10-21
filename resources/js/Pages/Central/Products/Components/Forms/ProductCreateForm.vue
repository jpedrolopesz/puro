<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { router } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
    FormDescription,
} from "@/Components/ui/form";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { toast } from "@/Components/ui/toast";

import CurrencyField from "../Fields/CurrencyField.vue";
import RecurringField from "../Fields/RecurringField.vue";
import FeatureField from "../Fields/FeatureField.vue";
import { DialogClose } from "radix-vue";

const dialogCloseRef = ref<InstanceType<typeof DialogClose> | null>(null);

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(255),
        description: z.string().max(255).optional(),
        price: z.number().min(0).max(255),
        currency: z.string().nonempty("Please select a currency."),
        recurring: z.string().nonempty("Please select a recurring."),
        features: z
            .array(z.string().min(1, "Feature name is required"))
            .min(1, "At least one feature is required"),
    }),
);

const selectedCurrency = ref();
const features = ref([""]);

const { handleSubmit, resetForm, setFieldValue, values } = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: "",
        description: "",
        price: 0,
        currency: "",
        recurring: "",
        features: [""],
    },
});

watch(
    features,
    (newFeatures) => {
        setFieldValue("features", newFeatures);
    },
    { deep: true },
);

const onSubmit = handleSubmit(async (values) => {
    try {
        const metadata = {};
        values.features.forEach((feature, index) => {
            metadata[`feature_${index + 1}`] = feature;
        });

        const submitData = {
            ...values,
            metadata,
        };

        await router.post(route("product.create"), submitData);
        showToast("Product Created", "Product created successfully.");
        resetForm();
        features.value = [""];
        dialogCloseRef.value?.$el.click();
    } catch (error) {
        showToast("Error", error.message || "An error occurred.");
    }
});

function addFeature() {
    features.value.push("");
}

function removeFeature(index: number) {
    features.value.splice(index, 1);
    if (features.value.length === 0) {
        features.value.push("");
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
    <form class="w-full space-y-3" @submit="onSubmit">
        <div>
            <FormField v-slot="{ componentField }" name="name">
                <FormItem>
                    <FormLabel>Name</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Enter product name"
                            v-bind="componentField"
                        />
                    </FormControl>
                    <FormDescription class="text-xs">
                        This is the name of your product.
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
                            v-bind="componentField"
                        />
                    </FormControl>
                    <FormDescription class="text-xs">
                        Optional description for the product.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>
        </div>

        <div>
            <Label>Price / Currency</Label>
        </div>

        <div class="flex w-full">
            <FormField v-slot="{ componentField }" name="price">
                <FormItem>
                    <FormControl>
                        <Input
                            class="rounded-none rounded-l-md"
                            type="number"
                            v-bind="componentField"
                            placeholder=" 0,00"
                        />
                        <FormMessage />
                    </FormControl>
                </FormItem>
            </FormField>
            <FormField v-slot="{ componentField }" name="currency">
                <FormItem>
                    <FormControl>
                        <CurrencyField
                            v-model="selectedCurrency"
                            v-bind="componentField"
                        />

                        <FormMessage />
                    </FormControl>
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
            <FormField v-slot="{ componentField }" name="recurring">
                <FormItem>
                    <FormLabel>Recurring Interval</FormLabel>

                    <FormControl>
                        <RecurringField v-bind="componentField" />

                        <FormMessage />
                    </FormControl>
                </FormItem>
            </FormField>
        </div>

        <div>
            <DialogClose ref="dialogCloseRef" class="hidden" />
            <Button class="mt-10" type="submit">Create Product</Button>
        </div>
    </form>
</template>
