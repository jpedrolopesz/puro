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

interface UserProps {
    defaultLayout?: number[];
    defaultCollapsed?: boolean;
}

const props = withDefaults(defineProps<UserProps>(), {
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
        route: route("tenant.dashboard"),
        current: route().current("tenant.dashboard"),
    },
    {
        title: "Conversation",
        label: "",
        icon: "lucide:inbox",
        variant: "ghost",
        route: route("tenant.conversation"),
        current: route().current("tenant.conversation"),
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
        route: route("tenant.logout.tenant"),
        method: "post",
        as: "button",
    },
];
</script>

<template>
    <TooltipProvider :delay-duration="0">
        <ResizablePanelGroup
            style="height: h-lvh"
            id="resize-panel-group-1"
            direction="horizontal"
            auto-save-id="any-id"
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
                    <!-- Início -->
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

                    <!-- Meio -->
                    <Separator />

                    <div class="flex-grow">
                        <NavList :is-collapsed="isCollapsed" :links="links2" />
                    </div>
                    <Separator />

                    <!-- Fim -->
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
                <slot class="h-lvh" />
            </ResizablePanel>
        </ResizablePanelGroup>
    </TooltipProvider>
</template>
