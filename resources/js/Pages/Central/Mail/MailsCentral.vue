<script lang="ts" setup>
import Mail from "./Components/Mail.vue";
import { accounts, mails } from "./data/mails";
import AuthenticatedCentralLayout from "../Layouts/AuthenticatedCentralLayout.vue";
</script>

<template>
    <AuthenticatedCentralLayout>
        <div class="min-h-screen flex-col md:flex">
            <Mail :accounts="accounts" :mails="mails" :nav-collapsed-size="4" />
        </div>
    </AuthenticatedCentralLayout>
</template>
