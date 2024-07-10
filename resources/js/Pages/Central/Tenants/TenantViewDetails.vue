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

console.log(props.tenantDetails);
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
                            class="shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0"
                        >
                            {{ tenantDetails.name }}
                        </h1>
                        <span class="lowercase">
                            {{ tenantDetails.email }}
                        </span>
                    </div>

                    <div class="hidden items-center md:ml-auto md:flex">
                        <span class="text-muted-foreground text-sm">{{
                            tenantDetails.id
                        }}</span>
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
                                <CardTitle>Assinaturas</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div>
                                    <Separator class="my-2" />
                                    <ul class="grid gap-3">
                                        <li
                                            class="flex items-center justify-between"
                                        >
                                            <span class="text-muted-foreground"
                                                >Subtotal</span
                                            >
                                            <span></span>
                                        </li>
                                        <li
                                            class="flex items-center justify-between"
                                        >
                                            <span class="text-muted-foreground"
                                                >Tax</span
                                            >
                                            <span></span>
                                        </li>

                                        <li
                                            class="flex items-center justify-between font-semibold"
                                        >
                                            <span class="text-muted-foreground"
                                                >Total</span
                                            >
                                            <span>$329.00</span>
                                        </li>
                                    </ul>
                                </div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader>
                                <CardTitle>Pagamentos</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div>
                                    <Separator class="my-2" />
                                    <ul class="grid gap-3">
                                        <li
                                            class="flex items-center justify-between"
                                        >
                                            <span class="text-muted-foreground"
                                                >Subtotal</span
                                            >
                                            <span></span>
                                        </li>
                                        <li
                                            class="flex items-center justify-between"
                                        >
                                            <span class="text-muted-foreground"
                                                >Tax</span
                                            >
                                            <span></span>
                                        </li>

                                        <li
                                            class="flex items-center justify-between font-semibold"
                                        >
                                            <span class="text-muted-foreground"
                                                >Total</span
                                            >
                                            <span>$329.00</span>
                                        </li>
                                    </ul>
                                </div>

                                <Separator class="my-4" />
                                <div class="grid gap-3">
                                    <div class="font-semibold">
                                        Payment Information
                                    </div>

                                    <dl class="grid gap-3">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt
                                                class="flex items-center gap-1 text-muted-foreground"
                                            >
                                                Id
                                            </dt>
                                            <dd></dd>
                                        </div>
                                    </dl>

                                    <dl class="grid gap-3">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt
                                                class="flex items-center gap-1 text-muted-foreground"
                                            >
                                                Description
                                            </dt>
                                            <dd></dd>
                                        </div>
                                    </dl>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                    <div class="grid auto-rows-max items-start gap-4 lg:gap-8">
                        <Card class="overflow-hidden">
                            <CardHeader>
                                <CardTitle> Insights</CardTitle>
                            </CardHeader>

                            <CardContent>
                                <Separator class="my-2" />

                                <div class="grid gap-3">
                                    <div class="font-semibold"></div>
                                    <address
                                        class="grid gap-0.5 not-italic text-muted-foreground"
                                    ></address>
                                </div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader>
                                <CardTitle>Detalhes</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit.
                                </CardDescription>
                                <Separator class="my-1" />
                            </CardHeader>

                            <CardContent>
                                <CardTitle class="text-gray-800/90 text-sm">
                                    Cliente desde</CardTitle
                                >
                                <CardDescription class="mb-3">
                                    {{ tenantDetails.created_at }}
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
                    <Button size="sm"> Save Product </Button>
                </div>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
