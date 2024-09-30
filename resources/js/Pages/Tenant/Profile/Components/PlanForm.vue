<script lang="ts" setup>
import { ref, computed } from "vue";
import { Link, usePage, Head } from "@inertiajs/vue3";
import { Label } from "@/Components/ui/label";
import { Switch } from "@/Components/ui/switch";
import { Separator } from "@/Components/ui/separator";
import { Button } from "@/Components/ui/button";
import StripeCheckout from "@/Pages/Subscription/StripeCheckout.vue";

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

const props = defineProps({
    plans: {
        type: Array,
        required: true,
    },
});

const route = usePage();
const planSelect = ref(true);

const columnCount = ref(3);

const sortPlans = (plans) => {
    return plans.sort((a, b) => {
        const orderA = parseInt(a.metadata.order) || 0;
        const orderB = parseInt(b.metadata.order) || 0;
        return orderA - orderB;
    });
};

const plansMonthly = computed(() => {
    const sorted = sortPlans(props.plans);
    // Atualiza o columnCount com base no primeiro plano (assumindo que todos têm o mesmo valor)
    if (sorted.length > 0) {
        columnCount.value = parseInt(sorted[0].metadata.column_count) || 3;
    }
    return sorted.map((plan) => ({
        id: plan.id,
        name: plan.name,
        price:
            parseInt(
                plan.prices.find((p) => p.recurring?.interval === "month")
                    ?.unit_amount || 0,
            ) / 100,
        slug: "month",
        price_id: plan.metadata.monthly_price_id,
        detail: [
            { id: 1, name: plan.description },
            // Adicione mais detalhes conforme necessário
        ],
    }));
});

const plansYearly = computed(() => {
    const sorted = sortPlans(props.plans);
    return sorted.map((plan) => ({
        id: plan.id,
        name: plan.name,
        price:
            parseInt(
                plan.prices.find((p) => p.recurring?.interval === "year")
                    ?.unit_amount || 0,
            ) / 100,
        slug: "year",
        price_id: plan.metadata.yearly_price_id,
        detail: [
            { id: 1, name: plan.description },
            // Adicione mais detalhes conforme necessário
        ],
    }));
});

const currentPlan = ref({
    stripe_price: "price_1Lb6kvGQW0U1PfqxQFJJcEBN",
});
</script>

<template>
    <Head title="Plans" />

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
                            v-if="plan.price_id === currentPlan.stripe_price"
                            disabled
                        >
                            Active plan
                        </Button>

                        <Dialog>
                            <DialogTrigger as-child>
                                <Button
                                    size="lg"
                                    v-if="
                                        plan.price_id !==
                                        currentPlan.stripe_price
                                    "
                                >
                                    Update
                                </Button>
                            </DialogTrigger>
                            <DialogContent class="max-w-2xl h-auto">
                                <div class="flex flex-col justify-between">
                                    <div
                                        class="flex items-center justify-between mb-4"
                                    >
                                        <h5
                                            class="text-2xl lg:text-3xl font-semibold text-gray-700"
                                        >
                                            {{ plan.name }}
                                        </h5>
                                        <div class="text-gray-800 font-bold">
                                            <span class="text-3xl lg:text-5xl"
                                                >${{ plan.price }}</span
                                            >
                                            <span
                                                class="text-slate-500 font-medium text-md"
                                                >/{{ plan.slug }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center my-2">
                                    <h4
                                        class="pr-4 text-sm lg:text-md font-semibold uppercase"
                                    >
                                        Subscribed
                                    </h4>
                                    <div
                                        class="flex-1 border-t-2 border-gray-200"
                                    ></div>
                                </div>

                                <StripeCheckout :priceId="plan.price_id" />

                                <div class="">
                                    <p
                                        class="text-xs mt-2 font-medium leading-none text-gray-500"
                                    >
                                        Powered by Puro | Terms Privacy
                                    </p>
                                </div>
                            </DialogContent>
                        </Dialog>
                    </div>

                    <Button v-else-if="currentPlan !== true">
                        <Link
                            @click="handlePlanClick(plan)"
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
                            @click="handlePlanClick(plan)"
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
                            @click="handlePlanClick(plan)"
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
