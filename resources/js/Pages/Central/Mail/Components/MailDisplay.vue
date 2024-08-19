<script lang="ts" setup>
import UsersListCombobox from "./UsersListCombobox.vue";

import { computed, ref, onMounted } from "vue";
import { format } from "date-fns";
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

console.log(props.mail);

const mailFallbackName = computed(() => {
    return (
        props.mail?.name
            .split(" ")
            .map((chunk) => chunk[0])
            .join("") || "N/A"
    );
});

const today = new Date();

// Referência para o texto da resposta
const replyText = ref<string>("");

const { auth } = usePage().props;

const form = useForm({
    receiver_id: auth.user.id || "",
    message: replyText.value,
});

const mails = ref([]);
const newMail = ref("");

onMounted(() => {
    const { auth } = usePage().props;
    const userId = auth.user.id;

    const channelName = `chat.${userId}`;

    Echo.channel(channelName).listen("MailSentEvent", (event) => {
        mails.value.push(event.mail);
    });
});
const sendMail = () => {
    // Atualiza o texto da resposta no formulário
    form.message = replyText.value;

    form.post(route("mail.send"), {
        onSuccess: () => {
            replyText.value = "";
        },
        onError: (error) => {
            console.error("Error sending mail:", error);
        },
    });
};
</script>

<template>
    <div class="flex h-lvh flex-col">
        <Separator />
        <div v-if="mail" class="flex flex-1 flex-col">
            <div class="flex items-start p-4">
                <div class="flex items-start gap-4 text-sm">
                    <Avatar>
                        <AvatarFallback>
                            {{ mailFallbackName }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="grid gap-1">
                        <div class="font-semibold">
                            {{ mail.name }}
                        </div>
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
                        <AvatarFallback>
                            {{ mailFallbackName }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="grid gap-1">
                        <div class="font-semibold">
                            {{ mail?.name }}
                        </div>
                        <div class="line-clamp-1 text-xs">
                            {{ mail?.subject ? mail.subject : "Subject:" }}
                        </div>
                        <div class="line-clamp-1 text-xs">
                            <span class="font-medium">Reply-To:</span>
                            {{ mail?.email }}
                        </div>
                    </div>
                </div>
                <div class="ml-auto text-xs text-muted-foreground">
                    <UsersListCombobox :tenantsWithUsers="tenantsWithUsers" />
                </div>
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
