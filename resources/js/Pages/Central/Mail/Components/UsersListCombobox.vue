<script setup lang="ts">
import { Check, ChevronsUpDown } from "lucide-vue-next";
import { ref, computed, defineEmits } from "vue";
import { cn } from "@/lib/utils";
import { Button } from "@/Components/ui/button";
import { Avatar, AvatarFallback } from "@/Components/ui/avatar";

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

const emit = defineEmits<{
    (e: "user-selected", user: User): void;
}>();

const open = ref(false);
const value = ref("");

// Função para obter todos os usuários de todos os tenants
const allUsers = computed(() => {
    return props.tenantsWithUsers.flatMap((tenant) => tenant.users);
});

const mailFallbackNames = computed(() => {
    const map = {};
    allUsers.value.forEach((user) => {
        map[user.id] =
            user?.name
                .split(" ")
                .map((chunk) => chunk[0])
                .join("") || "N/A";
    });
    return map;
});

// Função para tratar a seleção de um usuário
const handleSelectUser = (user: User) => {
    value.value = user.name;
    emit("user-selected", user);
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
                class="w-[200px] justify-between"
            >
                {{
                    value
                        ? allUsers.find((user) => user.name === value)?.name
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
                            :value="user.name"
                            @select="handleSelectUser(user)"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <Avatar>
                                        <AvatarFallback>
                                            {{
                                                mailFallbackNames[user.id] ||
                                                "N/A"
                                            }}
                                        </AvatarFallback>
                                    </Avatar>

                                    {{ user.name }}
                                </div>
                                <div>
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
                                </div>
                            </div>
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
