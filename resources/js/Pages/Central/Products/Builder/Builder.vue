<script setup lang="ts">
import { ref, computed, watch, nextTick } from "vue";
import { useForm, Head } from "@inertiajs/vue3";
import draggable from "vuedraggable";
import { Switch } from "@/Components/ui/switch";
import { Label } from "@/Components/ui/label";
import { Button } from "@/Components/ui/button";
import { Separator } from "@/Components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import { Columns2, Columns3, Columns4 } from "lucide-vue-next";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

interface Price {
    id: string;
    currency: string;
    unit_amount: number;
    recurring?: {
        interval: "month" | "year";
    };
}

interface Product {
    id: string;
    name: string;
    prices: Price[];
    metadata: {
        monthly_price_id: string;
        yearly_price_id: string;
        order: string;
        column_count: number;
    };
}

const props = defineProps<{
    products: Product[];
}>();

const isGrabbing = ref(false);
const selectedTab = ref("monthly");
const productCount = ref<number>(
    Number(props.products[0]?.metadata.column_count) || 2,
);

const columnIcons = {
    2: Columns2,
    3: Columns3,
    4: Columns4,
};

const form = useForm<{ products: any[] }>({
    products: [],
});

const selectedProducts = ref<Product[]>([]);
const availableProducts = ref<Product[]>([]);

const showProducts = computed(() => ({
    monthly: selectedTab.value === "monthly",
    yearly: selectedTab.value === "annually",
}));

const handleTabChange = (value: string) => {
    selectedTab.value = value;
};

const initializeProducts = () => {
    const allProducts = props.products.sort(
        (a, b) => parseInt(a.metadata.order) - parseInt(b.metadata.order),
    );

    selectedProducts.value = allProducts.slice(0, productCount.value);
    availableProducts.value = allProducts.slice(productCount.value);
};

initializeProducts();

const updateProducts = () => {
    nextTick(() => {
        const allProducts = [
            ...selectedProducts.value,
            ...availableProducts.value,
        ];
        selectedProducts.value = allProducts.slice(0, productCount.value);
        availableProducts.value = allProducts.slice(productCount.value);
    });
};

watch(productCount, (newValue, oldValue) => {
    if (newValue !== oldValue) {
        updateProducts();
        saveOrder();
    }
});

const onDragChange = () => {
    updateProducts();
    saveOrder();
};

const formatPrice = (price: Price): string => {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: price.currency,
    }).format(price.unit_amount / 100);
};

const getIntervalLabel = (price: Price): string => {
    switch (price.recurring?.interval) {
        case "month":
            return "/ monthly";
        case "year":
            return "/ annual";
        default:
            return "";
    }
};

const selectPrice = (
    product: Product,
    price: Price,
    type: "monthly" | "yearly",
) => {
    const currentPriceId =
        type === "monthly"
            ? product.metadata.monthly_price_id
            : product.metadata.yearly_price_id;
    if (currentPriceId !== price.id) {
        if (type === "monthly") {
            product.metadata.monthly_price_id = price.id;
        } else {
            product.metadata.yearly_price_id = price.id;
        }
        saveOrder();
    }
};

const saveOrder = () => {
    nextTick(() => {
        const orderedProducts = selectedProducts.value.map(
            (product, index) => ({
                id: product.id,
                order: index + 1,
                columnCount: productCount.value,
                metadata: {
                    monthly_price_id: product.metadata.monthly_price_id,
                    yearly_price_id: product.metadata.yearly_price_id,
                },
            }),
        );

        form.products = orderedProducts;

        form.post(route("products.updateOrder"), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (response) => {},
            onError: (errors) => {
                console.error(
                    "Error saving product order and metadata",
                    errors,
                );
            },
        });
    });
};

const getSelectedPrice = (
    product: Product,
    type: "monthly" | "yearly",
): Price | undefined => {
    const priceId =
        type === "monthly"
            ? product.metadata.monthly_price_id
            : product.metadata.yearly_price_id;
    return product.prices.find((price) => price.id === priceId);
};
</script>

