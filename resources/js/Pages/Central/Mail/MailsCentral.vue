<script lang="ts" setup>
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
import Chat from "./Components/Chat.vue";
import { defineProps } from "vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    conversations: {
        type: Array,
        required: true,
    },
    tenantsWithUsers: {
        type: Array,
        required: true,
    },
});

console.log(props.conversations);
</script>

<template>
    <Head title="Chat" />
    <AuthenticatedCentralLayout>
        <Chat
            :conversations="props.conversations"
            :tenantsWithUsers="props.tenantsWithUsers"
            :nav-collapsed-size="4"
        />
    </AuthenticatedCentralLayout>
</template>
