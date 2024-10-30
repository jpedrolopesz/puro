<script setup lang="ts">
import { ref } from "vue";

interface User {
    name: string;
    date: string;
    testimonial?: string;
}

const isModalOpen = ref(false);
const users = ref<User[]>([
    {
        name: "Maria Silva",
        date: "23/10/2024",
        testimonial: "Produto incrível!",
    },
    {
        name: "João Santos",
        date: "22/10/2024",
        testimonial: "Superou minhas expectativas",
    },
    {
        name: "Ana Oliveira",
        date: "21/10/2024",
        testimonial: "Melhor investimento que fiz!",
    },
    {
        name: "Pedro Costa",
        date: "20/10/2024",
        testimonial: "Recomendo muito!",
    },
]);

const toggleModal = () => {
    isModalOpen.value = !isModalOpen.value;
};

// Fechar modal com tecla ESC
const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === "Escape") {
        isModalOpen.value = false;
    }
};
</script>

<template>
    <div class="max-w-3xl mx-auto">
        <!-- Card Principal -->
        <div class="p-8 space-y-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold text-gray-900">
                        E aí, tudo bem? Sou o João! 👋
                    </h1>
                </div>
                <img
                    class="h-16 w-16 rounded-full object-cover"
                    src="https://avatars.githubusercontent.com/u/67524586?v=4"
                    alt="João"
                />
            </div>

            <div class="prose prose-lg text-gray-700 space-y-4">
                <p>
                    Que bom ter você aqui! 😊 Imagino que você esteja curioso
                    sobre como esse produto pode fazer a diferença para você.
                    Não estou aqui para dizer que sou o melhor ou único na
                    internet
                </p>

                <p>
                    Minha missão é simples: ajudar você a alcançar seus
                    objetivos, e ao mesmo tempo, me aproximar dos meus sonhos.
                </p>

                <p>
                    Cada compra é um passo mais perto para realizar meus sonhos,
                    e eu serei eternamente grato por você fazer parte dessa
                    jornada comigo. 💙
                </p>

                <!-- Botão para abrir o modal -->
                <button
                    @click="toggleModal"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1 duration-200"
                >
                    <span class="mr-2">Ver nossa comunidade incrível</span>
                    <span class="text-xl">✨</span>
                </button>

                <!-- Modal com overlay -->
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <div
                        v-if="isModalOpen"
                        class="fixed inset-0 z-50 overflow-y-auto"
                        @keydown="handleKeydown"
                        tabindex="-1"
                        role="dialog"
                        aria-modal="true"
                    >
                        <!-- Overlay com blur -->
                        <div
                            class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm"
                            @click="toggleModal"
                        ></div>

                        <!-- Container do Modal -->
                        <div
                            class="flex items-center justify-center min-h-screen p-4"
                        >
                            <div class="relative w-full max-w-2xl mx-auto">
                                <!-- Quadro SVG Estilo Cartoon -->
                                <div class="relative bg-transparent rounded-xl">
                                    <svg
                                        class="w-full h-full absolute top-0 left-0 -z-10"
                                        viewBox="0 0 800 600"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <!-- Fundo do quadro com efeito cartoon -->
                                        <path
                                            d="M50 30
                                   H750
                                   C760 30, 770 40, 770 50
                                   V550
                                   C770 560, 760 570, 750 570
                                   H50
                                   C40 570, 30 560, 30 550
                                   V50
                                   C30 40, 40 30, 50 30
                                   Z"
                                            fill="white"
                                            stroke="#2B3F6C"
                                            stroke-width="8"
                                            filter="drop-shadow(3px 3px 6px rgba(0,0,0,0.3))"
                                        />

                                        <!-- Decorações do quadro -->
                                        <path
                                            d="M30 100 Q35 95, 40 100 Q45 105, 50 100"
                                            stroke="#2B3F6C"
                                            stroke-width="4"
                                            fill="none"
                                        />
                                        <path
                                            d="M750 100 Q755 95, 760 100 Q765 105, 770 100"
                                            stroke="#2B3F6C"
                                            stroke-width="4"
                                            fill="none"
                                        />

                                        <!-- Título do quadro -->
                                        <text
                                            x="400"
                                            y="80"
                                            text-anchor="middle"
                                            class="text-2xl font-bold"
                                            fill="#2B3F6C"
                                        >
                                            Nossa Comunidade Incrível ✨
                                        </text>
                                    </svg>

                                    <!-- Botão Fechar -->
                                    <button
                                        @click="toggleModal"
                                        class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 z-50"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>

                                    <!-- Conteúdo da Lista -->
                                    <div class="relative z-10 p-16 space-y-6">
                                        <div class="grid gap-6">
                                            <div
                                                v-for="(user, index) in users"
                                                :key="index"
                                                class="bg-blue-50 rounded-lg p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg"
                                            >
                                                <div
                                                    class="flex items-center space-x-4"
                                                >
                                                    <!-- Avatar cartoon style -->
                                                    <div
                                                        class="w-16 h-16 relative"
                                                    >
                                                        <svg
                                                            class="w-full h-full"
                                                            viewBox="0 0 64 64"
                                                            fill="none"
                                                        >
                                                            <circle
                                                                cx="32"
                                                                cy="32"
                                                                r="30"
                                                                fill="#FFD8CC"
                                                                stroke="#2B3F6C"
                                                                stroke-width="2"
                                                            />
                                                            <path
                                                                d="M20 32a2 2 0 1 0 4 0 2 2 0 0 0-4 0M40 32a2 2 0 1 0 4 0 2 2 0 0 0-4 0"
                                                                fill="#2B3F6C"
                                                            />
                                                            <path
                                                                d="M25 40c3 3 11 3 14 0"
                                                                stroke="#2B3F6C"
                                                                stroke-width="2"
                                                                stroke-linecap="round"
                                                            />
                                                        </svg>
                                                    </div>

                                                    <div class="flex-1">
                                                        <div
                                                            class="flex items-center justify-between"
                                                        >
                                                            <h3
                                                                class="text-lg font-semibold text-blue-900"
                                                            >
                                                                {{ user.name }}
                                                            </h3>
                                                            <span
                                                                class="text-sm text-blue-600"
                                                                >{{
                                                                    user.date
                                                                }}</span
                                                            >
                                                        </div>
                                                        <p
                                                            v-if="
                                                                user.testimonial
                                                            "
                                                            class="mt-1 text-blue-700 italic"
                                                        >
                                                            "{{
                                                                user.testimonial
                                                            }}"
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Decorative elements -->
                                        <div
                                            class="absolute bottom-8 right-8 transform rotate-12"
                                        >
                                            <svg
                                                width="40"
                                                height="40"
                                                viewBox="0 0 40 40"
                                            >
                                                <path
                                                    d="M20 0L23.5 16.5L40 20L23.5 23.5L20 40L16.5 23.5L0 20L16.5 16.5L20 0Z"
                                                    fill="#FFD700"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>

                <div class="mt-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Meus sonhos:
                    </h2>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            Garantir o tratamento e acompanhamento médico para
                            minha família.
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            Aperfeiçoar meus conhecimentos estudando no exterior
                            e desenvolver um produto ainda melhor para meus
                            clientes.
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            Vivenciar a mágica de ver a aurora boreal.
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            Impactar mais de 10.000 sonhadores com a ajuda do
                            meu sistema. ✨
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Modal de Usuários -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
        >
            <div
                class="bg-white rounded-lg max-w-lg w-full max-h-[80vh] overflow-y-auto"
            >
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Nossa Comunidade
                        </h3>
                        <button
                            @click="toggleModal"
                            class="text-gray-500 hover:text-gray-700"
                        >
                            <span class="sr-only">Fechar</span>
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(user, index) in users"
                            :key="index"
                            class="border-b border-gray-200 last:border-0 pb-4"
                        >
                            <div class="flex items-center">
                                <img
                                    class="h-10 w-10 rounded-full"
                                    :src="`/api/placeholder/${40}/${40}`"
                                    :alt="user.name"
                                />
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">
                                        {{ user.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Entrou em {{ user.date }}
                                    </p>
                                    <p
                                        v-if="user.testimonial"
                                        class="mt-1 text-gray-600"
                                    >
                                        "{{ user.testimonial }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
</style>
