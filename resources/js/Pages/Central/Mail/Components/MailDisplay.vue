<script lang="ts" setup>
import { defineEmits, computed, ref, onMounted, onUnmounted } from "vue";
import { format } from "date-fns";
import { v4 as uuidv4 } from "uuid";
import { usePage, router, useForm } from "@inertiajs/vue3";

import UserSelectPopover from "./UserSelectPopover.vue";
import { Avatar, AvatarFallback } from "@/Components/ui/avatar";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Separator } from "@/Components/ui/separator";
import { Switch } from "@/Components/ui/switch";
import { Textarea } from "@/Components/ui/textarea";

const props = defineProps<{
    mail: Mail | null;
    tenantsWithUsers: Tenant[];
    selectedMail: String;
}>();

const emit = defineEmits<{
    (event: "mail-sent", mailId: string): void;
}>();

const today = new Date();
const replyText = ref<string>("");
const subject = ref<string>("");
const selectedUser = ref<User | null>(null);
const mails = ref<Mail[]>([]);

const isUserTyping = ref(false);
const isUserTypingTimer = ref(null);
const isUserOnline = ref(false);

const { auth } = usePage().props;

const allUsers = computed(() => {
    return props.tenantsWithUsers.flatMap((tenant) => tenant.users);
});

const initializeChannel = () => {
    Echo.private(`chat.${auth.user.id}`).whisper("typing", {
        userID: auth.user.id,
    });

    Echo.join(`presence.chat`)
        .here((users) => {
            console.log("Users currently online:", users);
            isUserOnline.value = users.some((user) => user.id === auth.user.id);
        })
        .joining((user) => {
            console.log("User joined:", user);
            if (user.id === auth.user.id) {
                isUserOnline.value = true;
            }
        })
        .leaving((user) => {
            console.log("User left:", user);
            if (user.id === auth.user.id) {
                isUserOnline.value = false;
            }
        });

    Echo.private(`chat.${auth.user.id}`).listen("MailSentEvent", (response) => {
        mails.value.push(response.mail);
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

const sendMail = (isNewMail = true) => {
    // Garantir que selectedUser.value não é nulo
    if (!selectedUser.value) {
        console.error("No selected user found.");
        return;
    }

    const { id, name, email } = props.selectedMail; // tenho que passar todos os elementos, esta passando apenas o id
    const newUuid = uuidv4();

    const mailId = isNewMail ? newUuid : currentMailId;

    const form = useForm({
        id: mailId,
        sender_id: auth.user.id || "",
        subject: isNewMail ? subject.value : "",
        receiver_id: isNewMail ? id : "",
        name: isNewMail ? name : "",
        email: isNewMail ? email : "",
        date: today,
        messages: [
            {
                mail_id: mailId,
                text: replyText.value,
                date: today,
            },
        ],
    });

    form.post(route("mail.send"), {
        onSuccess: () => {
            replyText.value = "";
            emit("mail-sent", mailId);
        },
        onError: (error) => console.error("Error sending mail:", error),
        preserveScroll: true,
    });
};

const mailFallbackName = computed(() => {
    return (
        props.mail?.name
            ?.split(" ")
            .map((chunk) => chunk[0])
            .join("") || "N/A"
    );
});

const getInitials = (name: string): string =>
    name
        .split(" ")
        .map((word) => word.charAt(0).toUpperCase())
        .join("");

const initials = computed(() =>
    selectedUser.value ? getInitials(selectedUser.value.name) : "",
);

const handleUserSelected = (user: User) => {
    selectedUser.value = user;
};
</script>

<template>
    <div class="flex h-lvh flex-col">
        <Separator />
        <div v-if="mail" class="flex flex-1 flex-col">
            <div class="flex items-start p-4">
                <div class="flex items-start gap-4 text-sm">
                    <Avatar>
                        <AvatarFallback>{{ mailFallbackName }}</AvatarFallback>
                    </Avatar>
                    <div class="grid gap-1">
                        <div class="font-semibold">{{ mail.name }}</div>
                        <div class="line-clamp-1 text-xs">
                            {{ mail.subject }}
                        </div>
                        <div class="line-clamp-1 text-xs">
                            <span class="font-medium">Reply-To:</span>
                            {{ mail.email }}
                        </div>
                    </div>
                </div>
                <div
                    v-if="mail.date"
                    class="ml-auto text-xs text-muted-foreground"
                >
                    {{ format(new Date(mail.date), "PPpp") }}
                </div>
            </div>

            <Separator />
            <div class="flex-1">
                <ul>
                    <li
                        v-for="message in mail.messages"
                        :key="message.id"
                        :class="{
                            'text-right bg-gray-50 p-4 my-4 mr-4 ml-32 rounded-lg ':
                                message.sender_id === auth.user?.id,
                            'text-left bg-gray-100    p-4 my-4 ml-4 mr-32 rounded-lg':
                                message.receiver_id === auth.user?.id,
                        }"
                    >
                        <div class="flex flex-col">
                            <span class="whitespace-pre-wrap text-sm">
                                {{ message.text }}
                            </span>
                            <span class="whitespace-pre-wrap text-xs">
                                {{ format(new Date(message.date), "pp") }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <Separator />
            <div class="p-4">
                <form @submit.prevent="sendMail">
                    <div class="grid gap-4">
                        <Textarea
                            v-model="replyText"
                            class="p-4"
                            :placeholder="`Reply ${mail.name}...`"
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
                        :tenantsWithUsers="tenantsWithUsers"
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

            <div class="flex-1 whitespace-pre-wrap p-4 text-sm">
                {{ mail?.text }}
            </div>
            <Separator />
            <div class="p-4">
                <form @submit.prevent="sendMail">
                    <div class="grid gap-4">
                        <Textarea
                            v-model="replyText"
                            class="p-4"
                            :placeholder="`Reply ${mail?.name}...`"
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
    </div>
</template>
