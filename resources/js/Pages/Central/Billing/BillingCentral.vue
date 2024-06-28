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

interface Order {
    id: number;
    Customer: {
        Name: string;
        Email: string;
    };
    Type: string;
    Status: string;
    Date: string;
    Amount: string;
}

const props = defineProps<{
    orders: Order[];
}>();

const handleStripeCallback = () => {
    window.location.href = "/billing-central/connect-stripe";
};
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
                        <Tabs default-value="week">
                            <div class="flex items-center">
                                <TabsList>
                                    <TabsTrigger value="week">
                                        Week
                                    </TabsTrigger>
                                    <TabsTrigger value="month">
                                        Month
                                    </TabsTrigger>
                                    <TabsTrigger value="year">
                                        Year
                                    </TabsTrigger>
                                </TabsList>
                                <div class="ml-auto flex items-center gap-2">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                class="h-7 gap-1 rounded-md px-3"
                                            >
                                                <ListFilter
                                                    class="h-3.5 w-3.5"
                                                />
                                                <span
                                                    class="sr-only sm:not-sr-only"
                                                    >Filter</span
                                                >
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuLabel
                                                >Filter by</DropdownMenuLabel
                                            >
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem>
                                                <div
                                                    class="items-top flex space-x-2"
                                                >
                                                    <Checkbox id="terms1" />
                                                    <label
                                                        for="terms2"
                                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                                    >
                                                        Fulfilled
                                                    </label>
                                                </div>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem>
                                                <div
                                                    class="items-top flex space-x-2"
                                                >
                                                    <Checkbox id="terms1" />
                                                    <label
                                                        for="terms2"
                                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                                    >
                                                        Declined
                                                    </label>
                                                </div>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem>
                                                <div
                                                    class="items-top flex space-x-2"
                                                >
                                                    <Checkbox id="terms1" />
                                                    <label
                                                        for="terms2"
                                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                                    >
                                                        Refunded
                                                    </label>
                                                </div>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        class="h-7 gap-1 rounded-md px-3"
                                    >
                                        <File class="h-3.5 w-3.5" />
                                        <span class="sr-only sm:not-sr-only"
                                            >Export</span
                                        >
                                    </Button>
                                </div>
                            </div>
                            <TabsContent value="week">
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
                                                    <TableHead
                                                        >Customer</TableHead
                                                    >
                                                    <TableHead
                                                        class="hidden sm:table-cell"
                                                        >Type</TableHead
                                                    >
                                                    <TableHead
                                                        class="hidden sm:table-cell"
                                                        >Status</TableHead
                                                    >
                                                    <TableHead
                                                        class="hidden md:table-cell"
                                                        >Date</TableHead
                                                    >
                                                    <TableHead
                                                        class="text-right"
                                                        >Amount</TableHead
                                                    >
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody>
                                                <TableRow
                                                    v-for="order in orders"
                                                    :key="
                                                        order.Customer.Email +
                                                        order.Date
                                                    "
                                                >
                                                    <TableCell>
                                                        <div
                                                            class="font-medium"
                                                        >
                                                            {{
                                                                order.Customer
                                                                    .Name
                                                            }}
                                                        </div>
                                                        <div
                                                            class="hidden text-sm text-muted-foreground md:inline"
                                                        >
                                                            {{
                                                                order.Customer
                                                                    .Email
                                                            }}
                                                        </div>
                                                    </TableCell>
                                                    <TableCell
                                                        class="hidden sm:table-cell"
                                                        >{{
                                                            order.Type
                                                        }}</TableCell
                                                    >
                                                    <TableCell
                                                        class="hidden sm:table-cell"
                                                    >
                                                        <Badge
                                                            v-if="
                                                                order.Status ===
                                                                'Fulfilled'
                                                            "
                                                            class="text-xs"
                                                            variant="secondary"
                                                            >Fulfilled</Badge
                                                        >
                                                        <Badge
                                                            v-if="
                                                                order.Status ===
                                                                'Declined'
                                                            "
                                                            class="text-xs"
                                                            variant="outline"
                                                            >Declined</Badge
                                                        >
                                                    </TableCell>
                                                    <TableCell
                                                        class="hidden md:table-cell"
                                                        >{{
                                                            order.Date
                                                        }}</TableCell
                                                    >
                                                    <TableCell
                                                        class="text-right"
                                                        >{{
                                                            order.Amount
                                                        }}</TableCell
                                                    >
                                                </TableRow>
                                            </TableBody>
                                        </Table>
                                    </CardContent>
                                </Card>
                            </TabsContent>
                        </Tabs>
                    </div>
                </main>
            </div>
        </div>
    </AuthenticatedCentralLayout>
</template>
