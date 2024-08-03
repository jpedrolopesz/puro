<script setup lang="ts">
import { ref, defineEmits } from "vue";
import { Check, ChevronsUpDown } from "lucide-vue-next";

import { cn } from "@/lib/utils";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";

import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/Components/ui/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";

// Defina suas opções aqui
const recurrings = [
    { value: "daily", label: "Daily" },
    { value: "weekly", label: "Weekly" },
    { value: "monthly", label: "Monthly" },
    { value: "quarterly", label: "Quarterly" },
    { value: "semiannual", label: "Semiannual" },
    { value: "annual", label: "Annual" },
];

const props = defineProps({
    modelValue: String,
    "onUpdate:modelValue": Function,
});

const emit = defineEmits(["update:modelValue"]);

const open = ref(false);
const selectedValue = ref(props.modelValue || "");

// Atualize o valor selecionado e emita o evento de atualização
const updateValue = (newValue: string) => {
    selectedValue.value = newValue;
    emit("update:modelValue", newValue);
    open.value = false;
};
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-full justify-between"
            >
                {{
                    selectedValue
                        ? recurrings.find(
                              (recurring) => recurring.value === selectedValue,
                          )?.label
                        : "Select recurring..."
                }}
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[300px] p-0">
            <Command>
                <CommandInput placeholder="Search recurring..." />
                <CommandEmpty>Nothing found.</CommandEmpty>
                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="recurring in recurrings"
                            :key="recurring.value"
                            :value="recurring.value"
                            @select="() => updateValue(recurring.value)"
                        >
                            {{ recurring.label }}
                            <Check
                                :class="
                                    cn(
                                        'ml-auto h-4 w-4',
                                        selectedValue === recurring.value
                                            ? 'opacity-100'
                                            : 'opacity-0',
                                    )
                                "
                            />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
