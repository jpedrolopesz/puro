<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { router } from "@inertiajs/vue3";
import { DialogClose } from "radix-vue";

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

// Custom Fields
import CurrencyField from "../Fields/CurrencyField.vue";
import RecurringField from "../Fields/RecurringField.vue";
import FeatureField from "../Fields/FeatureField.vue";

interface ProductFormData {
    name: string;
    description?: string;
    price: number;
    currency: string;
    recurring: string;
    features: string[];
}

const formSchema = toTypedSchema(
    z.object({
        name: z
            .string()
            .min(2, "Name must be at least 2 characters")
            .max(255, "Name is too long"),
        description: z.string().max(255, "Description is too long").optional(),
        price: z
            .number()
            .min(0, "Price cannot be negative")
            .max(999999, "Price is too high"),
        currency: z.string().nonempty("Please select a currency"),
        recurring: z.string().nonempty("Please select a recurring interval"),
        features: z
            .array(z.string().min(1, "Feature name is required"))
            .min(1, "At least one feature is required"),
    }),
);

const dialogCloseRef = ref<InstanceType<typeof DialogClose> | null>(null);
const selectedCurrency = ref<string>("");
const features = ref<string[]>([""]);

const { handleSubmit, resetForm, setFieldValue, values } =
    useForm<ProductFormData>({
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

const handleFeatures = {
    add: () => {
        features.value.push("");
    },
    remove: (index: number) => {
        features.value.splice(index, 1);
        if (features.value.length === 0) {
            features.value.push("");
        }
    },
};

const showToast = (title: string, description: string) => {
    toast({
        title,
        description: h(
            "pre",
            {
                class: `mt-2 w-[340px] rounded-md ${
                    title === "Error" ? "bg-red-500" : "bg-slate-950"
                } p-4`,
            },
            h("code", { class: "text-white" }, description),
        ),
    });
};

const onSubmit = handleSubmit(async (values) => {
    try {
        const metadata = values.features.reduce(
            (acc, feature, index) => ({
                ...acc,
                [`feature_${index + 1}`]: feature,
            }),
            {},
        );

        await router.post(route("product.create"), {
            ...values,
            metadata,
        });

        showToast("Success", "Product created successfully");
        resetForm();
        features.value = [""];
        dialogCloseRef.value?.$el.click();
    } catch (error) {
        showToast("Error", error.message || "An error occurred");
    }
});
</script>

<template>
    <form class="w-full space-y-3" @submit="onSubmit">
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
                <FormMessage />
            </FormItem>
        </FormField>

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

        <div>
            <Label>Price / Currency</Label>
            <div class="flex w-full">
                <FormField v-slot="{ componentField }" name="price">
                    <FormItem>
                        <FormControl>
                            <Input
                                class="rounded-none rounded-l-md"
                                type="number"
                                v-bind="componentField"
                                placeholder="0,00"
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
        </div>

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

        <FormField v-slot="{ componentField }" name="recurring">
            <FormItem>
                <FormLabel>Recurring Interval</FormLabel>
                <FormControl>
                    <RecurringField v-bind="componentField" />
                    <FormMessage />
                </FormControl>
            </FormItem>
        </FormField>

        <div>
            <DialogClose ref="dialogCloseRef" class="hidden" />
            <Button class="mt-10" type="submit">Create Product</Button>
        </div>
    </form>
</template>
