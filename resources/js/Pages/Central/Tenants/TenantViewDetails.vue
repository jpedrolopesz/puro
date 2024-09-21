<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import DataTable from "./Components/DataTable.vue";
import { columns } from "./Components/columnsDetails";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

import { ChevronLeft, CreditCard } from "lucide-vue-next";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Separator } from "@/Components/ui/separator";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

interface TenantDetails {
    tenancy_name: string;
    email: string;
    status: string;
    creator: {
        id: number;
        stripe_id: string;
        name: string;
        email: string;
    };
    domain: {
        domain: string;
    };
    tenancy_db_name: string;
    updated_at: string;
}

interface Payment {
    id: number;
    amount: number;
    description: string;
    payment_date: string;
}

interface CustomerPayments {
    payments: Payment[];
    totals: {
        total_amount: number;
        payment_count: number;
    };
}

const props = defineProps<{
    tenantDetails: TenantDetails;
    customerPayments: CustomerPayments;
}>();

const paymentsData = ref(props.customerPayments.payments);
</script>

<template>
    <Head title="Tenant Details" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="mx-auto grid flex-1 auto-rows-max gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('tenants.index')">
                        <Button variant="outline" size="icon" class="h-7 w-7">
                            <ChevronLeft class="h-4 w-4" />
                            <span class="sr-only">Back</span>
                        </Button>
                    </Link>
                    <div class="flex flex-col">
                        <h1
                            class="flex items-center shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0"
                        >
                            {{ tenantDetails.tenancy_name }}
                            <Badge
                                :variant="
                                    tenantDetails.status === 'active'
                                        ? ''
                                        : 'outline'
                                "
                                class="ml-2"
                            >
                                {{
                                    tenantDetails.status === "active"
                                        ? "Active"
                                        : "Inactive"
                                }}
                            </Badge>
                        </h1>
                        <span class="lowercase">
                            {{ tenantDetails.email }}
                        </span>
                    </div>
                </div>
                <div
                    class="grid gap-4 md:grid-cols-[1fr_250px] lg:grid-cols-3 lg:gap-8"
                >
                    <div
                        class="grid auto-rows-max items-start gap-4 lg:col-span-2 lg:gap-8"
                    >
                        <Card>
                            <CardHeader>
                                <CardTitle>Subscriptions</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div
                                    class="flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
                                >
                                    <p
                                        class="text-sm my-6 text-muted-foreground"
                                    >
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
                                <DataTable
                                    :data="paymentsData"
                                    :columns="columns"
                                />
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
                                    <h3 class="text-lg font-semibold">
                                        Total gasto
                                    </h3>
                                    <p class="text-2xl font-bold">
                                        {{
                                            customerPayments.totals.total_amount
                                        }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{
                                            customerPayments.totals
                                                .payment_count
                                        }}
                                        transações
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                        <Card class="overflow-hidden">
                            <CardHeader>
                                <CardTitle>Creator of the tenant</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit.
                                </CardDescription>
                                <Separator class="my-1" />
                            </CardHeader>

                            <CardContent>
                                <CardTitle class="text-gray-800/90 text-sm"
                                    >ID Local</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.creator.id }}
                                </CardDescription>
                                <CardTitle class="text-gray-800/90 text-sm"
                                    >ID Customer</CardTitle
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
                                    >Creator e-mail</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.creator.email }}
                                </CardDescription>
                                <CardTitle class="text-gray-800/90 text-sm"
                                    >Billing e-mail</CardTitle
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
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit.
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
                                    >Tenant name database</CardTitle
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
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
