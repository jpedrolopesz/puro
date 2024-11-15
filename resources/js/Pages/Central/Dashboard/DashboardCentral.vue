<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import { Head } from "@inertiajs/vue3";
import ChartlineOverview from "./Components/ChartlineOverview.vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import { defineProps, computed } from "vue";

interface Admin {
    id: number;
    name: string;
    email: string;
    // adicione outros campos necessárisadaos
}

interface PaymentTotals {
    total_amount: string;
    yearly_totals: Record<string, string>;
    chart_data: Array<{
        year: number;
        amount: number;
    }>;
}

interface SubscriberStatistics {
    subscribers: {
        total: number;
        yearly_totals: Record<number, number>;
        chart_data: Array<{
            year: number;
            subscribers: number;
        }>;
    };
    non_subscribers: {
        total: number;
    };
    total_users: number;
}

const props = defineProps<{
    admin: Admin;
    getMonthlyPaymentSummary: Array<Record<number, number>>;
    calculateAnnualPaymentTotals: PaymentTotals;
    calculateAnnualSubscriberTotals: SubscriberStatistics;
}>();

// Cálculo de variação percentual de pagamentos
const calculatePaymentPercentageChange = computed(() => {
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();

    const currentMonthPayment =
        props.getMonthlyPaymentSummary[currentMonth]?.[currentYear] || 0;
    const previousMonth = currentMonth === 0 ? 11 : currentMonth - 1;
    const previousYear = currentMonth === 0 ? currentYear - 1 : currentYear;
    const previousMonthPayment =
        props.getMonthlyPaymentSummary[previousMonth]?.[previousYear] || 0;

    if (previousMonthPayment === 0) return 0;

    return (
        ((currentMonthPayment - previousMonthPayment) / previousMonthPayment) *
        100
    ).toFixed(1);
});

// Cálculo de variação percentual de inscritos
const calculateSubscriberPercentageChange = computed(() => {
    const currentYear = new Date().getFullYear();
    const lastYear = currentYear - 1;

    const currentYearSubscribers =
        props.calculateAnnualSubscriberTotals.subscribers.yearly_totals[
            currentYear
        ] || 0;
    const lastYearSubscribers =
        props.calculateAnnualSubscriberTotals.subscribers.yearly_totals[
            lastYear
        ] || 0;

    if (lastYearSubscribers === 0) return 0;

    return (
        ((currentYearSubscribers - lastYearSubscribers) / lastYearSubscribers) *
        100
    ).toFixed(1);
});

// Valor atual do mês
const currentMonthSales = computed(() => {
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();

    return props.getMonthlyPaymentSummary[currentMonth]?.[currentYear] || 0;
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedCentralLayout>
        <main class="space-y-4 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Dashboard Central
                    </h2>
                    <p class="text-muted-foreground">
                        Bem-vindo(a), {{ admin.name }}!
                    </p>
                </div>
            </div>

            <Tabs default-value="overview" class="space-y-4">
                <TabsList>
                    <TabsTrigger value="overview">Overview</TabsTrigger>
                    <TabsTrigger value="analytics" disabled
                        >Analytics</TabsTrigger
                    >
                    <TabsTrigger value="reports" disabled>Reports</TabsTrigger>
                    <TabsTrigger value="notifications" disabled
                        >Notifications</TabsTrigger
                    >
                </TabsList>
                <TabsContent value="overview" class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Revenue Card -->
                        <Card>
                            <CardHeader
                                class="flex flex-row items-center justify-between space-y-0 pb-2"
                            >
                                <CardTitle class="text-sm font-medium">
                                    Total Revenue
                                </CardTitle>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground"
                                >
                                    <path
                                        d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"
                                    />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    ${{
                                        calculateAnnualPaymentTotals.total_amount
                                    }}
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    {{
                                        calculatePaymentPercentageChange >= 0
                                            ? "+"
                                            : ""
                                    }}{{ calculatePaymentPercentageChange }}%
                                    from last month
                                </p>
                            </CardContent>
                        </Card>

                        <!-- Subscribers Card -->
                        <Card>
                            <CardHeader
                                class="flex flex-row items-center justify-between space-y-0 pb-2"
                            >
                                <CardTitle class="text-sm font-medium">
                                    Subscribers
                                </CardTitle>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground"
                                >
                                    <path
                                        d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                    />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    {{
                                        calculateAnnualSubscriberTotals
                                            .subscribers.total
                                    }}
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    {{
                                        calculateSubscriberPercentageChange >= 0
                                            ? "+"
                                            : ""
                                    }}{{ calculateSubscriberPercentageChange }}%
                                    from last year
                                </p>
                            </CardContent>
                        </Card>

                        <!-- Monthly Sales Card -->
                        <Card>
                            <CardHeader
                                class="flex flex-row items-center justify-between space-y-0 pb-2"
                            >
                                <CardTitle class="text-sm font-medium">
                                    Monthly Sales
                                </CardTitle>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground"
                                >
                                    <rect
                                        width="20"
                                        height="14"
                                        x="2"
                                        y="5"
                                        rx="2"
                                    />
                                    <path d="M2 10h20" />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    ${{
                                        new Intl.NumberFormat("en-US", {
                                            minimumFractionDigits: 2,
                                        }).format(currentMonthSales)
                                    }}
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    {{
                                        calculatePaymentPercentageChange >= 0
                                            ? "+"
                                            : ""
                                    }}{{ calculatePaymentPercentageChange }}%
                                    from last month
                                </p>
                            </CardContent>
                        </Card>

                        <!-- Conversion Rate Card -->
                        <Card>
                            <CardHeader
                                class="flex flex-row items-center justify-between space-y-0 pb-2"
                            >
                                <CardTitle class="text-sm font-medium">
                                    Conversion Rate
                                </CardTitle>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground"
                                >
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    {{
                                        (
                                            (calculateAnnualSubscriberTotals
                                                .subscribers.total /
                                                calculateAnnualSubscriberTotals.total_users) *
                                            100
                                        ).toFixed(1)
                                    }}%
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    Of total users
                                </p>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Overview Chart -->
                    <div class="grid gap-2 md:grid-cols-1 lg:grid-cols-1">
                        <Card class="col-span-4">
                            <CardHeader>
                                <CardTitle>Overview</CardTitle>
                            </CardHeader>
                            <CardContent class="pl-2">
                                <ChartlineOverview
                                    :data="props.getMonthlyPaymentSummary"
                                />
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>
            </Tabs>
        </main>
    </AuthenticatedCentralLayout>
</template>
