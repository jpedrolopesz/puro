<script setup lang="ts">
import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";
import PriceCreateForm from "../Components/Forms/PriceCreateForm.vue";
import ProductUpdateForm from "../Components/Forms/ProductUpdateForm.vue";
import DataTable from "../Components/DataTable/DataTable.vue";
import { columns } from "../Components/DataTable/Columns/priceColumns";

import { Head, Link } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";

import { ChevronLeft, PlusCircle } from "lucide-vue-next";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Separator } from "@/Components/ui/separator";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
    CardFooter,
} from "@/Components/ui/card";
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet";

const props = defineProps({
    product: {
        type: Object as () => any,
        required: true,
    },
});

const defaultPriceId = ref(
    props.product.prices.find(
        (price) => price.id === props.product.default_price,
    )?.id || null,
);
</script>

<template>
    <Head title="Product Details" />

    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-8 lg:m-20">
            <div class="mx-auto grid auto-rows-max gap-2">
                <header class="flex items-center gap-2">
                    <Link :href="route('products.index')">
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
                            product.active
                                ? 'text-white bg-gray-800/80'
                                : 'text-gray-800/80 bg-gray-400/40',
                        ]"
                    >
                        {{ product.active ? "Active" : "Inactive" }}
                    </Badge>

                    <div class="items-center ml-auto">
                        <Sheet>
                            <SheetTrigger
                                class="flex items-center justify-center"
                            >
                                <Button variant="outline" size="xs"
                                    >Edit Product</Button
                                >
                            </SheetTrigger>
                            <SheetContent>
                                <SheetHeader>
                                    <SheetTitle>Edit Product</SheetTitle>
                                    <SheetDescription
                                        ><Separator
                                    /></SheetDescription>
                                </SheetHeader>

                                <ProductUpdateForm :data="props.product" />
                            </SheetContent>
                        </Sheet>
                    </div>
                </header>

                <div class="grid gap-5 md:grid-cols-[1fr_250px] lg:grid-cols-4">
                    <Card class="md:col-span-4">
                        <CardHeader class="px-7">
                            <CardTitle>Prices</CardTitle>
                            <CardDescription>
                                You can only edit the values of prices without a
                                subscription. For other changes, it is
                                recommended to create a new price.
                                <a
                                    class="text-gray-600 text-xs after:content-['_ⓘ']"
                                    href="https://docs.stripe.com/products-prices/pricing-models#flat-rate"
                                    target="_blank"
                                ></a>
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <DataTable
                                :data="props.product.prices"
                                :priceDefault="defaultPriceId"
                                :columns="columns"
                            />
                        </CardContent>
                        <CardFooter class="justify-center border-t p-4">
                            <Sheet>
                                <SheetTrigger as-child>
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        class="gap-1"
                                    >
                                        <PlusCircle class="h-3.5 w-3.5" />
                                        Create a new price
                                    </Button>
                                </SheetTrigger>
                                <SheetContent>
                                    <SheetHeader>
                                        <SheetTitle
                                            >Create a new price</SheetTitle
                                        >
                                        <SheetDescription
                                            ><Separator
                                        /></SheetDescription>
                                    </SheetHeader>

                                    <PriceCreateForm :data="props.product" />
                                </SheetContent>
                            </Sheet>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
