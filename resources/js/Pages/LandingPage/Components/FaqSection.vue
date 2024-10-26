<script setup lang="ts">
import { ref } from "vue";
import { FaqItem } from "../Types";

const faqs = ref<FaqItem[]>([
    {
        question: "Can I try this product for free?",
        answer: "You can can try the demo, you can also check the docs for videos and tutorials, where I explain the product in detail.",
    },
    {
        question: "What does Lifetime access mean?",
        answer: "You get access to all the updates and releases that I push to the project. You can also raise issues and feature requests on Github.",
    },
    {
        question: "Javascript or Typescript?",
        answer: "Supersaas is made with Javascript, but some utilities are written in Typescript.",
    },
    {
        question: "What's the tech stack?",
        answer: "Supersaas is made with Nuxt 3 the fullstack framework, Tailwind CSS for CSS, Nuxt Auth Utils for authentication, Drizzle ORM for database, AWS S3, Nuxthub Blob and Local Storage for file storage, Stripe and Lemonsqueezy for payments, Resend, Sendgrid, Postmark, Plunk for emails, and more.",
    },
    {
        question: "What happens after I purchase a template?",
        answer: "After you purchase the template, you will be invited to the respective github repository. You can then fork the repository and start building your project.",
    },
    {
        question: "Do you offer refunds?",
        answer: "Unfortunately due to the nature of this product, where you get complete access to the codebase, and I have no way to verify if you have used it, I cannot offer refunds.",
    },
    {
        question:
            "I did not like the product, and I haven't used it. Can I get a refund?",
        answer: "I strongly reccomend reaching out to me in case you have doubts about Supersaas, I will gladly give you an entire tour of the procuct and how to use it. I can also help you with any questions you have. This way you can decide if you want to purchase the product or not.",
    },
    {
        question: "Can I use Supersaas for open source projects?",
        answer: "No, this will break the license, I hope you understand.",
    },
    {
        question: "Can I use Supersaas for client projects?",
        answer: "Yes you can, use it for as many projects as you like.",
    },
    {
        question: "Can I use Supersaas for commercial projects?",
        answer: "Yes you can, Supersaas was made for this very purpose.",
    },
    {
        question: "Can I resell this product?",
        answer: "Absolutely not, please read the license for more details.",
    },
    {
        question: "Do you offer technical support?",
        answer: "Yes, as long as it's related to the template codebase. I cannot help with your business logic or customizations that you may have made.",
    },
]);

const toggleFaq = (faq: FaqItem) => {
    faq.isOpen = !faq.isOpen;
};
</script>

<template>
    <div class="py-24 px-4 relative" id="faq">
        <div class="relative max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-16">
                <h2
                    class="max-w-2xl text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 font-display"
                >
                    Got Questions? We've got some answers!
                </h2>
                <p class="mt-4 max-w-2xl text-gray-600 text-2xl">
                    If you have any other questions, feel free to reach out to
                    me at
                    <button
                        variant="link"
                        class="underline"
                        padded="false"
                        color="gray"
                    >
                        fayaz@supersaas.dev
                    </button>
                    I'm happy to help!
                </p>
            </div>

            <!-- FAQ Items -->
            <div
                class="w-full flex flex-col divide-y divide-gray-200 dark:divide-gray-800 -mt-6"
            >
                <div
                    v-for="(faq, index) in faqs"
                    :key="index"
                    class="w-full flex flex-col divide-y divide-gray-200 dark:divide-gray-800"
                >
                    <button
                        type="button"
                        class="focus:outline-none focus-visible:outline-0 disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-none gap-x-1.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 underline-offset-4 focus-visible:ring-inset focus-visible:ring-2 focus-visible:ring-primary-500 dark:focus-visible:ring-primary-400 hover:no-underline inline-flex items-center text-left text-lg py-6 w-full"
                        @click="toggleFaq(faq)"
                    >
                        <span class="text-gray-900 dark:text-white">{{
                            faq.question
                        }}</span>
                        <span
                            class="iconify i-heroicons:chevron-down-20-solid w-5 h-5 ms-auto transform transition-transform duration-200 flex-shrink-0 mr-1.5"
                            :class="{ '-rotate-90': !faq.isOpen }"
                            aria-hidden="true"
                        ></span>
                    </button>

                    <div :style="{ display: faq.isOpen ? 'block' : 'none' }">
                        <div
                            class="text-base text-gray-500 dark:text-gray-400 py-6"
                        >
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.font-display {
    font-family:
        system-ui,
        -apple-system,
        sans-serif;
}

/* Transition for accordion */
.transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .dark\:text-gray-400 {
        color: rgb(156 163 175);
    }

    .dark\:divide-gray-800 {
        border-color: rgb(31 41 55);
    }
}

/* Hover animations */
.hover\:text-gray-700:hover {
    color: rgb(55 65 81);
}

.focus-visible\:ring-2:focus-visible {
    box-shadow: 0 0 0 2px rgb(59 130 246 / 0.5);
}

/* Animation for content */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

div[style*="display: block"] {
    animation: slideDown 0.2s ease-out;
}
</style>
