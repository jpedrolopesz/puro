<script setup lang="ts">
import {
    CircleUser,
    Copy,
    CreditCard,
    File,
    Home,
    LineChart,
    ListFilter,
    MoreVertical,
    Package,
    Package2,
    PanelLeft,
    Search,
    Settings,
    ShoppingCart,
    Truck,
    Users2,
} from "lucide-vue-next";

import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Input } from "@/Components/ui/input";

import { Progress } from "@/Components/ui/progress";
import { Separator } from "@/Components/ui/separator";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/Components/ui/tooltip";
import { Checkbox } from "@/Components/ui/checkbox";

import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";

import { defineProps } from "vue";
import orders from "./data/orders.json";
import DataTable from "./Components/DataTable.vue";
import OrderNav from "./Components/OrderNav.vue";
import { columns } from "./Components/columns";

const handleStripeCallback = () => {
    window.location.href = "/billing-central/connect-stripe";
};

const props = defineProps({
    paymentList: {
        type: Object, // Defina o tipo correto do objeto product
        required: true, // Indica que product é obrigatório
    },
});

console.log(props.paymentList);
</script>

<template>
    <AuthenticatedCentralLayout>
        <div class="flex min-h-screen w-full flex-col">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <main
                    class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8 lg:grid-cols-2 xl:grid-cols-2"
                >
                    <div
                        class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2"
                    >
                        <div
                            class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4"
                        >
                            <Card class="sm:col-span-2">
                                <CardHeader class="pb-3">
                                    <CardTitle>Your Orders</CardTitle>
                                    <CardDescription
                                        class="max-w-lg text-balance leading-relaxed"
                                    >
                                        Introducing Our Dynamic Orders Dashboard
                                        for Seamless Management and Insightful
                                        Analysis.
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button @click="handleStripeCallback"
                                        >Connect Stripe</Button
                                    >
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader class="pb-2">
                                    <CardDescription>This Week</CardDescription>
                                    <CardTitle class="text-4xl">
                                        $1329
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div class="text-xs text-muted-foreground">
                                        +25% from last week
                                    </div>
                                </CardContent>
                                <CardFooter>
                                    <Progress
                                        :model-value="25"
                                        aria-label="25% increase"
                                    />
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader class="pb-2">
                                    <CardDescription
                                        >This Month</CardDescription
                                    >
                                    <CardTitle class="text-3xl">
                                        $5,329
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div class="text-xs text-muted-foreground">
                                        +10% from last month
                                    </div>
                                </CardContent>
                                <CardFooter>
                                    <Progress
                                        :model-value="12"
                                        aria-label="12% increase"
                                    />
                                </CardFooter>
                            </Card>
                        </div>

                        <div
                            class="hidden h-full flex-1 flex-col space-y-8 p-8 md:flex"
                        >
                            <div
                                class="flex items-center justify-between space-y-2"
                            >
                                <div>
                                    <h2
                                        class="text-2xl font-bold tracking-tight"
                                    >
                                        Orders
                                    </h2>
                                    <p class="text-muted-foreground">
                                        Here&apos;s a list of your tasks for
                                        this month!
                                    </p>
                                </div>
                            </div>

                            <DataTable :data="orders" :columns="columns" />
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </AuthenticatedCentralLayout>
</template>
