<script setup lang="ts">
import { Check, ChevronsUpDown } from "lucide-vue-next";
import { ref, computed } from "vue";
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

const props = defineProps<{
    tenantsWithUsers: Tenant[];
}>();

const open = ref(false);
const value = ref("");

// Função para obter todos os usuários de todos os tenants
const allUsers = computed(() => {
    return props.tenantsWithUsers.flatMap((tenant) => tenant.users);
});
console.log(allUsers.value);
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-[200px] justify-between"
            >
                {{
                    value
                        ? allUsers.find((user) => user.id === value)?.name
                        : "Select user..."
                }}

                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[200px] p-0">
            <Command v-model="value">
                <CommandInput placeholder="Search user..." />
                <CommandEmpty>No user found.</CommandEmpty>
                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="user in allUsers"
                            :key="user.id"
                            :value="user.id"
                            @select="open = false"
                        >
                            <Check
                                :class="
                                    cn(
                                        'mr-2 h-4 w-4',
                                        value === user.id
                                            ? 'opacity-100'
                                            : 'opacity-0',
                                    )
                                "
                            />
                            {{ user.name }}
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
