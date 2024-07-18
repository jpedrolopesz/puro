<script lang="ts" setup>
import { ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { Label } from "@/Components/ui/label";
import { Switch } from "@/Components/ui/switch";
import { Separator } from "@/Components/ui/separator";
import { Button } from "@/Components/ui/button";

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

const route = usePage();
const planSelect = ref(true);

const plansMonthly = ref([
    {
        id: 1,
        name: "Basic Plan",
        price: 10,
        slug: "month",
        stripe_id: "stripe_basic_monthly",
        detail: [
            { id: 1, name: "Access to basic features" },
            { id: 2, name: "5GB Cloud Storage" },
        ],
    },
    {
        id: 2,
        name: "Standard Plan",
        price: 20,
        slug: "month",
        stripe_id: "stripe_standard_monthly",
        detail: [
            { id: 1, name: "Access to standard features" },
            { id: 2, name: "10GB Cloud Storage" },
            { id: 3, name: "Priority Support" },
        ],
    },
    {
        id: 3,
        name: "Premium Plan",
        price: 30,
        slug: "month",
        stripe_id: "stripe_premium_monthly",
        detail: [
            { id: 1, name: "Access to all features" },
            { id: 2, name: "50GB Cloud Storage" },
            { id: 3, name: "Dedicated Support" },
        ],
    },
]);

const plansYearly = ref([
    {
        id: 4,
        name: "Basic Plan",
        price: 100,
        slug: "year",
        stripe_id: "stripe_basic_yearly",
        detail: [
            { id: 1, name: "Access to basic features" },
            { id: 2, name: "5GB Cloud Storage" },
        ],
    },
    {
        id: 5,
        name: "Standard Plan",
        price: 200,
        slug: "year",
        stripe_id: "stripe_standard_yearly",
        detail: [
            { id: 1, name: "Access to standard features" },
            { id: 2, name: "10GB Cloud Storage" },
            { id: 3, name: "Priority Support" },
        ],
    },
    {
        id: 6,
        name: "Premium Plan",
        price: 300,
        slug: "year",
        stripe_id: "stripe_premium_yearly",
        detail: [
            { id: 1, name: "Access to all features" },
            { id: 2, name: "50GB Cloud Storage" },
            { id: 3, name: "Dedicated Support" },
        ],
    },
]);

const currentPlan = ref({
    stripe_price: "stripe_standard_monthly",
});
</script>

<template>
    <div>
        <h3 class="text-lg font-medium">Plans</h3>
        <p class="text-sm text-muted-foreground">
            If you wish to keep using the application please choose a
            subscription plan below.
        </p>
    </div>

    <div class="mt-5 md:col-span-2 md:mt-0">
        <div class="flex items-center space-x-2">
            <Label for="airplane-mode">Monthly</Label>

            <Switch id="airplane-mode" @click="planSelect = !planSelect" />
            <Label for="airplane-mode">Annually</Label>
        </div>

        <div v-if="planSelect" class="my-5 grid grid-cols-3 gap-4">
            <Card v-for="plan in plansMonthly">
                <CardHeader>
                    <CardTitle> {{ plan.name }}</CardTitle>
                    <CardDescription>
                        Ideal for individuals that need a custom solution with
                        custom tools.</CardDescription
                    >
                </CardHeader>

                <CardContent class="grid gap-2">
                    <div class="text-slate-800 font-bold mb-4">
                        <span class="text-3xl"> ${{ plan.price }}</span>
                        <span class="text-slate-500 font-medium text-sm">
                            /{{ plan.slug }}</span
                        >
                    </div>

                    <div v-if="currentPlan !== null">
                        <Button
                            size="lg"
                            v-if="plan.stripe_id === currentPlan.stripe_price"
                            disabled
                        >
                            Active plan
                        </Button>

                        <Button
                            size="lg"
                            v-else-if="
                                plan.stripe_id !== currentPlan.stripe_price
                            "
                        >
                            <Link
                                href="
                                    route(
                                        'register.subscription',
                                        plan.id,
                                    )
                                "
                            >
                                Update
                            </Link>
                        </Button>
                    </div>

                    <Button v-else-if="currentPlan !== true">
                        <Link
                            size="lg"
                            v-if="plan.stripe_id"
                            href="
                                route('register.subscription', plan.id)
                            "
                        >
                            Choose plan
                        </Link>
                    </Button>

                    <Separator class="my-4" />

                    <div>
                        <div
                            v-for="detail in plan.detail"
                            class="mb-4 grid grid-cols-[25px_minmax(0,1fr)]"
                        >
                            <svg
                                class="w-3 h-3 shrink-0 fill-current text-emerald-500 mr-2"
                                viewBox="0 0 12 12"
                            >
                                <path
                                    d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z"
                                />
                            </svg>
                            <div class="space-y-1">
                                <p class="text-sm font-medium leading-none">
                                    {{ detail.name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
        <div v-else class="my-5 grid grid-cols-3 gap-4">
            <Card v-for="plan in plansYearly">
                <CardHeader>
                    <CardTitle> {{ plan.name }}</CardTitle>
                    <CardDescription>
                        Ideal for individuals that need a custom solution with
                        custom tools.</CardDescription
                    >
                </CardHeader>

                <CardContent class="grid gap-2">
                    <div class="text-slate-800 font-bold mb-4">
                        <span class="text-3xl"> ${{ plan.price }}</span>
                        <span class="text-slate-500 font-medium text-sm">
                            /{{ plan.slug }}</span
                        >
                    </div>

                    <div v-if="currentPlan !== null">
                        <Button
                            size="lg"
                            v-if="plan.stripe_id === currentPlan.stripe_price"
                            disabled
                        >
                            Active plan
                        </Button>

                        <Button
                            size="lg"
                            v-else-if="
                                plan.stripe_id !== currentPlan.stripe_price
                            "
                        >
                            <Link
                                href="
                                route(
                                    'register.subscription',
                                    plan.id,
                                )
                            "
                            >
                                Update
                            </Link>
                        </Button>
                    </div>

                    <Button v-else-if="currentPlan !== true">
                        <Link
                            size="lg"
                            v-if="plan.stripe_id"
                            href="
                            route('register.subscription', plan.id)
                        "
                        >
                            Choose plan
                        </Link>
                    </Button>

                    <Separator class="my-4" />

                    <div>
                        <div
                            v-for="detail in plan.detail"
                            class="mb-4 grid grid-cols-[25px_minmax(0,1fr)]"
                        >
                            <svg
                                class="w-3 h-3 shrink-0 fill-current text-emerald-500 mr-2"
                                viewBox="0 0 12 12"
                            >
                                <path
                                    d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z"
                                />
                            </svg>
                            <div class="space-y-1">
                                <p class="text-sm font-medium leading-none">
                                    {{ detail.name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
