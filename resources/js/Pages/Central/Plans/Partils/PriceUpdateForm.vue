<script setup lang="ts">
import { h, ref, watch, defineProps } from "vue";
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
import { Input } from "@/Components/ui/input";
import { toast } from "@/Components/ui/toast";
import CurrencyCombobox from "./CurrencyCombobox.vue";
import RecurringIntervalCombobox from "./RecurringIntervalCombobox.vue";

const props = defineProps<{
    data: {
        id: string;
        currency: string;
        unit_amount: number;
        recurring?: { interval: string };
        description?: string;
    }[];
}>();
const productId = props.data[0]?.id;
const formSchema = toTypedSchema(
    z.object({
        description: z.string().max(255).optional(),
        price: z.number().min(0).max(10000),
        currency: z.string().nonempty("Please select a currency."),
        recurring: z.string().nonempty("Please select a recurring."),
    }),
);

const { handleSubmit, resetForm, setValues, values } = useForm({
    validationSchema: formSchema,
    initialValues: {
        description: props.data[0]?.description || "",
        price: props.data[0]?.unit_amount || 0,
        currency: props.data[0]?.currency || "",
        recurring: props.data[0]?.recurring?.interval || "",
    },
});

watch(
    () => props.data[0],
    (data) => {
        if (data) {
            setValues({
                description: data.description || "",
                price: data.unit_amount || 0,
                currency: data.currency || "",
                recurring: data.recurring?.interval || "",
            });
        }
    },
    { immediate: true },
);

const onSubmit = handleSubmit(async (formValues) => {
    if (!productId) {
        showToast("Error", "Product ID is missing.");
        return;
    }

    try {
        await router.put(`/plans/${productId}`, formValues);
        showToast("Product Updated", "Product updated successfully.");
        resetForm();
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
            <FormField v-slot="{ componentField }" name="description">
                <FormItem>
                    <FormLabel>Price Description</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Enter product description"
                            v-model="values.description"
                            v-bind="componentField"
                        />
                    </FormControl>
                    <FormDescription class="text-xs">
                        Use to organize your prices. Not displayed to customers.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>
        </div>

        <div class="flex w-full">
            <FormField v-slot="{ componentField }" name="price">
                <FormItem>
                    <FormControl>
                        <Input
                            class="rounded-none rounded-l-md"
                            type="number"
                            v-model.number="values.price"
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
                        <CurrencyCombobox
                            v-model="values.currency"
                            v-bind="componentField"
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
                        <RecurringIntervalCombobox
                            v-model="values.recurring"
                            v-bind="componentField"
                        />
                        <FormMessage />
                    </FormControl>
                </FormItem>
            </FormField>
        </div>

        <div>
            <Button class="mt-10" type="submit">Update</Button>
        </div>
    </form>
</template>
