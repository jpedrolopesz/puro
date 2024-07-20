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
    paymentDetails: {
        type: Object,
        required: true,
    },
});

console.log(props.paymentDetails);
</script>

<template>
    <Head title="Payments Details" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="mx-auto grid flex-1 auto-rows-max gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('payments.index')">
                        <Button variant="outline" size="icon" class="h-7 w-7">
                            <ChevronLeft class="h-4 w-4" />
                            <span class="sr-only">Back</span>
                        </Button>
                    </Link>
                    <h1
                        class="flex-1 shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0"
                    >
                        {{ paymentDetails.amount }}
                        <span class="uppercase"
                            >{{ paymentDetails.currency }}
                        </span>
                    </h1>
                    <Badge
                        :class="[
                            'variant-outline',
                            'ml-auto sm:ml-0 capitalize',
                            paymentDetails.status
                                ? 'bg-gray-800/80'
                                : 'bg-gray-400/40 text-black',
                        ]"
                    >
                        {{ paymentDetails.status }}
                    </Badge>

                    <div class="hidden items-center md:ml-auto md:flex">
                        <span class="text-muted-foreground text-sm">{{
                            paymentDetails.id
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
                                <CardTitle>Payment Details</CardTitle>
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
                                            <span>{{
                                                paymentDetails.amount
                                            }}</span>
                                        </li>
                                        <li
                                            class="flex items-center justify-between"
                                        >
                                            <span class="text-muted-foreground"
                                                >Tax</span
                                            >
                                            <span>{{
                                                paymentDetails.application_fee_amount
                                            }}</span>
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

                                <Separator class="my-2" />
                                <div class="grid gap-3 my-4">
                                    <div class="font-semibold">
                                        Customer Information
                                    </div>
                                    <dl class="grid gap-3">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt class="text-muted-foreground">
                                                Customer
                                            </dt>
                                            <dd>
                                                {{
                                                    paymentDetails.customer_name
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt class="text-muted-foreground">
                                                Email
                                            </dt>
                                            <dd>
                                                <a
                                                    :href="
                                                        'mailto:' +
                                                        paymentDetails.customer_email
                                                    "
                                                >
                                                    {{
                                                        paymentDetails.customer_email
                                                    }}</a
                                                >
                                            </dd>
                                        </div>
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt class="text-muted-foreground">
                                                Phone
                                            </dt>
                                            <dd>
                                                <a
                                                    href="tel:{{ paymentDetails.customer_phone ? paymentDetails.customer_phone : 'Uninformed' }}"
                                                >
                                                    {{
                                                        paymentDetails.customer_phone
                                                            ? paymentDetails.customer_phone
                                                            : "Uninformed"
                                                    }}
                                                </a>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                                <Separator class="my-4" />
                                <div
                                    class="grid gap-3"
                                    v-if="paymentDetails.status !== 'canceled'"
                                >
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
                                            <dd>
                                                {{
                                                    paymentDetails.payment_method
                                                }}
                                            </dd>
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
                                            <dd>
                                                {{
                                                    paymentDetails.description
                                                        ? paymentDetails.description
                                                        : "Uninformed"
                                                }}
                                            </dd>
                                        </div>
                                    </dl>

                                    <dl class="grid gap-3">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt
                                                class="flex items-center gap-1 text-muted-foreground"
                                            >
                                                Card Expiration
                                            </dt>
                                            <dd>
                                                {{
                                                    paymentDetails.card_exp_month
                                                }}/
                                                {{
                                                    paymentDetails.card_exp_year
                                                }}
                                            </dd>
                                        </div>
                                    </dl>

                                    <dl class="grid gap-3">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt
                                                class="flex items-center gap-1 text-muted-foreground"
                                            >
                                                <CreditCard class="h-4 w-4" />
                                                {{ paymentDetails.card_brand }}
                                            </dt>
                                            <dd>
                                                **** **** ****
                                                {{ paymentDetails.card_last4 }}
                                            </dd>
                                        </div>
                                    </dl>
                                    <dl class="grid gap-3">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt
                                                class="flex items-center gap-1 text-muted-foreground"
                                            >
                                                Created
                                            </dt>
                                            <dd>
                                                {{
                                                    formatDate(
                                                        paymentDetails.created,
                                                    )
                                                }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                    <div class="grid auto-rows-max items-start gap-4 lg:gap-8">
                        <Card class="overflow-hidden">
                            <CardHeader>
                                <CardTitle> Shipping Information</CardTitle>
                            </CardHeader>

                            <CardContent>
                                <Separator class="my-2" />

                                <div class="grid gap-3">
                                    <div class="font-semibold"></div>
                                    <address
                                        class="grid gap-0.5 not-italic text-muted-foreground"
                                    >
                                        {{
                                            paymentDetails.customer_address
                                                ? paymentDetails.customer_address
                                                : "Uninformed"
                                        }}
                                    </address>
                                </div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader>
                                <CardTitle>Archive Product</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div />
                                <Button size="sm" variant="secondary">
                                    Archive Product
                                </Button>
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
