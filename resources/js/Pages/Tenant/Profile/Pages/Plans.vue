<script lang="ts" setup>
import { ref, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import { Label } from "@/Components/ui/label";
import { Switch } from "@/Components/ui/switch";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

interface Plan {
    id: number;
    stripe_product_id: string;
    name: string;
    description: string | null;
    order: number;
    column_count: number;
    monthly_price_id: string;
    yearly_price_id: string;
    monthly_unit_amount: number;
    yearly_unit_amount: number;
    monthly_recurring_interval: string;
    yearly_recurring_interval: string;
    features: string[];
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    plans: Plan[];
}>();

const isAnnual = ref(false);

const formatPrice = (amount: number) => (amount / 100).toFixed(2);

const filteredPlans = computed(() => {
    return props.plans.map((plan) => ({
        ...plan,
        price: isAnnual.value
            ? plan.yearly_unit_amount
            : plan.monthly_unit_amount,
        interval: isAnnual.value
            ? plan.yearly_recurring_interval
            : plan.monthly_recurring_interval,
        price_id: isAnnual.value ? plan.yearly_price_id : plan.monthly_price_id,
    }));
});

const billingPeriod = computed(() => (isAnnual.value ? "Annually" : "Monthly"));

const toggleBillingPeriod = () => {
    isAnnual.value = !isAnnual.value;
};
</script>

<template>
    <Head title="Plans" />
    <div class="mt-5 md:col-span-2 md:mt-0">
        <!-- Switch to toggle between Monthly and Annually -->
        <div class="flex items-center space-x-2 mb-4">
            <Label for="plan-switch">Monthly</Label>
            <Switch
                id="plan-switch"
                :checked="isAnnual"
                @update:checked="toggleBillingPeriod"
            />
            <Label for="plan-switch">Annually</Label>
        </div>
        <!-- Display plans dynamically -->
        <div class="grid grid-cols-3 gap-4">
            <Card v-for="plan in filteredPlans" :key="plan.id">
                <CardHeader>
                    <CardTitle>{{ plan.name }}</CardTitle>
                    <CardDescription>{{
                        plan.description || "No description available"
                    }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold mb-2">
                        ${{ formatPrice(plan.price) }}
                        <span class="text-sm font-normal">
                            /{{ plan.interval }}
                        </span>
                    </div>
                    <div>Order: {{ plan.order }}</div>
                    <div>Column Count: {{ plan.column_count }}</div>
                    <div>
                        Features:
                        <ul v-if="plan.features && plan.features.length">
                            <li v-for="feature in plan.features" :key="feature">
                                {{ feature }}
                            </li>
                        </ul>
                        <span v-else>No features listed</span>
                    </div>
                    <div>Product ID: {{ plan.stripe_product_id }}</div>
                    <div>Price ID: {{ plan.price_id }}</div>
                    <div>
                        Created:
                        {{ new Date(plan.created_at).toLocaleDateString() }}
                    </div>
                    <div>
                        Updated:
                        {{ new Date(plan.updated_at).toLocaleDateString() }}
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
