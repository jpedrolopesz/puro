<script setup>
import NavHeard from "./Components/NavHeard.vue";
import NavSidebar from "./Components/NavSidebar.vue";
</script>

<template>
    <NavSidebar>
        <div class="flex-col md:flex">
            <NavHeard />
            <div>
                <ScrollArea class="h-screen">
                    <slot />
                </ScrollArea>
            </div>
        </div>
    </NavSidebar>
</template>
