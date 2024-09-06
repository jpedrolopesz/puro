<script setup lang="ts">
import type { Product, Price } from "./data/type";
import { useForm } from "@inertiajs/vue3";

import { ref, computed, watch } from "vue";
import draggable from "vuedraggable";
import { Switch } from "@/Components/ui/switch";
import { Label } from "@/Components/ui/label";
import { Button } from "@/Components/ui/button";
import { Separator } from "@/Components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import { Columns2, Columns3, Columns4 } from "lucide-vue-next";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/ui/accordion";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

const props = defineProps<{
    products: Product[];
}>();

const isGrabbing = ref(false);
const selectedTab = ref("monthly");
const productCount = ref<number>(3);

const columnIcons = {
    2: Columns2,
    3: Columns3,
    4: Columns4,
};

const form = useForm({
    products: [],
});

const saveOrder = () => {
    form.products = [
        ...selectedProducts.value.monthly,
        ...selectedProducts.value.yearly,
    ].map((product, index) => ({
        id: product.id,
        order: index + 1,
    }));

    form.post(route("products.updateOrder"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            console.log("Ordem dos produtos salva com sucesso");
        },
    });
};
const selectedProducts = ref<{
    monthly: (Product & { selectedPrice: Price | null })[];
    yearly: (Product & { selectedPrice: Price | null })[];
}>({
    monthly: [],
    yearly: [],
});

const availableProducts = ref<{
    monthly: (Product & { selectedPrice: Price | null })[];
    yearly: (Product & { selectedPrice: Price | null })[];
}>({
    monthly: [],
    yearly: [],
});

const showProducts = computed(() => ({
    monthly: selectedTab.value === "monthly",
    yearly: selectedTab.value === "annually",
}));

const handleTabChange = (value: string) => {
    selectedTab.value = value;
};

const initializeProducts = () => {
    const allProducts = props.products.map((product) => ({
        ...product,
        selectedPrice: product.prices.length > 0 ? product.prices[0] : null,
    }));

    ["monthly", "yearly"].forEach((type) => {
        selectedProducts.value[type] = allProducts.slice(0, productCount.value);
        availableProducts.value[type] = allProducts.slice(productCount.value);
    });
};

initializeProducts();

const updateProducts = (type: "monthly" | "yearly") => {
    const allProducts = [
        ...selectedProducts.value[type],
        ...availableProducts.value[type],
    ];
    selectedProducts.value[type] = allProducts.slice(0, productCount.value);
    availableProducts.value[type] = allProducts.slice(productCount.value);
};

watch(productCount, () => {
    updateProducts("monthly");
    updateProducts("yearly");
});

const onDragChange = (type: "monthly" | "yearly") => {
    updateProducts(type);
};

const removeProduct = (
    product: Product & { selectedPrice: Price | null },
    type: "monthly" | "yearly",
) => {
    selectedProducts.value[type] = selectedProducts.value[type].filter(
        (p) => p.id !== product.id,
    );
    availableProducts.value[type].push(product);
    updateProducts(type);
};

const formatPrice = (price: Price): string => {
    return new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: price.currency,
    }).format(price.unit_amount / 100);
};

const getIntervalLabel = (price: Price): string => {
    switch (price.recurring?.interval) {
        case "day":
            return "/dia";
        case "week":
            return "/semana";
        case "month":
            return "/mês";
        case "year":
            return "/ano";
        default:
            return "";
    }
};

const selectPrice = (
    product: Product & { selectedPrice: Price | null },
    price: Price,
) => {
    product.selectedPrice = price;
};
</script>

