<script lang="ts" setup>
import { formatDistanceToNow } from "date-fns";
import { usePage } from "@inertiajs/vue3";

import { ScrollArea } from "@/Components/ui/scroll-area";
import { cn } from "@/lib/utils";
import { Badge } from "@/Components/ui/badge";
import { computed } from "vue";

interface ConversationListProps {
    items: Conversation[];
}

const props = defineProps<ConversationListProps>();

const selectedConversation = defineModel<string>("selectedConversation", {
    required: false,
});
const { auth } = usePage().props;

function getBadgeVariantFromLabel(label: string) {
    if (["work"].includes(label.toLowerCase())) return "default";

    if (["personal"].includes(label.toLowerCase())) return "outline";

    return "secondary";
}

const sortedItems = computed(() =>
    props.items
        .slice()
        .sort(
            (a, b) =>
                new Date(b.updated_at).getTime() -
                new Date(a.updated_at).getTime(),
        ),
);

function hasUnreadMessages(item: Conversation): boolean {
    item.messages.forEach((message) => {
        if (message.sender_id !== auth.user.id) {
            message.read = false;
        }
    });

    selectedConversation.value = item.id;
}

function getLastMessage(conversation: Conversation) {
    return conversation.messages[conversation.messages.length - 1];
}
</script>

<template>
    <ScrollArea class="h-screen flex">
        <div class="flex-1 flex flex-col gap-2 p-4 pt-0">
            <TransitionGroup name="list" appear>
                <button
                    v-for="item of sortedItems"
                    :key="item.id"
                    :class="
                        cn(
                            'flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm transition-all hover:bg-accent',
                            selectedConversation === item.id && 'bg-muted',
                        )
                    "
                    @click="hasUnreadMessages(item)"
                >
                    <div class="flex w-full flex-col gap-1">
                        <div class="flex items-center">
                            <div class="flex items-center gap-2">
                                <div class="font-semibold">
                                    {{ item.participant.name }}
                                </div>

                                <span
                                    v-if="!item.read"
                                    class="flex h-2 w-2 rounded-full bg-blue-600"
                                />
                            </div>
                            <div
                                :class="
                                    cn(
                                        'ml-auto text-xs',
                                        selectedConversation === item.id
                                            ? 'text-foreground'
                                            : 'text-muted-foreground',
                                    )
                                "
                            >
                                {{
                                    formatDistanceToNow(
                                        new Date(item.updated_at),
                                        {
                                            addSuffix: true,
                                        },
                                    )
                                }}
                            </div>
                        </div>

                        <div class="text-xs font-medium">
                            {{
                                item.subject.length > 60
                                    ? item.subject.substring(0, 60) + "..."
                                    : item.subject
                            }}
                        </div>
                    </div>
                    <div class="line-clamp-2 text-xs text-muted-foreground">
                        {{ getLastMessage(item).content.substring(0, 300) }}
                    </div>
                    <div class="flex items-center gap-2">
                        <Badge
                            v-for="label of item.labels"
                            :key="label"
                            :variant="getBadgeVariantFromLabel(label)"
                        >
                            {{ label }}
                        </Badge>
                    </div>
                </button>
            </TransitionGroup>
        </div>
    </ScrollArea>
</template>

<style scoped>
.list-move,
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(15px);
}

.list-leave-active {
    position: absolute;
}
</style>
