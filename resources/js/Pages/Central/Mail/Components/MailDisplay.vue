<script lang="ts" setup>
import UserSelectPopover from "./UserSelectPopover.vue";

import { defineEmits, computed, ref, onMounted } from "vue";
import { format } from "date-fns";
import { v4 as uuidv4 } from "uuid";

import { usePage, router, useForm } from "@inertiajs/vue3";

import { Avatar, AvatarFallback } from "@/Components/ui/avatar";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Separator } from "@/Components/ui/separator";
import { Switch } from "@/Components/ui/switch";
import { Textarea } from "@/Components/ui/textarea";
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from "@/Components/ui/tooltip";

const props = defineProps<{
    mail: Mail | null;
    tenantsWithUsers: Tenant[];
}>();

const emit = defineEmits<{
    (event: "mail-sent", mailId: string): void;
}>();

const today = new Date();
const replyText = ref<string>("");
const subject = ref<string>("");
const selectedUser = ref<User | null>(null);
const mails = ref<Mail[]>([]);
const newMail = ref<string>("");

const { auth } = usePage().props;
const form = useForm({
    id: "",
    sender_id: auth.user.id || "",
    text: replyText.value,
    subject: subject.value,
    receiver_id: selectedUser,
    name: "",
    email: "",
    date: today,
});

onMounted(() => {
    const userId = auth.user.id;
    const channelName = `chat.${userId}`;

    Echo.channel(channelName).listen("MailSentEvent", (event) => {
        mails.value.push(event.mail);
    });
});

const sendMail = () => {
    const newUuid = uuidv4();
    form.id = newUuid;
    form.text = replyText.value;
    form.subject = subject.value;
    form.receiver_id = selectedUser.value.id;
    form.name = selectedUser.value.name;
    form.email = selectedUser.value.email;
    form.date = today;

    form.post(route("mail.send"), {
        onSuccess: (response) => {
            preserveScroll: true;
            replyText.value = "";
            emit("mail-sent", newUuid);
        },
        onError: (error) => {
            console.error("Error sending mail:", error);
        },
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
            <div class="flex-1 whitespace-pre-wrap p-4 text-sm">
                {{ mail.text }}
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
