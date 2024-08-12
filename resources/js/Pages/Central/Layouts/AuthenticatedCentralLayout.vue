<script setup>
import NavHeard from "./Components/NavHeard.vue";
import NavSidebar from "./Components/NavSidebar.vue";
import Toaster from "@/Components/ui/toast/Toaster.vue";
</script>

<template>
    <NavSidebar>
        <div class="flex-col md:flex">
            <div class="flex-1">
                <Toaster />

                <slot />
            </div>
        </div>
    </NavSidebar>
</template>
