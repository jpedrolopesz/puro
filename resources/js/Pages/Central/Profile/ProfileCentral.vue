<script setup lang="ts">
import Layouts from "./Layouts.vue";
import ProfileForm from "./Components/ProfileForm.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Profile" />

    <Layouts
        title="Profile"
        description="This is how others will see you on the site."
    >
        <ProfileForm />
    </Layouts>
</template>
