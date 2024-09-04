<template>
    <AuthenticatedCentralLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Organização de Produtos</h1>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Produtos Mensais</h2>
                <div class="flex items-center mb-2">
                    <label class="mr-2">Número de produtos:</label>
                    <select
                        v-model="monthlyProductCount"
                        class="border p-1 rounded"
                        @change="updateMonthlyProducts"
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
                        v-model="selectedMonthlyProducts"
                        group="monthlyProducts"
                        item-key="id"
                        class="flex space-x-2"
                        @change="onDragChange"
                    >
                        <template #item="{ element }">
                            <div
                                class="bg-blue-100 p-2 rounded flex justify-between items-center"
                            >
                                <span>{{ element.name }}</span>
                                <button
                                    @click="removeProduct(element, 'monthly')"
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
                        v-model="availableMonthlyProducts"
                        group="monthlyProducts"
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

            <div class="mb-6">
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

            <button
                @click="saveOrder"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Salvar Ordem
            </button>
        </div>
    </AuthenticatedCentralLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import draggable from "vuedraggable";
import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";

interface Product {
    id: number;
    name: string;
}

const monthlyProductCount = ref<number>(3);
const yearlyProductCount = ref<number>(3);

const selectedMonthlyProducts = ref<Product[]>([]);
const availableMonthlyProducts = ref<Product[]>([]);
const selectedYearlyProducts = ref<Product[]>([]);
const availableYearlyProducts = ref<Product[]>([]);

// Initialize products
const initializeProducts = () => {
    const monthlyProducts = Array.from({ length: 10 }, (_, i) => ({
        id: i + 1,
        name: `Produto Mensal ${i + 1}`,
    }));
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
