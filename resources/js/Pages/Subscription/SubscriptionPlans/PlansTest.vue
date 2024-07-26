<script setup lang="ts">
import { ref } from "vue";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";
import { Label } from "@/Components/ui/label";
import { Switch } from "@/Components/ui/switch";
import { Link } from "@inertiajs/vue3";

// Dados fictícios
const selected = ref({
    name: "Basic Plan",
    price: 29.99,
    slug: "basic",
    description:
        "Access to basic features including 20 courses and up to 5 spectators.",
    max_users: 5,
    id: 1,
});

const planSelect = ref(true);

const plansMonthly = ref([
    {
        id: 1,
        name: "Basic Plan",
        slug: "basic",
        max_users: 5,
        price: 29.99,
    },
    {
        id: 2,
        name: "Pro Plan",
        slug: "pro",
        max_users: 10,
        price: 59.99,
    },
]);

const plansYearly = ref([
    {
        id: 1,
        name: "Basic Plan",
        slug: "basic",
        max_users: 5,
        price: 299.99,
    },
    {
        id: 2,
        name: "Pro Plan",
        slug: "pro",
        max_users: 10,
        price: 599.99,
    },
]);

const selectedPlan = ref(plansMonthly.value[0]); // Valor padrão
</script>

<template>
    <div class="group relative m-auto">
        <div
            class="absolute top-0 h-full w-full rounded-3xl border border-gray-100 bg-white shadow-2xl shadow-gray-600/10 transition duration-500 group-hover:scale-105 lg:group-hover:scale-110"
        ></div>
        <div class="relative sm:flex">
            <div class="space-y-8 p-8 pb-20 sm:w-7/12 sm:pb-8">
                <div class="flex items-center justify-between">
                    <h5 class="text-xl font-semibold text-gray-700">
                        {{ selected.name }}
                    </h5>
                    <div class="relative flex justify-around">
                        <div class="flex">
                            <div class="text-gray-800 font-bold">
                                <span class="text-4xl"
                                    >${{ selected.price }}</span
                                >
                                <span class="text-slate-500 font-medium text-sm"
                                    >/{{ selected.slug }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-start text-sm mb-2 text-gray-600">
                    {{ selected.description }}
                </p>
                <div
                    class="absolute inset-x-0 bottom-6 w-full px-6 sm:static sm:px-0"
                >
                    <Link
                        href="route('register.subscription', selected.id)"
                        class="relative flex h-11 w-full items-center justify-center px-6 before:absolute before:inset-0 before:rounded-lg before:bg-gray-800 before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95"
                    >
                        <span
                            class="relative text-base font-semibold text-white"
                            >Start plan</span
                        >
                    </Link>
                </div>
                <div class="flex items-center">
                    <h4
                        class="flex-shrink-0 pr-4 bg-white text-xs leading-5 tracking-wider font-semibold uppercase text-gray-700"
                    >
                        What's included
                    </h4>
                    <div class="flex-1 border-t-2 border-gray-200"></div>
                </div>
                <ul role="list" class="space-y-5">
                    <li class="flex space-x-3">
                        <svg
                            aria-hidden="true"
                            class="flex-shrink-0 w-4 h-4 text-gray-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <title>Check icon</title>
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span
                            class="text-base font-normal leading-tight text-gray-500"
                            >{{ selected.max_users }} spectator</span
                        >
                    </li>
                    <li class="flex space-x-3">
                        <svg
                            aria-hidden="true"
                            class="flex-shrink-0 w-4 h-4 text-gray-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <title>Check icon</title>
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span
                            class="text-base font-normal leading-tight text-gray-500"
                            >20 Courses</span
                        >
                    </li>
                </ul>
            </div>
            <div class="-mt-16 pb-20 sm:mt-0 sm:w-5/12 sm:pb-0">
                <div
                    class="relative h-full before:absolute before:left-0 before:top-1 before:my-auto before:h-0.5 before:w-full before:rounded-full before:bg-gray-200 dark:before:bg-gray-700 sm:pt-0 sm:before:inset-y-0 sm:before:h-[85%] sm:before:w-0.5"
                >
                    <div
                        class="relative -mt-1 h-full overflow-x-auto pt-7 pb-6 sm:-ml-1 sm:pl-1 overflow-y-hidden"
                    >
                        <div class="w-full px-4">
                            <h2
                                class="text-lg ml-5 mt-2.5 font-medium text-gray-900"
                            >
                                Plans
                            </h2>
                            <p class="mt-1 ml-5 text-sm text-gray-600">
                                If you wish to keep using the application please
                                choose a subscription plan below.
                            </p>
                            <div class="flex items-center space-x-2">
                                <Label for="planSelectMonthly">Monthly</Label>
                                <Switch
                                    id="planSelectMonthly"
                                    v-model="planSelect"
                                    true-value="true"
                                    false-value="false"
                                />
                                <Label for="planSelectMonthly">Annually</Label>
                            </div>

                            <RadioGroup v-model="selectedPlan">
                                <div
                                    v-if="planSelect"
                                    v-for="plan in plansMonthly"
                                    :key="plan.id"
                                    class="flex items-center space-x-2"
                                >
                                    <RadioGroupItem
                                        :id="plan.id"
                                        :key="plan.id"
                                    />
                                    <Label :for="plan.id.toString()">{{
                                        plan.name
                                    }}</Label>
                                </div>
                                <div
                                    v-else
                                    v-for="plan in plansYearly"
                                    ::key="plan.id"
                                    class="flex items-center space-x-2"
                                >
                                    <RadioGroupItem
                                        :id="plan.id.toString()"
                                        :value="plan.id.toString()"
                                    />
                                    <Label :for="plan.id.toString()">{{
                                        plan.name
                                    }}</Label>
                                </div>
                            </RadioGroup>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
