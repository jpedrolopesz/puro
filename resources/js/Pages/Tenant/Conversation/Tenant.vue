<script lang="ts" setup>
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";
import ConversationsGlobal from "@/Pages/Global/Conversation/ConversationsGlobal.vue";

const { conversations, tenantsWithUsers } = defineProps<{
    conversations: Array<any>;
    tenantsWithUsers: Array<any>;
}>();
</script>

<template>
    <AuthenticatedLayout>
        <ConversationsGlobal
            :conversations="conversations"
            :tenantsWithUsers="tenantsWithUsers"
        />
    </AuthenticatedLayout>
</template>
