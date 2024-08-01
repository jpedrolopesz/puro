<script setup lang="ts">
import { ref } from "vue";
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

const recurrings = [
    { value: "daily", label: "Daily" },
    { value: "weekly", label: "Weekly" },
    { value: "monthly", label: "Monthly" },
    { value: "quarterly", label: "Quarterly" },
    { value: "semiannual", label: "Semiannual" },
    { value: "annual", label: "Annual" },
];

const open = ref(false);
const value = ref("");

console.log(value);
</script>

<template>
    <Input class="rounded-none rounded-l-md w-full" type="text" />
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-full justify-between"
            >
                {{
                    value
                        ? recurrings.find(
                              (recurring) => recurring.value === value,
                          )?.value
                        : "Select recurring..."
                }}
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[300px] p-0">
            <Command>
                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="recurring in recurrings"
                            :key="recurring.value"
                            :value="recurring.value"
                            @select="
                                (ev) => {
                                    if (typeof ev.detail.value === 'string') {
                                        value = ev.detail.value;
                                    }
                                    open = false;
                                }
                            "
                        >
                            {{ recurring.label }}
                            <Check
                                :class="
                                    cn(
                                        'ml-auto h-4 w-4',
                                        value === recurring.value
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
