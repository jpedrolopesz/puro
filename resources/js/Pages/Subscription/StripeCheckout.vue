<template>
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
                <label for="additionalInfo">Additional Info:</label>
                <input
                    type="text"
                    id="additionalInfo"
                    v-model="form.additional_info"
                    required
                />
            </div>
            <div>
                <label for="amount">Amount (cents):</label>
                <input
                    type="number"
                    id="amount"
                    v-model="form.amount"
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
import { ref, onMounted } from "vue";
import { loadStripe } from "@stripe/stripe-js";
import { useForm } from "@inertiajs/vue3";

// Substitua pela sua chave pública do Stripe
const stripePromise = loadStripe(
    "pk_test_51LRjEpGQW0U1Pfqxy4rwka8HWdsBTnY2S2Jsiz9vUB8XstTHI47i3NuYkyJH1ZZ6sbhoOqlepPENnrJjy2kA9sCm00kus3pIY8",
);

const form = useForm({
    price_id: "",
    additional_info: "",
    token: "",
    amount: 0,
});

let stripe: any;
let cardElement: any;

const submitForm = async () => {
    if (!stripe || !cardElement) {
        console.error("Stripe or cardElement not initialized");
        return;
    }

    const { token, error } = await stripe.createToken(cardElement);

    if (error) {
        // Exibir erro
        console.error(error.message);
        document.getElementById("card-errors")!.textContent = error.message;
        return;
    }

    form.token = token!.id;

    form.post("/subscription", {
        onSuccess: (page) => {
            if (page.props.requires_action) {
                stripe
                    .handleCardAction(page.props.payment_intent_client_secret)
                    .then((result) => {
                        if (result.error) {
                            // Handle error here
                            console.error(result.error.message);
                            document.getElementById(
                                "card-errors",
                            )!.textContent = result.error.message;
                        } else {
                            // The payment has been processed!
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
                // Lida com sucesso
                console.log("Payment successful:", page.props);
            }
        },
        onError: (errors) => {
            // Lida com erros
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

<style scoped>
/* Adicione qualquer estilo necessário aqui */
</style>
