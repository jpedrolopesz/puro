<script setup lang="ts">
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";

import Plans from "./Pages/Plans.vue";

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";

import { defineProps } from "vue";

const props = defineProps<{
    plans: { type: Array; required: true };
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="space-y-0.5">
                <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
                <p class="text-muted-foreground">
                    Manage your profile information and preferences
                </p>
            </div>

            <Tabs
                default-value="account"
                class="flex w-full"
                orientation="vertical"
            >
                <TabsList class="grid w-48 grid-cols-1 bg-white space-y-2">
                    <TabsTrigger
                        v-for="tab in ['account', 'password', 'plans']"
                        :key="tab"
                        :value="tab"
                        class="w-full justify-start px-4 py-2 text-left capitalize transition-all hover:bg-gray-100 data-[state=active]:bg-primary data-[state=active]:text-white"
                    >
                        {{ tab }}
                    </TabsTrigger>
                </TabsList>

                <div class="flex-grow pl-6">
                    <TabsContent value="account">
                        <Card>
                            <CardHeader>
                                <CardTitle>Profile Information</CardTitle>
                                <CardDescription>
                                    Update your account's profile information
                                    and email address.
                                </CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-2">
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                    class="max-w-xl"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>
                    <TabsContent value="password">
                        <Card>
                            <CardHeader>
                                <CardTitle>Update Password</CardTitle>
                                <CardDescription>
                                    Ensure your account is using a long, random
                                    password to stay secure.
                                </CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-2">
                                <UpdatePasswordForm />
                            </CardContent>
                        </Card>
                    </TabsContent>
                    <TabsContent value="plans">
                        <Card>
                            <CardHeader>
                                <CardTitle>Plans</CardTitle>
                                <CardDescription>
                                    Choose and manage your subscription plan
                                </CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-2">
                                <Plans :plans="props.plans" />
                            </CardContent>
                        </Card>
                    </TabsContent>
                </div>
            </Tabs>
        </div>
    </AuthenticatedLayout>
</template>
