<script setup lang="ts">
import { ref } from "vue";
import draggable from "vuedraggable";
import { Switch } from "@/Components/ui/switch";
import { Label } from "@/Components/ui/label";
import { Button } from "@/Components/ui/button";
import { Separator } from "@/Components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";

import { Columns2, Columns3, Columns4, Move } from "lucide-vue-next";

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";

interface Product {
    id: number;
    name: string;
}

const props = defineProps({
    products: Array,
});

console.log(props.products);
const productSelect = ref(true);

const isGrabbing = ref(false);

const monthlyProductCount = ref<number>(3);
const yearlyProductCount = ref<number>(3);

const selectedMonthlyProducts = ref<Product[]>([]);
const availableMonthlyProducts = ref<Product[]>([]);
const selectedYearlyProducts = ref<Product[]>([]);
const availableYearlyProducts = ref<Product[]>([]);

// Initialize products
const initializeProducts = () => {
    const monthlyProducts = props.products;
    selectedMonthlyProducts.value = monthlyProducts.slice(
        0,
        monthlyProductCount.value,
    );
    availableMonthlyProducts.value = monthlyProducts.slice(
        monthlyProductCount.value,
    );

    const yearlyProducts = Array.from({ length: 10 }, (_, i) => ({
        id: i + 11,
        name: `Produto Anual ${i + 1}`,
    }));
    selectedYearlyProducts.value = yearlyProducts.slice(
        0,
        yearlyProductCount.value,
    );
    availableYearlyProducts.value = yearlyProducts.slice(
        yearlyProductCount.value,
    );
};

initializeProducts();

const updateMonthlyProducts = () => {
    const allProducts = [
        ...selectedMonthlyProducts.value,
        ...availableMonthlyProducts.value,
    ];
    selectedMonthlyProducts.value = allProducts.slice(
        0,
        monthlyProductCount.value,
    );
    availableMonthlyProducts.value = allProducts.slice(
        monthlyProductCount.value,
    );
};

const updateYearlyProducts = () => {
    const allProducts = [
        ...selectedYearlyProducts.value,
        ...availableYearlyProducts.value,
    ];
    selectedYearlyProducts.value = allProducts.slice(
        0,
        yearlyProductCount.value,
    );
    availableYearlyProducts.value = allProducts.slice(yearlyProductCount.value);
};

const onDragChange = () => {
    // Ensure the correct number of products are selected
    updateMonthlyProducts();
    updateYearlyProducts();
};

const removeProduct = (product: Product, type: "monthly" | "yearly") => {
    if (type === "monthly") {
        selectedMonthlyProducts.value = selectedMonthlyProducts.value.filter(
            (p) => p.id !== product.id,
        );
        availableMonthlyProducts.value.push(product);
        updateMonthlyProducts();
    } else {
        selectedYearlyProducts.value = selectedYearlyProducts.value.filter(
            (p) => p.id !== product.id,
        );
        availableYearlyProducts.value.push(product);
        updateYearlyProducts();
    }
};

const saveOrder = () => {
    console.log("Ordem dos produtos mensais:", selectedMonthlyProducts.value);
    console.log("Ordem dos produtos anuais:", selectedYearlyProducts.value);
};
</script>

