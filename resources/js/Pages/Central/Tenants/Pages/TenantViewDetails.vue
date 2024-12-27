<script setup lang="ts">
import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";
import TenantCustomerDetails from "../Components/TenantCustomerDetails.vue";
import TenantUserDetails from "../Components/TenantUserDetails.vue";
import { TenantDetails } from "../Types/tenantTypes";
import { CustomerPayments } from "../Types/paymentsTypes";
import { UserSchema } from "../Types/userTypes";

import { Link } from "@inertiajs/vue3";

import { ChevronLeft } from "lucide-vue-next";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";

const props = defineProps<{
    tenantDetails: TenantDetails;
    customerPayments: CustomerPayments;
    tenantUsers: UserSchema;
}>();

console.log(props.customerPayments);
</script>

<template>
    <AuthenticatedCentralLayout>
        <main class="space-y-8 m-4 md:m-10 lg:m-20">
            <div class="mx-auto grid flex-1 auto-rows-max gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.tenants.index')">
                        <Button variant="outline" size="icon" class="h-7 w-7">
                            <ChevronLeft class="h-4 w-4" />
                            <span class="sr-only">Back</span>
                        </Button>
                    </Link>
                    <div class="flex flex-col">
                        <h1
                            class="flex items-center shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0"
                        >
                            {{ tenantDetails.tenancy_name }}
                            <Badge
                                :variant="
                                    tenantDetails.status === 'active'
                                        ? ''
                                        : 'outline'
                                "
                                class="ml-2"
                            >
                                {{
                                    tenantDetails.status === "active"
                                        ? "Active"
                                        : "Inactive"
                                }}
                            </Badge>
                        </h1>
                        <span class="lowercase">
                            {{ tenantDetails.email }}
                        </span>
                    </div>
                </div>

                <Tabs default-value="customer" class="space-y-4">
                    <TabsList>
                        <TabsTrigger value="customer"> Customer </TabsTrigger>
                        <TabsTrigger value="users">
                            Users in tenant</TabsTrigger
                        >
                    </TabsList>
                    <TabsContent value="customer" class="space-y-4">
                        <TenantCustomerDetails
                            :tenantDetails="tenantDetails"
                            :customerPayments="customerPayments"
                        />
                    </TabsContent>

                    <TabsContent value="users" class="space-y-4"
                        ><TenantUserDetails :tenantUsers="tenantUsers"
                    /></TabsContent>
                </Tabs>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
