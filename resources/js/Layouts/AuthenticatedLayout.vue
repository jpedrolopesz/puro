<script setup>
import NavHorizontal from "./NavHorizontal.vue";
import NavVertical from "./NavVertical.vue";
</script>

<template>
    <NavVertical>
        <div class="flex-col md:flex">
            <NavHorizontal />
            <div class="flex-1 space-y-4 p-8 pt-6">
                <slot />
            </div>
        </div>
    </NavVertical>
</template>
