<script setup lang="ts">
import Layouts from "./Layouts.vue";
import PlanForm from "./Components/PlanForm.vue";
</script>

<template>
    <Layouts
        title="Plans"
        description="If you wish to keep using the application please choose a
    subscription plan below."
    >
        <PlanForm />
    </Layouts>
</template>
