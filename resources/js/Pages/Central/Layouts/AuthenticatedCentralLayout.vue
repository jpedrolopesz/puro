<script setup>
import NavHeard from "./Components/NavHeard.vue";
import NavSidebar from "./Components/NavSidebar.vue";
import { ScrollArea } from "@/Components/ui/scroll-area";

import Toaster from "@/Components/ui/toast/Toaster.vue";
</script>

<template>
    <NavSidebar>
        <div class="flex-col md:flex">
            <div>
                <Toaster />
                <ScrollArea class="h-screen">
                    <slot />
                </ScrollArea>
            </div>
        </div>
    </NavSidebar>
</template>
