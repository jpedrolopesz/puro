<script lang="ts" setup>
import { Search } from "lucide-vue-next";
import type { ChatProps, Conversation, Tenant, Message } from "../data/types";

import { computed, ref } from "vue";
import { refDebounced } from "@vueuse/core";
import { MailPlus } from "lucide-vue-next";
import { usePage } from "@inertiajs/vue3";

import ConversationList from "./ConversationList.vue";
import ConversationDisplay from "./ConversationDisplay.vue";
import { Separator } from "@/Components/ui/separator";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import { TooltipProvider } from "@/Components/ui/tooltip";
import {
    ResizableHandle,
    ResizablePanel,
    ResizablePanelGroup,
} from "@/Components/ui/resizable";

const props = withDefaults(defineProps<ChatProps>(), {
    defaultCollapsed: false,
    defaultLayout: () => [30, 40],
});

const { auth } = usePage().props;
const isCollapsed = ref(props.defaultCollapsed);
const selectedConversation = ref<string | undefined>(undefined);
const searchValue = ref("");
const debouncedSearch = refDebounced(searchValue, 250);

const filteredConversationList = computed(() => {
    let output: Conversation[] = [];
    const searchValue = debouncedSearch.value?.trim();
    if (!searchValue) {
        output = props.conversations;
    } else {
        output = props.conversations.filter((item) => {
            return (
                item.participant.name.includes(debouncedSearch.value) ||
                item.participant.email.includes(debouncedSearch.value) ||
                item.subject.includes(debouncedSearch.value)
            );
        });
    }
    return output;
});

const unreadConversationList = computed(() =>
    filteredConversationList.value.filter((conversation) =>
        conversation.messages.some(
            (message) => !message.read && message.sender_id !== auth.user.id,
        ),
    ),
);

const selectedConversationData = computed(() =>
    props.conversations.find(
        (conversation) => conversation.id === selectedConversation.value,
    ),
);

function createNewConversation() {
    selectedConversation.value = undefined;
}

function handleMessageSent(conversationId: string) {
    selectedConversation.value = conversationId;
}

function onCollapse() {
    isCollapsed.value = true;
}

function onExpand() {
    isCollapsed.value = false;
}
</script>

<template>
    <TooltipProvider :delay-duration="0">
        <ResizablePanelGroup
            id="resize-panel-group-1"
            direction="horizontal"
            class="max-h-full items-stretch"
        >
            <ResizablePanel
                id="resize-panel-1"
                :default-size="defaultLayout[1]"
                :min-size="30"
            >
                <Tabs default-value="all">
                    <div class="flex items-center px-4 py-2">
                        <h1 class="text-xl font-bold ml-4">Inbox</h1>
                        <TabsList class="ml-auto">
                            <TabsTrigger
                                value="all"
                                class="text-zinc-600 dark:text-zinc-200"
                            >
                                All mail
                            </TabsTrigger>
                            <TabsTrigger
                                value="unread"
                                class="text-zinc-600 dark:text-zinc-200"
                            >
                                Unread
                            </TabsTrigger>
                        </TabsList>
                    </div>
                    <Separator class="my-1" />
                    <div
                        class="grid grid-cols-5 gap-2 bg-background/95 p-4 backdrop-blur supports-[backdrop-filter]:bg-background/60"
                    >
                        <form class="col-span-4">
                            <div class="relative">
                                <Search
                                    class="absolute left-2 top-2.5 size-4 text-muted-foreground"
                                />
                                <Input
                                    v-model="searchValue"
                                    placeholder="Search"
                                    class="pl-8"
                                />
                            </div>
                        </form>
                        <div>
                            <Button
                                class="w-full"
                                @click="createNewConversation"
                            >
                                <MailPlus class="h-4 w-4 shrink-0" />
                            </Button>
                        </div>
                    </div>
                    <div class="p-4"></div>
                    <TabsContent value="all" class="m-0">
                        <ConversationList
                            v-model:selected-conversation="selectedConversation"
                            :items="filteredConversationList"
                        />
                    </TabsContent>
                    <TabsContent value="unread" class="m-0">
                        <ConversationList
                            v-model:selected-conversation="selectedConversation"
                            :items="unreadConversationList"
                        />
                    </TabsContent>
                </Tabs>
            </ResizablePanel>
            <ResizableHandle id="resiz-handle-2" with-handle />
            <ResizablePanel
                id="resize-panel-2"
                :default-size="defaultLayout[2]"
            >
                <ConversationDisplay
                    v-model:selected-conversation="selectedConversation"
                    :conversation="selectedConversationData || null"
                    :conversationParticipants="conversationParticipants"
                    @message-sent="handleMessageSent"
                />
            </ResizablePanel>
        </ResizablePanelGroup>
        <Separator />
    </TooltipProvider>
</template>
