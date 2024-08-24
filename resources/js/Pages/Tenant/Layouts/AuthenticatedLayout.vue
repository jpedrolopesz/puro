<script setup>
import NavHeard from "./Components/NavHeard.vue";
import NavSidebar from "./Components/NavSidebar.vue";
</script>

<template>
    <NavSidebar>
        <div class="flex-col md:flex">
            <NavHeard />
            <div class="flex-1 space-y-4 h-lvh">
                <slot />
            </div>
        </div>
    </NavSidebar>
</template>
