<script setup lang="ts">
import DropdownMenuHeard from "./DropdownMenuHeard.vue";
import Search from "./Search.vue";
</script>

<template>
    <div class="border-b">
        <div class="flex h-14 items-center px-4">
            <div class="ml-auto flex items-center space-x-4">
                <Search />
                <DropdownMenuHeard />
            </div>
        </div>
    </div>
</template>
