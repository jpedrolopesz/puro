<script setup lang="ts">
import { h, ref } from "vue";
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
import CurrencyCombobox from "../CurrencyCombobox.vue";
import RecurringIntervalCombobox from "../RecurringIntervalCombobox.vue";

const props = defineProps({
    data: {
        type: Object as () => any,
        required: true,
    },
});
const formSchema = toTypedSchema(
    z.object({
        price: z.number().min(0).max(255),
        currency: z.string().nonempty("Please select a currency."),
        recurring: z.string().nonempty("Please select a recurring."),
        description: z.string().max(255).optional(),
    }),
);

console.log(props.data.id);

const selectedCurrency = ref();

const { handleSubmit, resetForm } = useForm({
    validationSchema: formSchema,
});

const onSubmit = handleSubmit(async (values) => {
    try {
        await router.post(route("plan.addPriceToProduct"), {
            product_id: props.data.id, // Adicione o ID do produto aqui
            price: values.price,
            currency: values.currency,
            recurring: values.recurring,
            description: values.description,
        });
        showToast("Price Created", "Price created successfully.");
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
                        <CurrencyCombobox
                            v-model="selectedCurrency"
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
                        <RecurringIntervalCombobox v-bind="componentField" />

                        <FormMessage />
                    </FormControl>
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
                        Use to organize your prices. Not displayed to customers.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>
        </div>

        <div>
            <Button class="mt-10" type="submit">Create Price</Button>
        </div>
    </form>
</template>
