<script lang="ts" setup>
import { ref } from "vue";
import { cn } from "@/lib/utils";

import NavList, { type LinkProp } from "./NavList.vue";
import { Separator } from "@/Components/ui/separator";
import { TooltipProvider } from "@/Components/ui/tooltip";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

import {
    ResizableHandle,
    ResizablePanel,
    ResizablePanelGroup,
} from "@/Components/ui/resizable";

interface TenantProps {
    defaultLayout?: number[];
    defaultCollapsed?: boolean;
}

const props = withDefaults(defineProps<TenantProps>(), {
    defaultCollapsed: false,

    defaultLayout: () => [30],
});

const isCollapsed = ref(false);

function onCollapse() {
    isCollapsed.value = true;
}

function onExpand() {
    isCollapsed.value = false;
}
const links: LinkProp[] = [
    {
        title: "Dashboard",
        label: "",
        icon: "lucide:inbox",
        variant: "ghost",
        route: route("admin.dashboard"),
        current: route().current("admin.dashboard"),
    },
    {
        title: "Tenants",
        label: "972",
        icon: "lucide:user-2",
        variant: "ghost",
        route: route("tenants.index"),
        current: route().current("tenants.index"),
    },

    {
        title: "Payments",
        label: "",
        icon: "lucide:shopping-cart",
        variant: "ghost",
        route: route("payments.index"),
        current: route().current("payments.index"),
    },
    {
        title: "Plans",
        label: "6",
        icon: "lucide:archive",
        variant: "ghost",
        route: route("plans.index"),
        current: route().current("plans.index"),
    },
    {
        title: "Mails",
        label: "6",
        icon: "lucide:message-square",
        variant: "ghost",
        route: route("mails.index"),
        current: route().current("mails.index"),
    },
];

const links2: LinkProp[] = [
    {
        title: "Social",
        label: "972",
        icon: "lucide:user-2",
        variant: "ghost",
        route: "/social",
    },
    {
        title: "Updates",
        label: "342",
        icon: "lucide:alert-circle",
        variant: "ghost",
        route: "/updates",
    },
    {
        title: "Forums",
        label: "128",
        icon: "lucide:message-square",
        variant: "ghost",
        route: "/forums",
    },
    {
        title: "Shopping",
        label: "8",
        icon: "lucide:shopping-cart",
        variant: "ghost",
        route: "/shopping",
    },
    {
        title: "Promotions",
        label: "21",
        icon: "lucide:archive",
        variant: "ghost",
        route: "/promotions",
    },
];

const links3: LinkProp[] = [
    {
        title: "Log out",

        icon: "lucide:log-out",
        variant: "ghost",
        route: "/social",
    },
];
</script>

<template>
    <TooltipProvider :delay-duration="0">
        <ResizablePanelGroup
            style="height: h-lvh"
            id="resize-panel-group-1"
            direction="horizontal"
            class="h-lvh items-stretch"
        >
            <ResizablePanel
                id="resize-panel-1"
                :default-size="defaultLayout[0]"
                collapsible
                :min-size="15"
                :max-size="20"
                :class="
                    cn(
                        isCollapsed &&
                            'min-w-[50px] transition-all duration-300 ease-in-out',
                    )
                "
                @expand="onExpand"
                @collapse="onCollapse"
            >
                <div class="flex flex-col h-full justify-between">
                    <div
                        class="mb-1"
                        :class="
                            cn(
                                'flex h-[52px] items-center justify-center',
                                isCollapsed ? 'h-[52px]' : 'px-2',
                            )
                        "
                    >
                        <ApplicationLogo
                            class="w-10 h-10 fill-current text-gray-500"
                        />
                    </div>

                    <Separator />
                    <NavList :is-collapsed="isCollapsed" :links="links" />

                    <Separator />

                    <div class="flex-grow">
                        <NavList :is-collapsed="isCollapsed" :links="links2" />
                    </div>
                    <Separator />

                    <div class="mb-1">
                        <NavList :is-collapsed="isCollapsed" :links="links3" />
                    </div>
                </div>
            </ResizablePanel>
            <ResizableHandle id="resize-handle-1" with-handle />
            <ResizablePanel
                id="resize-panel-2"
                :default-size="defaultLayout[1]"
                :min-size="30"
            >
                <slot />
            </ResizablePanel>
        </ResizablePanelGroup>
    </TooltipProvider>
</template>