<template>
    <Head title="Builder Rearrange" />

    <div>
        <div class="flex items-center justify-between">
            <Tabs v-model="selectedTab" @update:modelValue="handleTabChange">
                <TabsList>
                    <TabsTrigger value="monthly">Monthly</TabsTrigger>
                    <TabsTrigger value="annually">Annual</TabsTrigger>
                </TabsList>
            </Tabs>

            <div class="flex items-center space-x-4">
                <Tabs
                    v-model="productCount"
                    @update:modelValue="updateProducts"
                >
                    <TabsList>
                        <TabsTrigger
                            v-for="count in [2, 3, 4]"
                            :key="count"
                            :value="count"
                        >
                            <component :is="columnIcons[count]" />
                        </TabsTrigger>
                    </TabsList>
                </Tabs>
                <Button
                    size="sm"
                    @click="saveOrder"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Saving..." : "Save Order" }}
                </Button>
            </div>
        </div>

        <div class="my-4">
            <div class="mb-6">
                <div class="mb-4">
                    <draggable
                        v-model="selectedProducts"
                        group="products"
                        item-key="id"
                        class="grid gap-4"
                        :class="{
                            'grid-cols-2': productCount === 2,
                            'grid-cols-3': productCount === 3,
                            'grid-cols-4': productCount === 4,
                        }"
                    >
                        <template #item="{ element }">
                            <Card
                                class="w-full"
                                :class="[
                                    'hover:bg-gray-100 transition-all duration-200',
                                    {
                                        'cursor-grabbing': isGrabbing,
                                        'cursor-grab': !isGrabbing,
                                    },
                                ]"
                                @mousedown="isGrabbing = true"
                                @mouseup="isGrabbing = false"
                                @mouseleave="isGrabbing = false"
                            >
                                <CardHeader>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <CardTitle class="text-2xl">{{
                                            element.name
                                        }}</CardTitle>
                                    </div>
                                    <CardDescription class="flex-col">
                                        <span>Id: {{ element.id }} </span>
                                    </CardDescription>
                                </CardHeader>
                                <Separator class="my-4" />
                                <CardContent class="grid gap-2">
                                    <h4 class="font-semibold mb-2">Prices:</h4>
                                    <div v-if="showProducts.monthly">
                                        <div
                                            v-for="price in element.prices.filter(
                                                (p) =>
                                                    p.recurring?.interval ===
                                                    'month',
                                            )"
                                            :key="price.id"
                                            class="flex justify-between items-center p-2 m-1 bg-white border rounded"
                                        >
                                            <span class="text-sm font-medium">
                                                {{ formatPrice(price)
                                                }}{{ getIntervalLabel(price) }}
                                            </span>
                                            <Switch
                                                :checked="
                                                    element.metadata
                                                        .monthly_price_id ===
                                                    price.id
                                                "
                                                @update:checked="
                                                    () =>
                                                        selectPrice(
                                                            element,
                                                            price,
                                                            'monthly',
                                                        )
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div
                                            v-for="price in element.prices.filter(
                                                (p) =>
                                                    p.recurring?.interval ===
                                                    'year',
                                            )"
                                            :key="price.id"
                                            class="flex justify-between items-center p-2 m-1 bg-white border rounded"
                                        >
                                            <span class="text-sm font-medium">
                                                {{ formatPrice(price)
                                                }}{{ getIntervalLabel(price) }}
                                            </span>
                                            <Switch
                                                :checked="
                                                    element.metadata
                                                        .yearly_price_id ===
                                                    price.id
                                                "
                                                @update:checked="
                                                    () =>
                                                        selectPrice(
                                                            element,
                                                            price,
                                                            'yearly',
                                                        )
                                                "
                                            />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </template>
                    </draggable>
                </div>
                <div>
                    <Label class="text-lg font-semibold mb-2"
                        >Available Products</Label
                    >
                    <draggable
                        v-model="availableProducts"
                        group="products"
                        item-key="id"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4"
                    >
                        <template #item="{ element: product }">
                            <Card
                                class="hover:bg-gray-100 transition-all duration-200"
                            >
                                <CardHeader>
                                    <CardTitle>{{ product.name }}</CardTitle>
                                </CardHeader>
                                <Separator />

                                <CardContent>
                                    <h4 class="font-semibold my-4">Prices:</h4>
                                    <div v-if="showProducts.monthly">
                                        <div
                                            v-for="price in product.prices.filter(
                                                (p) =>
                                                    p.recurring?.interval ===
                                                    'month',
                                            )"
                                            :key="price.id"
                                            class="flex justify-between items-center p-2 m-1 bg-white border rounded"
                                        >
                                            <span class="text-sm font-medium">
                                                {{ formatPrice(price)
                                                }}{{ getIntervalLabel(price) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div
                                            v-for="price in product.prices.filter(
                                                (p) =>
                                                    p.recurring?.interval ===
                                                    'year',
                                            )"
                                            :key="price.id"
                                            class="flex justify-between items-center p-2 bg-white border rounded shadow-sm"
                                        >
                                            <span class="text-sm font-medium">
                                                {{ formatPrice(price)
                                                }}{{ getIntervalLabel(price) }}
                                            </span>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.cursor-grab {
    cursor: grab !important;
}
.cursor-grabbing {
    cursor: grabbing !important;
}
</style>
