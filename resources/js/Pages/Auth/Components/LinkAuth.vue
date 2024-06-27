<script setup lang="ts">
import { computed, defineProps } from "vue";
import { buttonVariants } from "@/Components/ui/button";
import { Link, Head } from "@inertiajs/vue3";
import { cn } from "@/lib/utils";

const props = defineProps<{
    isRegister: String;
}>();

console.log(props.isRegister);
const linkRoute = computed(() => (props.isRegister ? "login" : "register"));
const linkText = computed(() => (props.isRegister ? "Login" : "Register"));
</script>

<template>
    <Link
        :href="route(linkRoute)"
        :class="
            cn(
                buttonVariants({ variant: 'ghost' }),
                'absolute right-4 top-4 md:right-8 md:top-8',
            )
        "
    >
        {{ linkText }}
    </Link>
</template>
