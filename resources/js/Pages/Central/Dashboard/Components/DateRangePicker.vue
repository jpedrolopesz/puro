<script setup lang="ts">
import { addDays, format, startOfDay } from "date-fns";
import { Calendar as CalendarIcon } from "lucide-vue-next";

import { ref, watch } from "vue";
import { cn } from "@/lib/utils";
import { Button } from "@/Components/ui/button";
import { Calendar } from "@/Components/ui/calendar";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";

const currentDate = new Date();
const date = ref({
    start: new Date(2024, 5, 10),
    end: addDays(startOfDay(currentDate), 0),
});

// Simulated API data
const apiData = ref([
    { year: 2015, "Export Growth Rate": 0, "Import Growth Rate": 0 },
    { year: 2016, "Export Growth Rate": 0, "Import Growth Rate": 0 },
    { year: 2017, "Export Growth Rate": 0, "Import Growth Rate": 0 },
    { year: 2018, "Export Growth Rate": 0, "Import Growth Rate": 0 },
    { year: 2019, "Export Growth Rate": 0, "Import Growth Rate": 0 },
    { year: 2020, "Export Growth Rate": 0, "Import Growth Rate": 0 },
    { year: 2021, "Export Growth Rate": 0, "Import Growth Rate": 0 },
    {
        year: 2022,
        "Export Growth Rate": 4736.7,
        "Import Growth Rate": 3552.525,
    },
    {
        year: 2023,
        "Export Growth Rate": 2066.9,
        "Import Growth Rate": 1550.175,
    },
    { year: 2024, "Export Growth Rate": 576, "Import Growth Rate": 432 },
]);

// Função para filtrar os dados da API com base no intervalo de datas selecionado
function filterData(startDate, endDate) {
    return apiData.value.filter((item) => {
        const itemYear = item.year;
        return (
            itemYear >= startDate.getFullYear() &&
            itemYear <= endDate.getFullYear()
        );
    });
}

// Observa mudanças nas datas selecionadas e filtra os dados da API
watch(
    () => [date.value.start, date.value.end],
    ([newStartDate, newEndDate]) => {
        if (newStartDate && newEndDate) {
            const filteredData = filterData(newStartDate, newEndDate);
            console.log(filteredData); // Aqui você pode usar os dados filtrados como preferir
        }
    },
);
</script>

<template>
    <div :class="cn('grid gap-2', $attrs.class ?? '')">
        <Popover>
            <PopoverTrigger as-child>
                <Button
                    id="date"
                    :variant="'outline'"
                    :class="
                        cn(
                            'w-[260px] justify-start text-left font-normal',
                            !date && 'text-muted-foreground',
                        )
                    "
                >
                    <CalendarIcon class="mr-2 h-4 w-4" />

                    <span>
                        {{
                            date.start
                                ? date.end
                                    ? `${format(date.start, "LLL dd, y")} - ${format(date.end, "LLL dd, y")}`
                                    : format(date.start, "LLL dd, y")
                                : "Pick a date"
                        }}
                    </span>
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0" :align="'end'">
                <Calendar v-model.range="date" :columns="2" />
            </PopoverContent>
        </Popover>
    </div>
</template>
