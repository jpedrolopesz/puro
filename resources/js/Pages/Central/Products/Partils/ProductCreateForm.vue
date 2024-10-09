<script setup lang="ts">
import { h, ref } from "vue";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { router } from "@inertiajs/vue3";
import { Plus, Trash2 } from "lucide-vue-next";
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
import CurrencyCombobox from "./CurrencyCombobox.vue";
import RecurringIntervalCombobox from "./RecurringIntervalCombobox.vue";

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

const { handleSubmit, resetForm } = useForm({
    validationSchema: formSchema,
});
//sfs
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
    } catch (error) {
        showToast("Error", error.message || "An error occurred.");
    }
});

function addFeature() {
    features.value.push("");
}

function removeFeature(index: number) {
    features.value.splice(index, 1);
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
            <div class="flex justify-between items-center">
                <Label>Features</Label>
                <Button size="sm" variant="outline" @click="addFeature">
                    <Plus class="h-4 w-4" />
                </Button>
            </div>

            <div v-for="(feature, index) in features" :key="index" class="mt-2">
                <div class="flex items-center space-x-2">
                    <FormField
                        :name="`features[${index}]`"
                        v-slot="{ componentField }"
                    >
                        <FormItem class="flex-grow">
                            <FormControl>
                                <Input
                                    v-model="features[index]"
                                    placeholder="Feature name"
                                    v-bind="componentField"
                                />
                            </FormControl>
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
            <Button class="mt-10" type="submit">Create Product</Button>
        </div>
    </form>
</template>
