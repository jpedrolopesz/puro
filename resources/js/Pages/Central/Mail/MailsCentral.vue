<script lang="ts" setup>
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";

import Mail from "./Components/Mail.vue";
import { defineProps } from "vue";

import { Head } from "@inertiajs/vue3";

const props = defineProps({
    mails: {
        type: Object,
        required: true,
    },
    tenantsWithUsers: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Mails" />
    <AuthenticatedCentralLayout>
        <Mail
            :mails="props.mails"
            :tenantsWithUsers="props.tenantsWithUsers"
            :nav-collapsed-size="4"
        />
    </AuthenticatedCentralLayout>
</template>
