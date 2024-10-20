<script setup lang="ts">
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <div></div>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <UpdatePasswordForm class="max-w-xl" />
    </div>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <DeleteUserForm class="max-w-xl" />
    </div>
</template>
