<script setup>
import Heard from "./Components/Heard.vue";
import Sidebar from "./Components/Sidebar.vue";
</script>

<template>
    <Sidebar>
        <div class="flex-col md:flex">
            <Heard />
            <div class="flex-1 space-y-4 p-8 pt-6">
                <slot />
            </div>
        </div>
    </Sidebar>
</template>
