<script setup lang="ts">
import DataTable from "./DataTable/DataTable.vue";
import { columns } from "./DataTable/Columns/paymentColumns";
import { TenantDetails } from "../Types/tenantTypes";
import { CustomerPayments } from "../Types/paymentsTypes";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import { CreditCard } from "lucide-vue-next";
import { Badge } from "@/Components/ui/badge";

import { Separator } from "@/Components/ui/separator";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

const props = defineProps<{
    tenantDetails: TenantDetails;
    customerPayments: CustomerPayments;
}>();

const paymentsData = ref(props.customerPayments.payments);

const getFrequency = (interval: string) => {
    const frequencies = {
        day: "Daily billing",
        week: "Weekly billing",
        month: "Monthly billing",
        year: "Yearly billing",
    };
    return frequencies[interval] || "Custom billing";
};

const formatNextBilling = (date: string) => {
    const nextBilling = new Date(date);
    const formattedDate = nextBilling.toLocaleDateString("en-US", {
        day: "numeric",
        month: "short",
    });
    const amount =
        props.customerPayments.current_subscription?.plan.amount || 0;
    const currency =
        props.customerPayments.current_subscription?.plan.currency || "USD";
    const formattedAmount = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency,
    }).format(amount / 100);
    return `${formattedDate} for ${formattedAmount}`;
};
</script>

<template>
    <Head title="Customer Details" />

    <div class="grid gap-4 md:grid-cols-[1fr_250px] lg:grid-cols-3 lg:gap-8">
        <div
            class="grid auto-rows-max items-start gap-4 lg:col-span-2 lg:gap-8"
        >
            <Card>
                <CardHeader>
                    <CardTitle>Subscriptions</CardTitle>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="props.customerPayments.current_subscription"
                        class="space-y-2"
                    >
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Product</span>
                            <div class="flex items-center space-x-2">
                                <span>{{
                                    props.customerPayments.current_subscription
                                        .plan.name
                                }}</span>
                                <Badge variant="success">Active</Badge>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Frequency</span>
                            <span>{{
                                getFrequency(
                                    props.customerPayments.current_subscription
                                        .plan.interval,
                                )
                            }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Next invoice</span>
                            <span>{{
                                formatNextBilling(
                                    props.customerPayments.current_subscription
                                        .current_period_end,
                                )
                            }}</span>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
                    >
                        <p class="text-sm my-6 text-muted-foreground">
                            No subscription
                        </p>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardHeader class="px-7">
                    <CardTitle>Orders</CardTitle>
                    <CardDescription>
                        Recent orders from your store.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <DataTable :data="paymentsData" :columns="columns" />
                </CardContent>
            </Card>
        </div>
        <div class="grid auto-rows-max items-start gap-4 lg:gap-8">
            <Card>
                <CardHeader>
                    <CardTitle>Insights</CardTitle>
                </CardHeader>
                <CardContent>
                    <div>
                        <h3 class="text-lg font-semibold">Total spent</h3>
                        <p class="text-2xl font-bold">
                            {{ customerPayments.totals.total_amount }}
                        </p>
                        <p class="text-sm text-muted-foreground">
                            {{ customerPayments.totals.payment_count }}
                            transactions
                        </p>
                    </div>
                </CardContent>
            </Card>
            <Card class="overflow-hidden">
                <CardHeader>
                    <CardTitle>Creator of the tenant</CardTitle>
                    <CardDescription>
                        Details about the tenant creator.
                    </CardDescription>
                    <Separator class="my-1" />
                </CardHeader>

                <CardContent>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Local ID</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.creator.id }}
                    </CardDescription>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Customer ID</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.creator.stripe_id }}
                    </CardDescription>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Creator name</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.creator.name }}
                    </CardDescription>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Creator email</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.creator.email }}
                    </CardDescription>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Billing email</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.email }}
                    </CardDescription>

                    <Separator class="my-4" />
                    <div class="grid gap-3">
                        <CardTitle class="text-gray-800/90 text-sm">
                            Payment Information
                        </CardTitle>
                        <dl class="grid gap-3">
                            <CardDescription
                                class="flex items-center justify-between"
                            >
                                <dt
                                    class="flex items-center gap-1 text-muted-foreground"
                                >
                                    <CreditCard class="h-4 w-4" />
                                    Visa
                                </dt>
                                <dd>**** **** **** 4532</dd>
                            </CardDescription>
                        </dl>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Details of the tenant</CardTitle>
                    <CardDescription>
                        Technical information about the tenant.
                    </CardDescription>
                    <Separator class="my-1" />
                </CardHeader>

                <CardContent>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Domain</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.domain.domain }}
                    </CardDescription>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Tenant database name</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.tenancy_db_name }}
                    </CardDescription>
                    <CardTitle class="text-gray-800/90 text-sm"
                        >Last update</CardTitle
                    >
                    <CardDescription class="mb-3">
                        {{ tenantDetails.updated_at }}
                    </CardDescription>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
