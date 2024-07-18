<script setup lang="ts">
import { LineChart } from "@/Components/ui/chart-line";
import { ChartTooltip } from "@/Components/ui/chart";

import { defineProps } from "vue";

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});

const data = props.data;

const extractYears = (data) => {
    const yearsSet = new Set();

    data.forEach((item) => {
        Object.keys(item)
            .filter((key) => /^\d{4}$/.test(key))
            .forEach((year) => yearsSet.add(year));
    });

    return [...yearsSet].sort();
};

// Função para gerar cores diferentes com base nos anos
const generateColors = (years) => {
    const colors = ["#3B82F6", "#EF4444", "#10B981"]; // Cores disponíveis

    return years.map((year, index) => colors[index % colors.length]);
};

const categories = extractYears(data);
const colors = generateColors(categories);
</script>

<template>
    <LineChart
        :data="data"
        index="month"
        :categories="categories"
        :colors="colors"
        :y-formatter="
            (tick, i) => {
                return typeof tick === 'number'
                    ? `$ ${new Intl.NumberFormat('us').format(tick).toString()}`
                    : '';
            }
        "
        :custom-tooltip="ChartTooltip"
    />
</template>
