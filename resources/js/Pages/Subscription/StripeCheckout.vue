<template>
    <div class="space-y-2">
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">Subscribe</h2>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <Label for="cardholder-name">Cardholder Name</Label>
                <input
                    type="text"
                    id="cardholder-name"
                    placeholder="Name"
                    v-model="cardHolderName"
                    required
                    class="w-full h-9 rounded-md border border-gray-300 bg-transparent px-3 py-1 text-sm shadow-sm focus:border-gray-300 focus:ring-0"
                />
            </div>

            <div class="mb-4">
                <Label for="card-number">Card Number</Label>

                <div
                    id="card-number"
                    type="number"
                    class="p-2 border border-gray-300 rounded-md shadow-sm"
                ></div>
            </div>

            <div class="mb-4 flex space-x-2">
                <div class="w-1/2">
                    <Label for="expiry-date">Expiry Date</Label>

                    <div
                        id="card-expiry"
                        class="p-2 border border-gray-300 rounded-md shadow-sm"
                    ></div>
                </div>
                <div class="w-1/2">
                    <Label for="card-cvc">CVC</Label>
                    <div
                        id="card-cvc"
                        class="p-2 border border-gray-300 rounded-md shadow-sm"
                    ></div>
                </div>
            </div>

            <div id="card-errors" role="alert" class="mt-2 text-red-500"></div>

            <div class="flex mt-10">
                <slot />

                <Button type="submit" :disabled="form.processing" class="w-full"
                    >Subscription</Button
                >
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";

import { onMounted, ref } from "vue";
import { loadStripe } from "@stripe/stripe-js";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    priceId: number | string | null;
}>();

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);

const cardHolderName = ref("");

const form = useForm({
    price_id: props.priceId ? props.priceId.toString() : "",
    payment_method_id: "",
});

let stripe: any;
let cardNumber: any;
let cardExpiry: any;
let cardCvc: any;

const submitForm = async () => {
    if (!stripe || !cardNumber || !cardExpiry || !cardCvc) {
        console.error("Stripe or card elements not initialized");
        return;
    }

    // Create a payment method using the card elements
    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: "card",
        card: cardNumber,
        billing_details: {
            name: cardHolderName.value,
        },
    });

    if (error) {
        console.error(error.message);
        document.getElementById("card-errors")!.textContent = error.message;
        return;
    }

    form.payment_method_id = paymentMethod.id;

    form.post("/subscription", {
        onSuccess: (page) => {
            if (page.props.requires_action) {
                stripe
                    .handleCardAction(page.props.payment_intent_client_secret)
                    .then((result) => {
                        if (result.error) {
                            console.error(result.error.message);
                            document.getElementById(
                                "card-errors",
                            )!.textContent = result.error.message;
                        } else {
                            form.post("/subscription/confirm", {
                                data: {
                                    payment_intent_id: result.paymentIntent.id,
                                },
                                onSuccess: () => {
                                    console.log(
                                        "Payment successful:",
                                        page.props,
                                    );
                                },
                            });
                        }
                    });
            } else {
                console.log("Payment successful:", page.props);
            }
        },
        onError: (errors) => {
            console.error("Errors:", errors);
        },
    });
};

onMounted(async () => {
    stripe = await stripePromise;
    const elements = stripe.elements();

    cardNumber = elements.create("cardNumber", {
        showIcon: true,
    });

    cardExpiry = elements.create("cardExpiry");
    cardCvc = elements.create("cardCvc");

    cardNumber.mount("#card-number");
    cardExpiry.mount("#card-expiry");
    cardCvc.mount("#card-cvc");
});
</script>
