<script setup lang="ts">
import { computed, defineProps } from "vue";
import { buttonVariants } from "@/Components/ui/button";
import { Link, Head } from "@inertiajs/vue3";
import { cn } from "@/lib/utils";

const props = defineProps<{
    isRegister: String;
}>();

console.log(props.isRegister);
const isLogin = computed(() => props.isRegister === "Login");
const isRegister = computed(() => props.isRegister === "Register");

const linkRoute = computed(() =>
    isLogin.value ? "register" : isRegister.value ? "login" : "",
);
const linkText = computed(() =>
    isLogin.value ? "Register" : isRegister.value ? "Login" : "",
);
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
