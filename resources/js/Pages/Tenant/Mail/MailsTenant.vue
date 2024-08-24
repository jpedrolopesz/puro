<script lang="ts" setup>
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";

import Mail from "./Components/Mail.vue";
import { defineProps } from "vue";

import { mails } from "./data/mails";

import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Mails" />
    <AuthenticatedLayout>
        <Mail :mails="mails" :nav-collapsed-size="4" />
    </AuthenticatedLayout>
</template>
