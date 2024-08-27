<script lang="ts" setup>
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import ConversationsGlobal from "@/Pages/Global/Conversation/ConversationsGlobal.vue";

const { conversations, conversationParticipants } = defineProps<{
    conversations: Array<any>;
    conversationParticipants: Record<string, Tenant | Admin>;
}>();

console.log(conversations);
</script>

<template>
    <AuthenticatedCentralLayout>
        <ConversationsGlobal
            :conversations="conversations"
            :conversationParticipants="conversationParticipants"
        />
    </AuthenticatedCentralLayout>
</template>
