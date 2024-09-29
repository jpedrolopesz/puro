<script setup lang="ts">
import Layouts from "./Layouts.vue";
import AccountForm from "./Components/AccountForm.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Account" />
    <Layouts
        title="Account"
        description="Update your account settings. Set your preferred language and
    timezone."
    >
        <AccountForm />
    </Layouts>
</template>
