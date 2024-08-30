<script lang="ts" setup>
import { defineEmits, computed, ref, onMounted, onUnmounted, watch } from "vue";
import { format } from "date-fns";
import { v4 as uuidv4 } from "uuid";
import { usePage, useForm } from "@inertiajs/vue3";

import UserSelectPopover from "./UserSelectPopover.vue";
import { Avatar, AvatarFallback } from "@/Components/ui/avatar";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Separator } from "@/Components/ui/separator";
import { Switch } from "@/Components/ui/switch";
import { Textarea } from "@/Components/ui/textarea";
import { ScrollArea } from "@/Components/ui/scroll-area";

const props = defineProps<{
    conversation: Conversation;
    conversationParticipants: Tenant[] | Record<string, Tenant>;
}>();

const emit = defineEmits<{
    (event: "message-sent", conversationId: string): void;
}>();

const replyText = ref("");
const subject = ref("");
const selectedUser = ref<User | null>(null);
//const messages = ref<Message[]>([]);

const { auth } = usePage().props;
const initials = computed(() =>
    selectedUser.value ? getInitials(selectedUser.value.name) : "",
);

const getInitials = (name: string): string =>
    name
        .split(" ")
        .map((word) => word.charAt(0).toUpperCase())
        .join("");

const handleUserSelected = (user: User) => {
    selectedUser.value = user;
};

const createConversation = () => {
    const newConversationId = uuidv4();

    const form = useForm({
        id: newConversationId,
        initiator_id: auth.user?.id,
        recipient_id: selectedUser.value?.id,
        recipient_type: selectedUser.value?.identifier,
        subject: subject.value,
        content: replyText.value.trim(),
        labels: [],
    });

    form.post(
        route(
            `${auth.user?.tenant_id ? "tenant" : "admin"}.conversation.create`,
        ),
        {
            preserveScroll: true,
            onSuccess: () => {
                subject.value = "";
                replyText.value = "";
                emit("message-sent", form.id as string);
                setupWebSocket(newConversationId);

                console.log(newConversationId);
            },
            onError: (error) => console.error("Error sending message:", error),
        },
    );
};

const sendMessage = async () => {
    if (!props.conversation) return;

    const form = useForm({
        conversation_id: props.conversation.id,
        content: replyText.value.trim(),
        read: "false",
    });

    await form.post(
        route(`${auth.user?.tenant_id ? "tenant" : "admin"}.message.send`),
        {
            preserveScroll: true,
            onSuccess: () => {
                replyText.value = "";
                emit("message-sent", form.conversation_id);

                setupWebSocket(form.conversation_id);
            },
            onError: (error) => console.error("Error sending message:", error),
        },
    );
};

const handleSubmit = () => {
    if (props.conversation?.id) {
        sendMessage();
    } else {
        createConversation();
    }
};
const localMessages = computed(() => props.conversation?.messages || []);

const setupWebSocket = (conversationId: string) => {
    if (Echo.connector.channels[`conversation.${conversationId}`]) {
        Echo.leaveChannel(`conversation.${conversationId}`);
    }

    Echo.channel(`conversation.${conversationId}`).listen(
        ".new-message",

        (event: { message: Message }) => {
            localMessages.value.push(event.message);
            console.log(event.message);
        },
    );
};

watch(
    () => props.conversation?.id,
    (conversationId) => {
        if (conversationId) {
            setupWebSocket(conversationId);
        }
    },
    { immediate: true },
);
</script>

<template>
    <div class="flex h-screen flex-col">
        <Separator />
        <div class="flex flex-1 flex-col">
            <div class="flex items-start p-4">
                <div class="flex items-start gap-4 text-sm">
                    <Avatar>
                        <AvatarFallback>{{ initials }}</AvatarFallback>
                    </Avatar>
                    <div class="grid gap-1">
                        <div class="font-semibold">
                            {{
                                conversation
                                    ? conversation.participant?.name
                                    : selectedUser?.name
                            }}
                        </div>
                        <template v-if="conversation">
                            <div class="line-clamp-1 text-xs">
                                {{ conversation.subject }}
                            </div>
                            <div class="line-clamp-1 text-xs">
                                <span class="font-medium">Reply-To:</span>
                                {{ conversation.participant?.email }}
                            </div>
                        </template>
                        <div
                            v-else-if="selectedUser?.email"
                            class="line-clamp-1 text-xs"
                        >
                            <span class="font-medium">Reply-To:</span>
                            {{ selectedUser.email }}
                        </div>
                    </div>
                </div>
                <div class="ml-auto text-xs text-muted-foreground">
                    <template v-if="conversation">
                        {{ format(new Date(conversation.created_at), "PPpp") }}
                    </template>
                    <UserSelectPopover
                        v-else
                        :conversationParticipants="conversationParticipants"
                        @user-selected="handleUserSelected"
                    />
                </div>
            </div>

            <Separator />

            <template v-if="conversation">
                <ScrollArea
                    class="flex-1 overflow-y-auto"
                    :class="
                        auth.user?.tenant_id
                            ? 'max-h-[calc(100vh-300px)]'
                            : 'max-h-[calc(100vh-250px)]'
                    "
                >
                    <ul>
                        <li
                            v-for="message in localMessages"
                            :key="message.id"
                            :class="[
                                'p-4 my-4 rounded-lg',
                                message.sender_id == auth.user.id
                                    ? 'text-right bg-gray-50 mr-4 ml-20'
                                    : 'text-left bg-gray-100 ml-4 mr-20',
                            ]"
                        >
                            <div class="flex flex-col">
                                <span class="whitespace-pre-wrap text-sm">
                                    {{ message?.content }}
                                </span>
                                <span
                                    class="whitespace-pre-wrap text-xs truncate"
                                >
                                    {{
                                        format(
                                            new Date(message.created_at),
                                            "pp",
                                        )
                                    }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </ScrollArea>
            </template>
            <template v-else>
                <div class="flex items-center mx-5">
                    <span class="items-center text-xs">Subject: </span>
                    <input
                        v-model="subject"
                        type="text"
                        class="w-full line-clamp-1 text-xs border-transparent focus:border-transparent focus:ring-0 focus:outline-none"
                    />
                </div>
                <Separator />
                <div
                    class="flex-1"
                    :class="
                        auth.user?.tenant_id ? 'max-h-[calc(100vh-320px)]' : ''
                    "
                ></div>
            </template>

            <Separator />
            <div class="p-4">
                <form @submit.prevent="handleSubmit">
                    <div class="grid gap-4">
                        <Textarea
                            v-model="replyText"
                            class="p-4"
                            :placeholder="
                                conversation
                                    ? `Reply to ${conversation.participant?.name}...`
                                    : 'Reply to ...'
                            "
                        />
                        <div class="flex items-center">
                            <Label
                                html-for="mute"
                                class="flex items-center gap-2 text-xs font-normal"
                            >
                                <Switch id="mute" aria-label="Mute thread" />
                                Mute this thread
                            </Label>
                            <Button type="submit" size="sm" class="ml-auto">
                                {{
                                    conversation
                                        ? "Send"
                                        : "Start a conversation"
                                }}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
