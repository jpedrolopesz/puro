<template>
    <CardHeader>
        <CardTitle>Payment Method</CardTitle>
        <CardDescription>
            Add a new payment method to your account.
        </CardDescription>
    </CardHeader>
    <CardContent class="grid gap-6">
        <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input id="name" placeholder="First Last" />
        </div>
        <div class="grid gap-2">
            <Label for="number">Card number</Label>
            <Input id="number" placeholder="" />
        </div>
    </CardContent>

    <div>
        <form @submit.prevent="submitForm">
            <div>
                <label for="priceId">Price ID:</label>
                <input
                    type="text"
                    id="priceId"
                    v-model="form.price_id"
                    required
                />
            </div>

            <div>
                <label for="card-holder-name">Card Holder Name:</label>
                <input
                    type="text"
                    id="card-holder-name"
                    v-model="cardHolderName"
                    required
                />
            </div>

            <div id="card-element">
                <!-- A Stripe Element será inserida aqui -->
            </div>
            <div id="card-errors" role="alert"></div>
            <button type="submit" :disabled="form.processing">
                Start Subscription
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
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

import { onMounted, defineProps } from "vue";
import { loadStripe } from "@stripe/stripe-js";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    priceId: number | string | null;
}>();

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);

const form = useForm({
    price_id: props.priceId ? props.priceId.toString() : "",
    payment_method_id: "",
});

let stripe: any;
let cardElement: any;

const submitForm = async () => {
    if (!stripe || !cardElement) {
        console.error("Stripe or cardElement not initialized");
        return;
    }

    const cardHolderName = document.getElementById(
        "card-holder-name",
    ) as HTMLInputElement;

    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: "card",
        card: cardElement,
        billing_details: { name: cardHolderName.value }, // Adicionando o nome do titular
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

    cardElement = elements.create("card");
    cardElement.mount("#card-element");
});
</script>
