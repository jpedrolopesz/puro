<script setup>
import NavHeard from "./Components/NavHeard.vue";
import NavSidebar from "./Components/NavSidebar.vue";
</script>

<template>
    <NavSidebar>
        <div class="flex-col md:flex">
            <div class="flex-1">
                <slot />
            </div>
        </div>
    </NavSidebar>
</template>
