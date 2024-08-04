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
import { Label } from "@/Components/ui/label";
import { toast } from "@/Components/ui/toast";
import CurrencyCombobox from "./CurrencyCombobox.vue";
import RecurringIntervalCombobox from "./RecurringIntervalCombobox.vue";

const props = defineProps<{
    data: { currency: string }[];
}>();

console.log(props.data[0].currency);

watch(
    () => props.data[0]?.currency,
    (newCurrency) => {
        selectedCurrency.value = newCurrency || "";
    },
);
const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(255),
        description: z.string().max(255),
        price: z.number().min(0).max(255),
        currency: z.string({
            required_error: "Please select a currency.",
        }),
        recurring: z.string({
            required_error: "Please select a recurring.",
        }),
    }),
);
const { handleSubmit, resetForm } = useForm({
    validationSchema: formSchema,
});

const selectedCurrency = ref("usd"); // Defina o valor inicial se necessário

const onSubmit = handleSubmit(async (values) => {
    try {
        await router.post("plans/create", values, {
            onSuccess: () => {
                toast({
                    title: "Product Created",
                    description: h(
                        "pre",
                        { class: "mt-2 w-[340px] rounded-md bg-slate-950 p-4" },
                        h(
                            "code",
                            { class: "text-white" },
                            "Product created successfully.",
                        ),
                    ),
                });

                resetForm();
            },
            onError: (errors) => {
                toast({
                    title: "Error",
                    description: h(
                        "pre",
                        { class: "mt-2 w-[340px] rounded-md bg-red-500 p-4" },
                        h(
                            "code",
                            { class: "text-white" },
                            errors.message || "An error occurred.",
                        ),
                    ),
                });
            },
        });
    } catch (error) {
        // Exibe mensagem de erro em caso de exceção
        toast({
            title: "Error",
            description: h(
                "pre",
                { class: "mt-2 w-[340px] rounded-md bg-red-500 p-4" },
                h("code", { class: "text-white" }, error.message),
            ),
        });
    }
});
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
                            :value="props.data[0].unit_amount"
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
                            v-model="selectedCurrency"
                            v-bind="componentField"
                            :value="props.data[0].currency"
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
                            :value="props.data[0].recurring.interval"
                            v-bind="componentField"
                        />

                        <FormMessage />
                    </FormControl>
                </FormItem>
            </FormField>
        </div>

        <div>
            <Button class="mt-10" type="submit">Create Product</Button>
        </div>
    </form>
</template>
