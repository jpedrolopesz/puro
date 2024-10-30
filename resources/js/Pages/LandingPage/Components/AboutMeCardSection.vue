<script setup lang="ts">
import { ref } from "vue";
import type { User } from "../Types";

const isModalOpen = ref(false);
const users = ref<User[]>([
    {
        name: "Fayaz Ahmed",
        avatar: "https://placehold.co/400x400?text=%2B140",
    },
    {
        name: "Gavin Zhou",
        avatar: "https://api.dicebear.com/9.x/glass/svg?seed=gavin",
    },
    {
        name: "Gavin Zhou",
        avatar: "https://api.dicebear.com/9.x/glass/svg?seed=gavin",
    },
    {
        name: "Gavin Zhou",
        avatar: "https://api.dicebear.com/9.x/glass/svg?seed=gavin",
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
        <div class="text-center">
            <h2
                class="mx-auto max-w-xl text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 font-display"
            >
                É um prazer ter você aqui!
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-gray-600 text-2xl">
                Tenho certeza de que você está curioso sobre como este produto
                pode transformar a sua experiência.
            </p>
        </div>
        <!-- Card Principal -->
        <div class="p-8 space-y-6">
            <div class="flex items-start justify-between">
                <div class="space-y-1 mr-10">
                    <h1 class="prose prose-lg text-gray-700 space-y-4">
                        <p>
                            Eiiiii, e aí? Antes de mais nada, me desculpa por
                            não me apresentar!
                        </p>
                        <p>Esse cara da foto sou eu, e meu nome é Joao 😄</p>

                        <p>Agora sim, vamos lá!</p>
                        <p>
                            Não estou aqui para descrever todas minhas
                            competencias ou dizer o melhor ou o único produto
                            incrivel feito; minha missão é bem mais simples:
                            ajudar você a alcançar seus objetivos enquanto busco
                            realizar os meus sonhos.
                        </p>

                        <p>
                            Gostaria de dizer que cada compra representa um
                            passo em direção à realização dos meus sonhos, e
                            serei eternamente grato por você fazer parte dessa
                            jornada ao meu lado. 💙
                        </p>
                    </h1>
                </div>
                <img
                    class="h-32 w-32 rounded-3xl object-cover"
                    src="https://avatars.githubusercontent.com/u/67524586?v=4"
                    alt="João"
                />
            </div>

            <div class="flex items-center gap-4 mt-6">
                <div class="inline-flex flex-row-reverse justify-end">
                    <span
                        v-for="user in users"
                        :key="user.name"
                        class="relative inline-flex items-center justify-center flex-shrink-0 rounded-full h-6 w-6 text-xs ring-2 ring-white -me-1.5"
                    >
                        <img
                            :src="user.avatar"
                            :alt="user.name"
                            class="rounded-full h-6 w-6 text-xs"
                        />
                    </span>
                </div>
                <p class="text-sm text-gray-500 max-w-xs">
                    <span class="font-semibold text-primary-900"
                        >148+ developers</span
                    >
                    shipping apps in production.
                </p>
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
