<script lang="ts" setup>
import { cn } from "@/lib/utils";
import { Icon } from "@iconify/vue";
import { Link } from "@inertiajs/vue3";
import { buttonVariants } from "@/Components/ui/button";
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from "@/Components/ui/tooltip";

export interface LinkProp {
    title: string;
    label?: string;
    icon: string;
    variant: "default" | "ghost";
    route: string;
    current: boolean;
    method?: string;
}

interface NavProps {
    isCollapsed: boolean;
    links: LinkProp[];
}

defineProps<NavProps>();
</script>

<template>
    <div
        :data-collapsed="isCollapsed"
        class="group flex flex-col gap-4 py-2 data-[collapsed=true]:py-2"
    >
        <nav
            class="grid gap-1 px-2 group-[[data-collapsed=true]]:justify-center group-[[data-collapsed=true]]:px-2"
        >
            <template v-for="(link, index) of links">
                <Tooltip
                    v-if="isCollapsed"
                    :key="`1-${index}`"
                    :delay-duration="0"
                >
                    <TooltipTrigger as-child>
                        <Link
                            :href="link.route"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant: link.current
                                            ? 'default'
                                            : link.variant,
                                        size: 'sm',
                                    }),
                                    'h-9 w-9',

                                    link.current &&
                                        'dark:bg-muted dark:text-muted-foreground dark:hover:bg-muted dark:hover:text-white',
                                )
                            "
                        >
                            <Icon :icon="link.icon" class="size-4" />
                            <span class="sr-only">{{ link.title }}</span>
                        </Link>
                    </TooltipTrigger>
                    <TooltipContent
                        side="right"
                        class="flex items-center gap-4"
                    >
                        {{ link.title }}
                        <span
                            v-if="link.label"
                            class="ml-auto text-muted-foreground"
                        >
                            {{ link.label }}
                        </span>
                    </TooltipContent>
                </Tooltip>

                <Link
                    v-else
                    :key="`2-${index}`"
                    :href="link.route"
                    :class="
                        cn(
                            buttonVariants({
                                variant: link.current
                                    ? 'default'
                                    : link.variant,
                                size: 'sm',
                            }),
                            link.current &&
                                'dark:bg-muted dark:text-white dark:hover:bg-muted dark:hover:text-white',
                            'justify-start',
                        )
                    "
                >
                    <Icon :icon="link.icon" class="mr-2 size-4" />
                    {{ link.title }}
                    <span
                        v-if="link.label"
                        :class="
                            cn(
                                'ml-auto',
                                link.variant === 'default' &&
                                    'text-background dark:text-white',
                            )
                        "
                    >
                        {{ link.label }}
                    </span>
                </Link>
            </template>

            cdds
        </nav>
    </div>
</template>
