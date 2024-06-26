<script setup lang="ts">
import { ref } from "vue";
import { SymbolIcon, GithubLogoIcon } from "@radix-icons/vue";

import { cn } from "@/lib/utils";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import InputError from "@/Components/InputError.vue";

import { buttonVariants } from "@/Components/ui/button";
import { Link, useForm } from "@inertiajs/vue3";

const isLoading = ref(false);
async function onSubmit(event: Event) {
    event.preventDefault();
    isLoading.value = true;

    setTimeout(() => {
        isLoading.value = false;
    }, 3000);
}

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
    });
};
</script>

<template>
    <div>
        <div :class="cn('grid gap-6', $attrs.class ?? '')">
            <form @submit.prevent="submit">
                <div class="grip gip-2">
                    <div class="mt-2">
                        <Label class="sr-only" for="name" value="Name">
                            Name
                        </Label>
                        <Input
                            id="name"
                            placeholder="Name"
                            type="name"
                            auto-capitalize="none"
                            auto-complete="name"
                            auto-correct="off"
                            :disabled="isLoading"
                            required
                            v-model="form.name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

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
                            required
                            v-model="form.email"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                </div>

                <div class="mt-2">
                    <Label class="sr-only" for="password"> Password </Label>
                    <Input
                        id="password"
                        placeholder="Password"
                        type="password"
                        auto-capitalize="none"
                        auto-complete="new-password"
                        :disabled="isLoading"
                        required
                        v-model="form.password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-2">
                    <Label class="sr-only" for="password_confirmation">
                        Confirm Password
                    </Label>
                    <Input
                        id="password_confirmation"
                        placeholder="Confirm Password"
                        type="password"
                        auto-capitalize="none"
                        auto-complete="new-password"
                        :disabled="isLoading"
                        required
                        v-model="form.password_confirmation"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div class="mt-4">
                    <div class="grid gap-1">
                        <Button
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="isLoading || form.processing"
                        >
                            <SymbolIcon
                                v-if="isLoading"
                                class="mr-2 h-3 w-3 animate-spin"
                            />
                            Register
                        </Button>
                    </div>
                    <div class="flex justify-end mt-2">
                        <Link
                            :href="route('login')"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Already registered?
                        </Link>
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
    </div>
</template>
