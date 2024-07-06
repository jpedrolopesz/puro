<script setup lang="ts">
import {
    ChevronLeft,
    CircleUser,
    Home,
    LineChart,
    Package,
    Package2,
    PanelLeft,
    PlusCircle,
    Search,
    Settings,
    ShoppingCart,
    Upload,
    Users2,
    CreditCard,
    Clipboard,
} from "lucide-vue-next";

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
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

import { ToggleGroup, ToggleGroupItem } from "@/Components/ui/toggle-group";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/Components/ui/tooltip";

import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";

import { defineProps } from "vue";

const props = defineProps({
    paymentDetails: {
        type: Object, // Defina o tipo correto do objeto product
        required: true, // Indica que product é obrigatório
    },
});

console.log(props.paymentDetails);
</script>

<template>
    <AuthenticatedCentralLayout>
        <main
            class="grid flex-1 items-start gap-4 p-4 sm:px-1 sm:py-0 md:gap-8"
        >
            <div class="mx-auto grid flex-1 auto-rows-max gap-4">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" class="h-7 w-7">
                        <ChevronLeft class="h-4 w-4" />
                        <span class="sr-only">Back</span>
                    </Button>
                    <h1
                        class="flex-1 shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0"
                    >
                        {{ paymentDetails.amount }}
                    </h1>
                    <Badge variant="outline" class="ml-auto sm:ml-0">
                        In stock
                    </Badge>
                    <div class="hidden items-center gap-2 md:ml-auto md:flex">
                        {{ paymentDetails.id }}
                        <Clipboard class="h-4 w-4" />
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
                                            <span>$299.00</span>
                                        </li>
                                        <li
                                            class="flex items-center justify-between"
                                        >
                                            <span class="text-muted-foreground"
                                                >Shipping</span
                                            >
                                            <span>$5.00</span>
                                        </li>
                                        <li
                                            class="flex items-center justify-between"
                                        >
                                            <span class="text-muted-foreground"
                                                >Tax</span
                                            >
                                            <span>$25.00</span>
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
                                            <dd>Liam Johnson</dd>
                                        </div>
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <dt class="text-muted-foreground">
                                                Email
                                            </dt>
                                            <dd>
                                                <a href="mailto:"
                                                    >liam@acme.com</a
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
                                                <a href="tel:"
                                                    >+1 234 567 890</a
                                                >
                                            </dd>
                                        </div>
                                    </dl>
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
                                                <CreditCard class="h-4 w-4" />
                                                Visa
                                            </dt>
                                            <dd>**** **** **** 4532</dd>
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
                                        <span>Liam Johnson</span>
                                        <span>1234 Main St.</span>
                                        <span>Anytown, CA 12345</span>
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
