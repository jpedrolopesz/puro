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
} from "lucide-vue-next";

import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
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
                        <Button variant="outline" size="sm"> Discard </Button>
                        <Button size="sm"> Save Product </Button>
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
                                <CardTitle>Product Details</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit
                                </CardDescription>
                            </CardHeader>
                            <CardContent> </CardContent>
                        </Card>
                        <Card>
                            <CardHeader>
                                <CardTitle>Stock</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit
                                </CardDescription>
                                <CardContent> </CardContent>
                            </CardHeader>
                        </Card>
                    </div>
                    <div class="grid auto-rows-max items-start gap-4 lg:gap-8">
                        <Card>
                            <CardHeader>
                                <CardTitle>Product Status</CardTitle>
                            </CardHeader>
                            <CardContent> </CardContent>
                        </Card>
                        <Card class="overflow-hidden">
                            <CardHeader>
                                <CardTitle>Product imgs</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-2">
                                    <img
                                        alt="Product image"
                                        class="aspect-square w-full rounded-md object-cover"
                                        height="300"
                                        src="/placeholder.svg"
                                        width="300"
                                    />
                                    <div class="grid grid-cols-3 gap-2">
                                        <button>
                                            <img
                                                alt="Product image"
                                                class="aspect-square w-full rounded-md object-cover"
                                                height="84"
                                                src="/placeholder.svg"
                                                width="84"
                                            />
                                        </button>
                                        <button>
                                            <img
                                                alt="Product image"
                                                class="aspect-square w-full rounded-md object-cover"
                                                height="84"
                                                src="/placeholder.svg"
                                                width="84"
                                            />
                                        </button>
                                        <button
                                            class="flex aspect-square w-full items-center justify-center rounded-md border border-dashed"
                                        >
                                            <Upload
                                                class="h-4 w-4 text-muted-foreground"
                                            />
                                            <span class="sr-only">Upload</span>
                                        </button>
                                    </div>
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
