<script lang="ts" setup>
import { computed } from "vue";
import addDays from "date-fns/addDays";
import addHours from "date-fns/addHours";
import format from "date-fns/format";
import nextSaturday from "date-fns/nextSaturday";
import type { Mail } from "../data/mails";

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

interface MailDisplayProps {
    mail: Mail | undefined;
}

const props = defineProps<MailDisplayProps>();

const mailFallbackName = computed(() => {
    return props.mail?.name
        .split(" ")
        .map((chunk) => chunk[0])
        .join("");
});

const today = new Date();
</script>

<template>
    <div class="flex h-lvh flex-col">
        <Separator />
        <div class="flex flex-1 flex-col">
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
                <form>
                    <div class="grid gap-4">
                        <Textarea
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
                            <Button type="button" size="sm" class="ml-auto">
                                Send
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="p-8 text-center text-muted-foreground">
            No message selected
        </div>
    </div>
</template>
