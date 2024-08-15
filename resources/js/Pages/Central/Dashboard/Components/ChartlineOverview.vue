<script setup lang="ts">
import { LineChart } from "@/Components/ui/chart-line";
import { ChartTooltip } from "@/Components/ui/chart";

import { defineProps, PropType } from "vue";

interface DataItem {
    [key: string]: number;
}

const props = defineProps({
    data: {
        type: Array as PropType<DataItem[]>,
        required: true,
    },
});

const extractYears = (data: DataItem[]): string[] => {
    const yearsSet = new Set<string>();

    data.forEach((item) => {
        Object.keys(item)
            .filter((key) => /^\d{4}$/.test(key))
            .forEach((year) => yearsSet.add(year));
    });

    return Array.from(yearsSet).sort();
};

const generateColors = (years: string[]): string[] => {
    const currentYear = new Date().getFullYear().toString();
    const colors = {
        current: "black",
        other: "lightgray",
    };

    return years.map((year) =>
        year === currentYear ? colors.current : colors.other,
    );
};

const data = props.data;
const categories = extractYears(data);
const colors = generateColors(categories);
</script>

<template>
    <LineChart
        :data="data"
        index="month"
        :colors="colors"
        :categories="categories"
        :filterOpacity="0"
        :y-formatter="
            (tick: number | string) => {
                return typeof tick === 'number' ? `$${tick.toFixed(2)}` : '';
            }
        "
        :custom-tooltip="ChartTooltip"
    />
</template>
