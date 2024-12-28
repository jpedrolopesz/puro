<script setup lang="ts">
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <main class="space-y-4 m-4 md:m-10 lg:m-20">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Dashboard</h2>
                    <p class="text-muted-foreground">
                        Bem-vindo(a), {{ $page.props.auth.user.name }}!
                    </p>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>
