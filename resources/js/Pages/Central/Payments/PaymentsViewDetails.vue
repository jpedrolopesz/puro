<script setup lang="ts">
import { computed } from "vue";
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { paymentStatus, type Payment } from "./data/data";
import { Head, Link } from "@inertiajs/vue3";
import { ChevronLeft, CreditCard } from "lucide-vue-next";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Separator } from "@/Components/ui/separator";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

const props = defineProps<{ paymentDetails: Payment }>();

console.log(props.paymentDetails);
const status = computed(() => paymentStatus(props.paymentDetails));
const amount = computed(() => props.paymentDetails.amount);
const additionalInfo = computed(() => {
    try {
        return JSON.parse(props.paymentDetails.additional_info);
    } catch (error) {
        console.error("Error parsing additional_info:", error);
        return {};
    }
});
const cardDetails = computed(
    () => additionalInfo.value.payment_method_details?.card || {},
);

const getStripeDashboardLink = (invoiceId: string) =>
    `https://dashboard.stripe.com/invoices/${invoiceId}`;

const paymentItems = computed(() => [
    { label: "Subtotal", value: props.paymentDetails.amount },
    { label: "Processing fees", value: props.paymentDetails.fee },
    {
        label: "Net amount",
        value: `${props.paymentDetails.currency} ${props.paymentDetails.net_amount}`,
        class: "font-semibold uppercase",
    },
]);

const paymentInfoItems = computed(() => [
    { label: "Id", value: props.paymentDetails.stripe_payment_id },
    {
        label: "Description",
        value: props.paymentDetails.description || "Uninformed",
    },
    {
        label: "Card Expiration",
        value: `${cardDetails.value.exp_month}/${cardDetails.value.exp_year}`,
    },
    {
        label: "Card",
        value: `**** **** **** ${cardDetails.value.last4}`,
        icon: CreditCard,
    },
    { label: "Country", value: cardDetails.value.country },
    { label: "Funding", value: cardDetails.value.funding, class: "capitalize" },
    {
        label: "Risk Level",
        value: additionalInfo.value.risk_level,
        class: "capitalize",
    },
    { label: "Risk Score", value: additionalInfo.value.risk_score },
    {
        label: "Invoice",
        value: props.paymentDetails.invoice_id,
        link: getStripeDashboardLink(props.paymentDetails.invoice_id),
    },
    { label: "Created", value: props.paymentDetails.payment_date },
]);
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
                    <div
                        v-if="amount && status"
                        class="flex w-[150px] space-x-2 items-center"
                    >
                        <span class="max-w-[50px] font-medium">{{
                            amount
                        }}</span>
                        <span class="font-medium uppercase">{{
                            paymentDetails.currency
                        }}</span>
                        <Badge
                            :variant="status.style"
                            :class="`flex items-center space-x-1 ${status.style}`"
                        >
                            <span class="font-medium">{{ status.label }}</span>
                            <component
                                v-if="status.icon"
                                :is="status.icon"
                                :class="`h-4 w-4 ml-1 ${status.iconStyle || 'text-current'}`"
                            />
                        </Badge>
                    </div>
                    <div class="hidden items-center md:ml-auto md:flex">
                        <span class="text-muted-foreground text-sm">{{
                            paymentDetails.id
                        }}</span>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Payment Details</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Separator class="my-2" />
                        <ul class="grid gap-3">
                            <li
                                v-for="(item, index) in paymentItems"
                                :key="index"
                                :class="[
                                    'flex items-center justify-between',
                                    item.class,
                                ]"
                            >
                                <span
                                    :class="{
                                        'text-muted-foreground': !item.class,
                                    }"
                                    >{{ item.label }}</span
                                >
                                <span>{{ item.value }}</span>
                            </li>
                        </ul>

                        <Separator class="my-4" />
                        <div class="grid gap-3 my-4">
                            <div class="font-semibold">
                                Customer Information
                            </div>
                            <dl class="grid gap-3">
                                <div class="flex items-center justify-between">
                                    <dt class="text-muted-foreground">
                                        Customer
                                    </dt>
                                    <dd>{{ paymentDetails.customer_name }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-muted-foreground">Email</dt>
                                    <dd>{{ paymentDetails.customer_email }}</dd>
                                </div>
                            </dl>
                        </div>

                        <Separator class="my-4" />
                        <div
                            v-if="paymentDetails.status !== 'canceled'"
                            class="grid gap-3"
                        >
                            <div class="font-semibold">Payment Information</div>
                            <dl class="grid gap-3">
                                <div
                                    v-for="(item, index) in paymentInfoItems"
                                    :key="index"
                                    class="flex items-center justify-between"
                                >
                                    <dt
                                        :class="[
                                            'text-muted-foreground',
                                            {
                                                'flex items-center gap-1':
                                                    item.icon,
                                            },
                                        ]"
                                    >
                                        <component
                                            v-if="item.icon"
                                            :is="item.icon"
                                            class="h-4 w-4"
                                        />
                                        {{
                                            item.icon === "CreditCard"
                                                ? cardDetails.brand
                                                : item.label
                                        }}
                                    </dt>
                                    <dd :class="item.class">
                                        <template v-if="item.link">
                                            <span class="mr-2">{{
                                                item.value
                                            }}</span>

                                            <a
                                                :href="item.link"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="text-blue-500 hover:text-blue-700 transition-colors after:content-['_ⓘ']"
                                            >
                                            </a>
                                        </template>
                                        <template v-else>
                                            {{ item.value }}
                                        </template>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
