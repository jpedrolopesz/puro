<script setup lang="ts">
import { LineChart } from "@/Components/ui/chart-line";
import { ChartTooltip } from "@/Components/ui/chart";

import { defineProps } from "vue";

const props = defineProps({
    gross_volume: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <LineChart
        :data="gross_volume"
        index="year"
        :categories="['Volume bruto', 'Volume líquido de vendas']"
        :colors="['#3B82F6', '#EF4444']"
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
