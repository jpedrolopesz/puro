<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
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
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { defineProps } from "vue";

function formatDate(timestamp) {
    const date = new Date(timestamp * 1000);
    return date.toLocaleDateString();
}

const props = defineProps({
    tenantDetails: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Tenant Details" />

    <AuthenticatedCentralLayout>
        <main
            class="grid flex-1 items-start gap-4 p-4 sm:px-1 sm:py-0 md:gap-8"
        >
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
                                :variant="tenantDetails.status ? '' : 'outline'"
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

                    <div class="hidden items-center md:ml-auto md:flex">
                        <div class="flex items-center justify-center gap-2">
                            <Button variant="outline" size="sm">
                                Discard
                            </Button>
                            <Button size="sm"> Save </Button>
                        </div>
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
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Customer</TableHead>
                                            <TableHead
                                                class="hidden sm:table-cell"
                                            >
                                                Type
                                            </TableHead>
                                            <TableHead
                                                class="hidden sm:table-cell"
                                            >
                                                Status
                                            </TableHead>
                                            <TableHead
                                                class="hidden md:table-cell"
                                            >
                                                Date
                                            </TableHead>
                                            <TableHead class="text-right">
                                                Amount
                                            </TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow>
                                            <TableCell>
                                                <div class="font-medium">
                                                    Emma Brown
                                                </div>
                                                <div
                                                    class="hidden text-sm text-muted-foreground md:inline"
                                                >
                                                    emma@example.com
                                                </div>
                                            </TableCell>
                                            <TableCell
                                                class="hidden sm:table-cell"
                                            >
                                                Sale
                                            </TableCell>
                                            <TableCell
                                                class="hidden sm:table-cell"
                                            >
                                                <Badge
                                                    class="text-xs"
                                                    variant="secondary"
                                                >
                                                    Fulfilled
                                                </Badge>
                                            </TableCell>
                                            <TableCell
                                                class="hidden md:table-cell"
                                            >
                                                2023-06-26
                                            </TableCell>
                                            <TableCell class="text-right">
                                                $450.00
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </CardContent>
                        </Card>
                    </div>
                    <div class="grid auto-rows-max items-start gap-4 lg:gap-8">
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
                                <CardTitle class="text-gray-800/90 text-sm">
                                    ID creator</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.creator.id }}
                                </CardDescription>
                                <CardTitle class="text-gray-800/90 text-sm">
                                    Domain</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.domain.domain }}
                                </CardDescription>
                                <CardTitle class="text-gray-800/90 text-sm">
                                    Creator name</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.creator.name }}
                                </CardDescription>
                                <CardTitle class="text-gray-800/90 text-sm">
                                    Creator e-mail</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.creator.email }}
                                </CardDescription>

                                <CardTitle class="text-gray-800/90 text-sm">
                                    Customer since</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.creator.created_at }}
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
                                <CardTitle class="text-gray-800/90 text-sm">
                                    Last update</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.updated_at }}
                                </CardDescription>
                                <CardTitle class="text-gray-800/90 text-sm">
                                    E-mail billing</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.email }}
                                </CardDescription>

                                <CardTitle class="text-gray-800/90 text-sm">
                                    Tenant name database</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.tenancy_db_name }}
                                </CardDescription>
                            </CardContent>
                        </Card>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-2 md:hidden">
                    <Button variant="outline" size="sm"> Discard </Button>
                    <Button size="sm"> Save </Button>
                </div>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
