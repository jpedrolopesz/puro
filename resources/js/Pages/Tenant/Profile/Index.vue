<script setup lang="ts">
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";
import Plans from "./Pages/Plans.vue";
import Account from "./Pages/Account.vue";

import { defineProps } from "vue";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";

const props = defineProps({
    plans: { type: Array, required: true },
});

console.log(props.plans);
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="space-y-0.5">
                <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
                <p class="text-muted-foreground">Description</p>
            </div>

            <Tabs
                default-value="account"
                class="flex w-full"
                orientation="vertical"
            >
                <TabsList class="grid w-48 grid-cols-1 bg-white space-y-2">
                    <TabsTrigger
                        v-for="tab in ['account', 'plans']"
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
                                <CardTitle>Account</CardTitle>
                                <CardDescription>
                                    Make changes to your account here. Click
                                    save when you're done.
                                </CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-2">
                                <Account />
                            </CardContent>
                        </Card>
                    </TabsContent>
                    <TabsContent value="plans">
                        <Card>
                            <CardHeader>
                                <CardTitle>Password</CardTitle>
                                <CardDescription>
                                    Change your password here. After saving,
                                    you'll be logged out.
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
