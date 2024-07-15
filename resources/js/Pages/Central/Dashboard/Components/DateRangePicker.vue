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

const emit = defineEmits(["updateData"]);
const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});

const currentDate = new Date();
const date = ref({
    start: new Date(2024, 5, 10),
    end: addDays(startOfDay(currentDate), 0),
});

// Reset function
function resetDate() {
    date.value = {
        start: null,
        end: null,
    };
    emit("updateData", props.data); // Emit the original data to reset the chart
}

function filterData(startDate, endDate) {
    return props.data.filter((item) => {
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
            emit("updateData", filteredData);
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
        <Button @click="resetDate">Reset</Button>
    </div>
</template>
