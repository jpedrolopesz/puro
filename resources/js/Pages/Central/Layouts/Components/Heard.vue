<script setup lang="ts">
import UserNav from "./UserNav.vue";
import Search from "./Search.vue";
</script>

<template>
    <div class="border-b">
        <div class="flex h-14 items-center px-4">
            LOGO AQUI
            <div class="ml-auto flex items-center space-x-4">
                <Search />
                <UserNav />
            </div>
        </div>
    </div>
</template>
