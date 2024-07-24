<script setup lang="ts">
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

import { defineProps } from "vue";

const props = defineProps<{
    intent: { client_secret: string };
}>();
</script>

<template>
    <div>
        <CardHeader>
            <CardTitle>Payment Method</CardTitle>
            <CardDescription>
                Add a new payment method to your account.
            </CardDescription>
        </CardHeader>
        <CardContent class="grid gap-6">
            <RadioGroup default-value="card" class="grid grid-cols-1 gap-2">
                <div>
                    <RadioGroupItem
                        id="card"
                        value="card"
                        class="peer sr-only"
                    />
                    <Label
                        for="card"
                        class="flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary [&:has([data-state=checked])]:border-primary"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            class="mb-3 h-6 w-6"
                        >
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <path d="M2 10h20" />
                        </svg>
                        Card
                    </Label>
                </div>
            </RadioGroup>
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input id="name" placeholder="First Last" />
            </div>
            <div class="grid gap-2">
                <Label for="number">Card number</Label>
                <Input id="number" placeholder="" />
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="grid gap-2">
                    <Label for="month">Expires</Label>
                    <Select>
                        <SelectTrigger id="month">
                            <SelectValue placeholder="Month" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="1"> January </SelectItem>
                            <SelectItem value="2"> February </SelectItem>
                            <SelectItem value="3"> March </SelectItem>
                            <SelectItem value="4"> April </SelectItem>
                            <SelectItem value="5"> May </SelectItem>
                            <SelectItem value="6"> June </SelectItem>
                            <SelectItem value="7"> July </SelectItem>
                            <SelectItem value="8"> August </SelectItem>
                            <SelectItem value="9"> September </SelectItem>
                            <SelectItem value="10"> October </SelectItem>
                            <SelectItem value="11"> November </SelectItem>
                            <SelectItem value="12"> December </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid gap-2">
                    <Label for="year">Year</Label>
                    <Select>
                        <SelectTrigger id="year">
                            <SelectValue placeholder="Year" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="i in 10"
                                :key="i"
                                :value="`${new Date().getFullYear() + i}`"
                            >
                                {{ new Date().getFullYear() + i }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid gap-2">
                    <Label for="cvc">CVC</Label>
                    <Input id="cvc" placeholder="CVC" />
                </div>
            </div>
        </CardContent>
        <CardFooter>
            <slot />
        </CardFooter>
    </div>

    <form id="payment-form">
        <div id="card-element"></div>
        <div id="card-error" role="alert"></div>
        <button :disabled="isProcessing">Pay</button>
    </form>
</template>
