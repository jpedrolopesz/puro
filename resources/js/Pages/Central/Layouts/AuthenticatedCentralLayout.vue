<script setup>
import Heard from "./Components/Heard.vue";
import SidebarNav from "./Components/SidebarNav.vue";
</script>

<template>
    <SidebarNav>
        <div class="flex-col md:flex">
            <Heard />
            <div class="flex-1 space-y-4 p-8 pt-12">
                <slot />
            </div>
        </div>
    </SidebarNav>
</template>
