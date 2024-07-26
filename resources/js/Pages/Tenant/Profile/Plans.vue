<script setup lang="ts">
import Layouts from "./Layouts.vue";
import PlanForm from "./Components/PlanForm.vue";
import { Head } from "@inertiajs/vue3";
import PlansTest from "../../Subscription/SubscriptionPlans/PlansTest.vue";
</script>

<template>
    <Head title="Plans" />

    <Layouts
        title="Plans"
        description="If you wish to keep using the application please choose a
    subscription plan below."
    >
        <PlanForm />
    </Layouts>
</template>
