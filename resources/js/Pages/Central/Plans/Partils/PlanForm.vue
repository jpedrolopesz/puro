<script setup lang="ts">
import { h } from "vue";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { vAutoAnimate } from "@formkit/auto-animate/vue";

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

const formSchema = toTypedSchema(
    z.object({
        username: z.string().min(2).max(50),
    }),
);

const { handleSubmit } = useForm({
    validationSchema: formSchema,
});

const onSubmit = handleSubmit((values) => {
    toast({
        title: "You submitted the following values:",
        description: h(
            "pre",
            { class: "mt-2 w-[340px] rounded-md bg-slate-950 p-4" },
            h(
                "code",
                { class: "text-white text-xs" },
                JSON.stringify(values, null, 2),
            ),
        ),
    });
});
</script>

<template>
    <form class="w-full space-y-4 mt-10" @submit="onSubmit">
        <FormField v-slot="{ componentField }" name="username">
            <FormItem v-auto-animate>
                <FormLabel> Name (required) </FormLabel>
                <FormDescription class="text-xs">
                    Name of the product or service, visible to customers.
                </FormDescription>
                <FormControl>
                    <Input
                        type="text"
                        placeholder="name"
                        v-bind="componentField"
                    />
                </FormControl>

                <FormMessage />
            </FormItem>
        </FormField>
        <Button type="submit"> Submit </Button>
    </form>
</template>
