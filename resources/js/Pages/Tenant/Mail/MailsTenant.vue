<script lang="ts" setup>
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";

import Mail from "./Components/Mail.vue";
import { defineProps } from "vue";

import { mails } from "./data/mails";

import { Head } from "@inertiajs/vue3";

const props = defineProps({
    mails: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Mails" />
    <AuthenticatedLayout>
        <Mail :mails="props.mails" :nav-collapsed-size="4" />
    </AuthenticatedLayout>
</template>