<template>
    <div class="border rounded-md">
        <div class="flex items-center justify-between m-3">
            <Tabs v-model="selectedTab" @update:modelValue="handleTabChange">
                <TabsList>
                    <TabsTrigger value="monthly">Monthly</TabsTrigger>
                    <TabsTrigger value="annually">Annually</TabsTrigger>
                </TabsList>
            </Tabs>

            <div class="flex items-center space-x-4">
                <Tabs
                    v-model="productCount"
                    @update:modelValue="
                        updateProducts('monthly');
                        updateProducts('yearly');
                    "
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
                    {{ form.processing ? "Salvando..." : "Salvar Ordem" }}
                </Button>
            </div>
        </div>
        <Separator />
        <div class="container mx-auto p-4">
            <template v-for="type in ['monthly', 'yearly']" :key="type">
                <div v-if="showProducts[type]" class="mb-6">
                    <div class="mb-4">
                        <draggable
                            v-model="selectedProducts[type]"
                            :group="{
                                name: type + 'Products',
                                pull: true,
                                put: true,
                            }"
                            item-key="id"
                            class="grid gap-4"
                            :class="{
                                'grid-cols-2': productCount === 2,
                                'grid-cols-3': productCount === 3,
                                'grid-cols-4': productCount === 4,
                            }"
                            @change="() => onDragChange(type)"
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
                                        <h4 class="font-semibold mb-2">
                                            Preço Selecionado:
                                        </h4>
                                        <div
                                            v-if="element.selectedPrice"
                                            class="p-2 bg-white border rounded shadow-sm"
                                        >
                                            <span class="text-sm font-medium">
                                                {{
                                                    formatPrice(
                                                        element.selectedPrice,
                                                    )
                                                }}{{
                                                    getIntervalLabel(
                                                        element.selectedPrice,
                                                    )
                                                }}
                                            </span>
                                        </div>
                                        <div
                                            v-else
                                            class="text-sm text-gray-500"
                                        >
                                            Nenhum preço selecionado
                                        </div>
                                        <Accordion
                                            type="single"
                                            collapsible
                                            class="w-full"
                                        >
                                            <AccordionItem value="item-1">
                                                <AccordionTrigger
                                                    >Alterar
                                                    Preço</AccordionTrigger
                                                >
                                                <AccordionContent>
                                                    <div class="space-y-2">
                                                        <div
                                                            v-for="price in element.prices"
                                                            :key="price.id"
                                                            class="flex justify-between items-center p-2 bg-white border rounded shadow-sm cursor-pointer"
                                                            @click="
                                                                selectPrice(
                                                                    element,
                                                                    price,
                                                                )
                                                            "
                                                        >
                                                            <span
                                                                class="text-sm font-medium"
                                                            >
                                                                {{
                                                                    formatPrice(
                                                                        price,
                                                                    )
                                                                }}{{
                                                                    getIntervalLabel(
                                                                        price,
                                                                    )
                                                                }}
                                                            </span>
                                                            <span
                                                                v-if="
                                                                    element
                                                                        .selectedPrice
                                                                        ?.id ===
                                                                    price.id
                                                                "
                                                                class="text-green-500"
                                                                >✓</span
                                                            >
                                                        </div>
                                                    </div>
                                                </AccordionContent>
                                            </AccordionItem>
                                        </Accordion>
                                    </CardContent>
                                </Card>
                            </template>
                        </draggable>
                    </div>
                    <div>
                        <Label class="text-lg font-semibold mb-2"
                            >Produtos Disponíveis</Label
                        >
                        <draggable
                            v-model="availableProducts[type]"
                            :group="{
                                name: type + 'Products',
                                pull: true,
                                put: true,
                            }"
                            item-key="id"
                            class="flex flex-col space-y-4"
                            @change="() => onDragChange(type)"
                        >
                            <template #item="{ element: product }">
                                <Card
                                    class="hover:bg-gray-100 transition-all duration-200"
                                >
                                    <CardHeader>
                                        <CardTitle>{{
                                            product.name
                                        }}</CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <h4 class="font-semibold mb-2">
                                            Preços:
                                        </h4>
                                        <div class="space-y-2">
                                            <div
                                                v-for="price in product.prices"
                                                :key="price.id"
                                                class="flex justify-between items-center p-2 bg-white border rounded shadow-sm cursor-pointer"
                                                @click="
                                                    selectPrice(product, price)
                                                "
                                            >
                                                <span
                                                    class="text-sm font-medium"
                                                >
                                                    {{ formatPrice(price)
                                                    }}{{
                                                        getIntervalLabel(price)
                                                    }}
                                                </span>
                                                <span
                                                    v-if="
                                                        product.selectedPrice
                                                            ?.id === price.id
                                                    "
                                                    class="text-green-500"
                                                    >✓</span
                                                >
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </template>
                        </draggable>
                    </div>
                </div>
            </template>
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
