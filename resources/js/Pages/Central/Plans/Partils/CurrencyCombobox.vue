<script setup lang="ts">
import { ref } from "vue";
import { Check, ChevronsUpDown } from "lucide-vue-next";

import { cn } from "@/lib/utils";
import { Button } from "@/Components/ui/button";
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

const currencys = [
    { value: "usd", label: "USD" },
    { value: "real", label: "Real" },
    { value: "pesoargentino", label: "Peso Argentino" },
];

const open = ref(false);
const value = ref("");
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="rounded-none rounded-r-md w-24 justify-between"
            >
                {{
                    value
                        ? currencys.find((currency) => currency.value === value)
                              ?.label
                        : "Select"
                }}
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[200px] p-0">
            <Command>
                <CommandInput class="h-9" placeholder="Search currency..." />
                <CommandEmpty>No currency found.</CommandEmpty>
                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="currency in currencys"
                            :key="currency.value"
                            :value="currency.value"
                            @select="
                                (ev) => {
                                    if (typeof ev.detail.value === 'string') {
                                        value = ev.detail.value;
                                    }
                                    open = false;
                                }
                            "
                        >
                            {{ currency.label }}
                            <Check
                                :class="
                                    cn(
                                        'ml-auto h-4 w-4',
                                        value === currency.value
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