<template>
    <AuthenticatedCentralLayout>
        <div class="flex items-center justify-between m-3">
            <Tabs default-value="All" class="">
                <TabsList>
                    <TabsTrigger value="All"> All </TabsTrigger>
                    <TabsTrigger value="Montly"> Monthly </TabsTrigger>
                    <TabsTrigger value="Annually"> Annually </TabsTrigger>
                </TabsList>
            </Tabs>

            <Tabs
                :default-value="monthlyProductCount.toString()"
                v-model="monthlyProductCount"
                @update:modelValue="updateMonthlyProducts"
            >
                <TabsList>
                    <TabsTrigger value="2"> <Columns2 /></TabsTrigger>
                    <TabsTrigger value="3"><Columns3 /> </TabsTrigger>
                    <TabsTrigger value="4"><Columns4 /> </TabsTrigger>
                </TabsList>
            </Tabs>
        </div>
        <Separator class="" />
        <div class="container mx-auto p-4">
            <div v-if="productSelect" class="mb-6">
                <div class="mb-4">
                    <draggable
                        v-model="selectedMonthlyProducts"
                        group="monthlyProducts"
                        item-key="id"
                        class="flex space-x-2"
                        @change="onDragChange"
                    >
                        <template #item="{ element }" class="">
                            <Card
                                class="hover:bg-gray-100 transition-all duration-200"
                                :class="{
                                    'cursor-grab': !isGrabbing,
                                    'cursor-grabbing': isGrabbing,
                                }"
                                @mousedown="isGrabbing = true"
                                @mouseup="isGrabbing = false"
                                @mouseleave="isGrabbing = false"
                            >
                                <CardHeader>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <CardTitle>
                                            {{ element.name }}</CardTitle
                                        >
                                    </div>
                                    <CardDescription>
                                        Ideal for individuals that need a custom
                                        solution with custom
                                        tools.</CardDescription
                                    >
                                </CardHeader>

                                <Separator class="my-4" />
                                <CardContent class="grid gap-2">
                                    <div class="text-slate-800 font-bold mb-4">
                                        <span class="text-3xl"> $99</span>
                                        <span
                                            class="text-slate-500 font-medium text-sm"
                                        >
                                            /mensal</span
                                        >
                                    </div>

                                    <div>
                                        <div
                                            class="mb-4 grid grid-cols-[25px_minmax(0,1fr)]"
                                        >
                                            <svg
                                                class="w-3 h-3 shrink-0 fill-current text-emerald-500 mr-2"
                                                viewBox="0 0 12 12"
                                            >
                                                <path
                                                    d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z"
                                                />
                                            </svg>
                                            <div class="space-y-1">
                                                <p
                                                    class="text-sm font-medium leading-none"
                                                ></p>
                                            </div>
                                        </div></div
                                ></CardContent>
                            </Card>
                        </template>
                    </draggable>
                </div>
                <div>
                    <Label class="text-lg font-semibold mb-2">
                        Produtos Disponíveis
                    </Label>
                    <draggable
                        v-model="availableMonthlyProducts"
                        group="monthlyProducts"
                        item-key="id"
                        class="flex space-x-2"
                        @change="onDragChange"
                    >
                        <template
                            #item="{ element }"
                            class="grid gap-4 md:grid-cols-2 lg:grid-cols-4"
                        >
                            <Card
                                class="hover:bg-gray-100 transition-all duration-200"
                                :class="{
                                    'cursor-grab': !isGrabbing,
                                    'cursor-grabbing': isGrabbing,
                                }"
                                @mousedown="isGrabbing = true"
                                @mouseup="isGrabbing = false"
                                @mouseleave="isGrabbing = false"
                            >
                                <CardHeader
                                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                                >
                                    <CardTitle>{{ element.name }}</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div class="text-2xl font-bold">+2350</div>
                                    <p class="text-xs text-muted-foreground">
                                        +180.1% from last month
                                    </p>
                                </CardContent>
                            </Card>
                        </template>
                    </draggable>
                </div>
            </div>

            <div v-else class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Produtos Anuais</h2>
                <div class="flex items-center mb-2">
                    <label class="mr-2">Número de produtos:</label>
                    <select
                        v-model="yearlyProductCount"
                        class="border p-1 rounded"
                        @change="updateYearlyProducts"
                    >
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">
                        Produtos Selecionados
                    </h3>
                    <draggable
                        v-model="selectedYearlyProducts"
                        group="yearlyProducts"
                        item-key="id"
                        class="flex space-x-2"
                        @change="onDragChange"
                    >
                        <template #item="{ element }">
                            <div
                                class="bg-green-100 p-2 rounded flex justify-between items-center"
                            >
                                <span>{{ element.name }}</span>
                                <button
                                    @click="removeProduct(element, 'yearly')"
                                    class="text-red-500"
                                >
                                    &times;
                                </button>
                            </div>
                        </template>
                    </draggable>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">
                        Produtos Disponíveis
                    </h3>
                    <draggable
                        v-model="availableYearlyProducts"
                        group="yearlyProducts"
                        item-key="id"
                        class="flex space-x-2"
                        @change="onDragChange"
                    >
                        <template #item="{ element }">
                            <div
                                class="bg-gray-100 p-2 rounded flex justify-between items-center"
                            >
                                <span>{{ element.name }}</span>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>

            <Button size="sm" @click="saveOrder"> Salvar Ordem </Button>
        </div>
    </AuthenticatedCentralLayout>
</template>

<style scoped>
.cursor-grab {
    cursor: grab !important;
}
.cursor-grabbing {
    cursor: grabbing !important;
}
</style>
