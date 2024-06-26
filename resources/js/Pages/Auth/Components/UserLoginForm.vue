<script setup lang="ts">
import { ref } from "vue";
import { SymbolIcon, GithubLogoIcon } from "@radix-icons/vue";

import { cn } from "@/lib/utils";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Checkbox } from "@/Components/ui/checkbox";
import InputError from "@/Components/InputError.vue";
import { Link, useForm } from "@inertiajs/vue3";

const isLoading = ref(false);
async function onSubmit(event: Event) {
    event.preventDefault();
    isLoading.value = true;

    setTimeout(() => {
        isLoading.value = false;
    }, 3000);
}

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
    });
};
</script>

<template>
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>
    <div :class="cn('grid gap-6', $attrs.class ?? '')">
        <form @submit="submit">
            <div class="grid gap-2">
                <div class="mt-2">
                    <Label class="sr-only" for="email"> Email </Label>
                    <Input
                        id="email"
                        placeholder="Email"
                        type="email"
                        auto-capitalize="none"
                        auto-complete="email"
                        auto-correct="off"
                        :disabled="isLoading"
                        v-model="form.email"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-2">
                    <Label class="sr-only" for="password"> Password </Label>
                    <Input
                        id="password"
                        placeholder="Password"
                        type="password"
                        auto-capitalize="none"
                        auto-correct="off"
                        :disabled="isLoading"
                        v-model="form.password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <div class="grid gap-1">
                        <Button
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing || isLoading"
                        >
                            <SymbolIcon
                                v-if="isLoading"
                                class="mr-2 h-3 w-3 animate-spin"
                            />
                            Log In
                        </Button>
                    </div>
                </div>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </Link>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox
                            for="remember"
                            v-model:checked="form.remember"
                        />
                        <span class="ms-2 text-sm text-gray-600"
                            >Remember me</span
                        >
                    </label>
                </div>
            </div>
        </form>
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t" />
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-background px-2 text-muted-foreground">
                    Or continue with
                </span>
            </div>
        </div>
        <Button variant="outline" type="button" :disabled="isLoading">
            <GithubLogoIcon v-if="isLoading" class="mr-2 h-4 w-4" />

            GitHub
        </Button>
    </div>
</template>
