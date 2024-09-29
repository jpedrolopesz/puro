<script setup lang="ts">
import { defineProps } from "vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import { Separator } from "@/Components/ui/separator";
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    title: {
        type: String,
        default: "Settings",
    },
    description: {
        type: String,
        default: "Manage your account settings.",
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="space-y-0.5">
                <h2 class="text-2xl font-bold tracking-tight">{{ title }}</h2>
                <p class="text-muted-foreground">{{ description }}</p>
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
                                <div class="space-y-1">
                                    <Label for="name">Name</Label>
                                    <Input
                                        id="name"
                                        default-value="Pedro Duarte"
                                    />
                                </div>
                                <div class="space-y-1">
                                    <Label for="username">Username</Label>
                                    <Input
                                        id="username"
                                        default-value="@peduarte"
                                    />
                                </div>
                            </CardContent>
                            <CardFooter>
                                <Button>Save changes</Button>
                            </CardFooter>
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
                                <div class="space-y-1">
                                    <Label for="current"
                                        >Current password</Label
                                    >
                                    <Input id="current" type="password" />
                                </div>
                                <div class="space-y-1">
                                    <Label for="new">New password</Label>
                                    <Input id="new" type="password" />
                                </div>
                            </CardContent>
                            <CardFooter>
                                <Button>Save password</Button>
                            </CardFooter>
                        </Card>
                    </TabsContent>
                </div>
            </Tabs>
        </div>
    </AuthenticatedLayout>
</template>
