<script setup lang="ts">
import { h } from "vue";
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

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(255),
        description: z.string().max(255),
        price: z.number().min(0).max(255),
        currency: z.string().length(3).optional(), // Exemplo para códigos de moeda de 3 letras
        recurringInterval: z.string().optional(),
        recurringIntervalCount: z.number().min(1).optional(),
    }),
);

const { handleSubmit, resetForm } = useForm({
    validationSchema: formSchema,
});

const onSubmit = handleSubmit(async (values) => {
    try {
        // Envia os dados para o backend usando Inertia
        await router.post("/plans/create", values, {
            onSuccess: () => {
                // Exibe mensagem de sucesso
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

                // Reseta o formulário após o envio bem-sucedido
                resetForm();
            },
            onError: (errors) => {
                // Exibe mensagem de erro
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
    <form class="w-full space-y-2" @submit="onSubmit">
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

        <FormField v-slot="{ componentField }" name="price">
            <FormItem>
                <FormLabel>Price</FormLabel>

                <FormControl>
                    <div class="flex items-center mx-auto">
                        <div class="w-full">
                            <Input
                                class="rounded-none rounded-l-md w-full"
                                type="text"
                                placeholder="Enter currency code (e.g., USD)"
                            />
                        </div>
                        <div>
                            <CurrencyCombobox
                                v-slot="{ componentField }"
                                v-bind="componentField"
                                name="currency"
                            />
                        </div>
                    </div>

                    <FormMessage />
                </FormControl>
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="RecurringInterval">
            <FormItem>
                <FormLabel>Recurring Interval</FormLabel>

                <FormControl>
                    <RecurringIntervalCombobox v-bind="componentField" />

                    <FormMessage />
                </FormControl>
            </FormItem>
        </FormField>

        <Button type="submit">Create Product</Button>
    </form>
</template>
