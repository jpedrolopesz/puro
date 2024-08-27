<script lang="ts" setup>
import Chat from "./Components/Chat.vue";
import { Head } from "@inertiajs/vue3";

const { conversations, conversationParticipants } = defineProps<{
    conversations: Array<any>;
    conversationParticipants: Record<string, Tenant | Admin>;
}>();
</script>

<template>
    <Head title="Conversation" />
    <Chat
        :conversations="conversations"
        :conversationParticipants="conversationParticipants"
        :nav-collapsed-size="4"
    />
</template>
