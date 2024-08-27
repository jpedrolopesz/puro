<script lang="ts" setup>
import { defineEmits, computed, ref, onMounted, onUnmounted } from "vue";
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
    conversationParticipants: Tenant[] | Record<string, Tenant>; // Aceita ambos
}>();

const emit = defineEmits<{
    (event: "message-sent", conversationId: string): void;
}>();

const replyText = ref<string>("");
const subject = ref<string>("");
const selectedUser = ref<User | null>(null);
const isUserTypingTimer = ref(null);

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

// Inicialização e finalização do canal
const initializeChannel = () => {
    Echo.private(`chat.${auth.user.id}`).whisper("typing", {
        userID: auth.user.id,
    });
};

onMounted(() => {
    console.log("Initializing channel...");
    initializeChannel();
});

onUnmounted(() => {
    console.log("Leaving private channel...");
    Echo.leave(`chat.${auth.user.id}`);

    if (isUserTypingTimer.value) {
        clearTimeout(isUserTypingTimer.value);
    }
});

const createConversation = () => {
    const uuid = uuidv4();

    const form = useForm({
        id: uuid,
        initiator_id: auth.user?.id,
        recipient_id: selectedUser.value?.id,
        recipient_type: selectedUser?.value.identifier,
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

                emit("message-sent", uuid);
            },
            onError: (error) => {
                console.error("Error sending message:", error);
            },
        },
    );
};

const sendMessage = () => {
    const form = useForm({
        conversation_id: props.conversation.id,
        content: replyText.value.trim(),
        read: "false",
    });

    form.post(
        route(`${auth.user?.tenant_id ? "tenant" : "admin"}.message.send`),
        {
            preserveScroll: true,
            onSuccess: () => {
                replyText.value = "";
                emit("message-sent", props.conversation.id);
            },
            onError: (error) => {
                console.error("Error sending message:", error);
            },
        },
    );
};
</script>

<template>
    <div class="flex h-screen flex-col">
        <Separator />
        <div v-if="conversation" class="flex flex-1 flex-col">
            <div class="flex items-start p-4">
                <div class="flex items-start gap-4 text-sm">
                    <Avatar>
                        <AvatarFallback>{{ initials }}</AvatarFallback>
                    </Avatar>
                    <div class="grid gap-1">
                        <div class="font-semibold">
                            {{ props.conversation.participant?.name }}
                        </div>
                        <div class="line-clamp-1 text-xs">
                            {{ props.conversation?.subject }}
                        </div>
                        <div class="line-clamp-1 text-xs">
                            <span class="font-medium">Reply-To:</span>
                            {{ props.conversation.participant?.email }}
                        </div>
                    </div>
                </div>
                <div class="ml-auto text-xs text-muted-foreground">
                    {{
                        format(new Date(props.conversation?.created_at), "PPpp")
                    }}
                </div>
            </div>

            <Separator />
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
                        v-for="message in conversation.messages"
                        :key="message.id"
                        :class="{
                            'text-right bg-gray-50 p-4 my-4 mr-4 ml-20 rounded-lg':
                                message.sender_id == auth.user.id,
                            'text-left bg-gray-100 p-4 my-4 ml-4 mr-20 rounded-lg':
                                message.sender_id != auth.user.id,
                        }"
                    >
                        <div class="flex flex-col">
                            <span class="whitespace-pre-wrap text-sm">
                                {{ message?.content }}
                            </span>
                            <span class="whitespace-pre-wrap text-xs truncate">
                                {{ format(new Date(message.created_at), "pp") }}
                            </span>
                        </div>
                    </li>
                </ul>
            </ScrollArea>

            <Separator />
            <div class="p-4">
                <form @submit.prevent="sendMessage">
                    <div class="grid gap-4">
                        <Textarea
                            v-model="replyText"
                            class="p-4"
                            :placeholder="`Reply to ${props.conversation.participant?.name}...`"
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
                                Send
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div v-else class="flex flex-1 flex-col">
            <div class="flex items-start p-4">
                <div class="flex items-start gap-4 text-sm">
                    <Avatar>
                        <AvatarFallback>{{ initials }}</AvatarFallback>
                    </Avatar>
                    <div class="grid gap-1">
                        <div class="font-semibold">
                            {{ selectedUser?.name }}
                        </div>

                        <div class="line-clamp-1 text-xs">
                            <span v-if="selectedUser?.email" class="font-medium"
                                >Reply-To:</span
                            >
                            {{ selectedUser?.email }}
                        </div>
                    </div>
                </div>

                <div class="ml-auto text-xs text-muted-foreground">
                    <UserSelectPopover
                        :conversationParticipants="conversationParticipants"
                        @user-selected="handleUserSelected"
                    />
                </div>
            </div>
            <Separator />
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
                :class="auth.user?.tenant_id ? 'max-h-[calc(100vh-320px)]' : ''"
            ></div>
            <Separator />
            <div class="p-4">
                <form @submit.prevent="createConversation">
                    <div class="grid gap-4">
                        <Textarea
                            v-model="replyText"
                            class="p-4"
                            :placeholder="`Reply to ...`"
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
                                Start a conversation
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
