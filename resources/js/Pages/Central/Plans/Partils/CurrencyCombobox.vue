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
import { Input } from "@/Components/ui/input";

import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";

const currencys = [
    {
        value: "usd",
        label: "US Dollar (USD)",
        abbreviation: "USD",
        symbol: "$",
    },
    { value: "eur", label: "Euro (EUR)", abbreviation: "EUR", symbol: "€" },
    {
        value: "jpy",
        label: "Japanese Yen (JPY)",
        abbreviation: "JPY",
        symbol: "¥",
    },
    {
        value: "gbp",
        label: "British Pound (GBP)",
        abbreviation: "GBP",
        symbol: "£",
    },
    {
        value: "aud",
        label: "Australian Dollar (AUD)",
        abbreviation: "AUD",
        symbol: "$",
    },
    {
        value: "cad",
        label: "Canadian Dollar (CAD)",
        abbreviation: "CAD",
        symbol: "$",
    },
    {
        value: "chf",
        label: "Swiss Franc (CHF)",
        abbreviation: "CHF",
        symbol: "CHF",
    },
    {
        value: "cny",
        label: "Chinese Yuan (CNY)",
        abbreviation: "CNY",
        symbol: "¥",
    },
    {
        value: "sek",
        label: "Swedish Krona (SEK)",
        abbreviation: "SEK",
        symbol: "kr",
    },
    {
        value: "nzd",
        label: "New Zealand Dollar (NZD)",
        abbreviation: "NZD",
        symbol: "$",
    },
    {
        value: "mxn",
        label: "Mexican Peso (MXN)",
        abbreviation: "MXN",
        symbol: "$",
    },
    {
        value: "sgd",
        label: "Singapore Dollar (SGD)",
        abbreviation: "SGD",
        symbol: "$",
    },
    {
        value: "hkd",
        label: "Hong Kong Dollar (HKD)",
        abbreviation: "HKD",
        symbol: "$",
    },
    {
        value: "inr",
        label: "Indian Rupee (INR)",
        abbreviation: "INR",
        symbol: "₹",
    },
    {
        value: "brl",
        label: "Brazilian Real (BRL)",
        abbreviation: "BRL",
        symbol: "R$",
    },
    {
        value: "zar",
        label: "South African Rand (ZAR)",
        abbreviation: "ZAR",
        symbol: "R",
    },
    {
        value: "rub",
        label: "Russian Ruble (RUB)",
        abbreviation: "RUB",
        symbol: "₽",
    },
    {
        value: "try",
        label: "Turkish Lira (TRY)",
        abbreviation: "TRY",
        symbol: "₺",
    },
    {
        value: "ars",
        label: "Argentine Peso (ARS)",
        abbreviation: "ARS",
        symbol: "$",
    },
    {
        value: "krw",
        label: "South Korean Won (KRW)",
        abbreviation: "KRW",
        symbol: "₩",
    },
];

const open = ref(false);
const value = ref("usd");
</script>

<template>
    <div class="flex items-center mx-auto">
        <div class="w-full">
            <Input
                class="rounded-none rounded-l-md w-full"
                type="number"
                :placeholder="
                    value
                        ? currencys.find((currency) => currency.value === value)
                              ?.symbol
                        : 'Select'
                "
            />
        </div>
        <div>
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
                                ? currencys.find(
                                      (currency) => currency.value === value,
                                  )?.abbreviation
                                : "Select"
                        }}
                        <ChevronsUpDown
                            class="ml-2 h-4 w-4 shrink-0 opacity-50"
                        />
                    </Button>
                </PopoverTrigger>
                <PopoverContent class="w-[200px] p-0">
                    <Command>
                        <CommandInput
                            class="h-9"
                            placeholder="Search currency..."
                        />
                        <CommandEmpty>No currency found.</CommandEmpty>
                        <CommandList>
                            <CommandGroup>
                                <CommandItem
                                    v-for="currency in currencys"
                                    :key="currency.value"
                                    :value="currency.value"
                                    @select="
                                        (ev) => {
                                            if (
                                                typeof ev.detail.value ===
                                                'string'
                                            ) {
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
        </div>
    </div>
</template>
