<script setup lang="ts">
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import PriceUpdateForm from "./Partils/PriceUpdateForm.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import { SymbolIcon, DotsHorizontalIcon } from "@radix-icons/vue";

import { ChevronLeft, PlusCircle, Upload } from "lucide-vue-next";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { Label } from "@/Components/ui/label";
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger,
    DropdownMenuLabel,
} from "@/Components/ui/dropdown-menu";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

function formatDate(timestamp) {
    const date = new Date(timestamp * 1000);
    return date.toLocaleDateString();
}
const props = defineProps({
    product: {
        type: Object as () => any,
        required: true,
    },
});

console.log(props.product);

const form = useForm({
    name: props.product.name,
    description: props.product.description,
    active: props.product.active.toString(),
});

function updateProduct() {
    form.put(route("plan.update", { productId: props.product.id }), {
        onSuccess: () => {
            // Redirect or notify user of success
        },
        onError: (errors) => {
            // Handle form errors
        },
    });
}
</script>

<template>
    <Head title="Plan Details" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="mx-auto grid flex-1 auto-rows-max gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('plans.index')">
                        <Button variant="outline" size="icon" class="h-7 w-7">
                            <ChevronLeft class="h-4 w-4" />
                            <span class="sr-only">Back</span>
                        </Button>
                    </Link>
                    <h1
                        class="flex-1 shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0"
                    >
                        {{ product.name }}
                    </h1>
                    <Badge
                        :variant="product.active ? 'outline' : ''"
                        :class="[
                            'ml-auto hover:non',
                            'sm:ml-0',
                            product.active
                                ? 'text-white bg-gray-800/80'
                                : 'text-gray-800/80 bg-gray-400/40',
                        ]"
                    >
                        {{ product.active ? "Active" : "Inactive" }}
                    </Badge>

                    <div class="hidden items-center gap-2 md:ml-auto md:flex">
                        <Button size="sm" @click="updateProduct"> Save</Button>
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
                                <CardTitle>Plan Details</CardTitle>
                                <CardDescription>
                                    Product or Service Name: Visible to
                                    customers. Description: Appears at checkout
                                    and in the customer portal, in quotation
                                    marks.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-6">
                                    <div class="grid gap-3">
                                        <Label for="name">Name</Label>
                                        <Input
                                            id="name"
                                            type="text"
                                            class="w-full"
                                            v-model="form.name"
                                        />
                                    </div>
                                    <div class="grid gap-3">
                                        <Label for="description"
                                            >Description</Label
                                        >
                                        <Textarea
                                            id="description"
                                            class="min-h-22"
                                            v-model="form.description"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader class="px-7">
                                <CardTitle>Prices</CardTitle>
                                <CardDescription>
                                    You can only edit the values of prices
                                    without a subscription. For other changes,
                                    it is recommended to create a new price.
                                    <a
                                        class="text-gray-600 text-xs after:content-['_ⓘ'] ..."
                                        href="https://docs.stripe.com/products-prices/pricing-models#flat-rate"
                                        target="_blank"
                                    ></a>
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead> Price </TableHead>
                                            <TableHead> Subscribers </TableHead>
                                            <TableHead> Date </TableHead>
                                            <TableHead> Action </TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow
                                            v-for="price in props.product
                                                .prices"
                                            :key="price.id"
                                        >
                                            <TableCell>
                                                <div class="flex flex-col">
                                                    <div
                                                        class="flex items-end font-semibold space-x-1.5"
                                                    >
                                                        <span class="uppercase">
                                                            {{
                                                                price.currency
                                                            }} </span
                                                        ><span>
                                                            {{
                                                                price.unit_amount /
                                                                100
                                                            }}
                                                        </span>
                                                    </div>

                                                    <div
                                                        class="flex items-center space-x-1 font-normal text-xs"
                                                    >
                                                        <SymbolIcon
                                                            class="w-3 h-3"
                                                        />
                                                        <span>
                                                            {{
                                                                price.recurring
                                                                    .interval
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                {{ price.subscription_count }}
                                                active
                                            </TableCell>
                                            <TableCell>
                                                {{ formatDate(price.created) }}
                                            </TableCell>
                                            <TableCell>
                                                <Sheet>
                                                    <SheetTrigger>
                                                        <Button
                                                            variant="ghost"
                                                            class="flex h-8 w-8 p-0 data-[state=open]:bg-muted"
                                                        >
                                                            <DotsHorizontalIcon
                                                                class="h-4 w-4"
                                                            />
                                                            <span
                                                                class="sr-only"
                                                                >Open menu</span
                                                            >
                                                        </Button>
                                                    </SheetTrigger>
                                                    <SheetContent>
                                                        <SheetHeader>
                                                            <SheetTitle
                                                                >Edit Price
                                                            </SheetTitle>
                                                            <SheetDescription
                                                                ><Separator
                                                            /></SheetDescription>
                                                        </SheetHeader>

                                                        <PriceUpdateForm
                                                            :data="
                                                                props.product
                                                                    .prices
                                                            "
                                                        />
                                                    </SheetContent>
                                                </Sheet>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </CardContent>
                            <CardFooter class="justify-center border-t p-4">
                                <Button size="sm" variant="ghost" class="gap-1">
                                    <PlusCircle class="h-3.5 w-3.5" />
                                    Create a new price
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                    <div class="grid auto-rows-max items-start gap-4 lg:gap-8">
                        <Card>
                            <CardHeader>
                                <CardTitle>Plan Status</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-6">
                                    <div class="grid gap-3">
                                        <Label for="status">Status</Label>
                                        <Select v-model="form.active">
                                            <SelectTrigger
                                                id="status"
                                                aria-label="Select status"
                                            >
                                                <SelectValue
                                                    :placeholder="
                                                        product.active
                                                            ? 'Active'
                                                            : 'Inactive'
                                                    "
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="true">
                                                    Active
                                                </SelectItem>
                                                <SelectItem value="false">
                                                    Inactive
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-2 md:hidden">
                    <Button size="sm" @click="updateProduct"> Save </Button>
                </div>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
