<script setup lang="ts">
import Layouts from "./Layouts.vue";
import AccountForm from "./Components/AccountForm.vue";
</script>

<template>
    <Layouts
        title="Account"
        description="Update your account settings. Set your preferred language and
    timezone."
    >
        <AccountForm />
    </Layouts>
</template>
