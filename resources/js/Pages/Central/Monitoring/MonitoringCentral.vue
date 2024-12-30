<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";

// Estado para mensagens recebidas
const messages = ref<string[]>([]);

// Estado para o indicador de ping/pong
const isPongReceived = ref(false);
let pingPongTimeout: number;

onMounted(() => {
    // Escutar mensagens do canal "monitoring-channel"
    Echo.channel("monitoring-channel").listen(
        ".monitoring-event",
        (data: { message: string }) => {
            messages.value.push(data.message);
        },
    );

    // Monitorar ping/pong no WebSocket
    Echo.connector.pusher.connection.bind("pusher:ping", () => {
        console.log("Ping recebido");
    });

    Echo.connector.pusher.connection.bind("pusher:pong", () => {
        console.log("Pong recebido");
        isPongReceived.value = true;

        // Resetar o estado após 1 segundo
        clearTimeout(pingPongTimeout);
        pingPongTimeout = window.setTimeout(() => {
            isPongReceived.value = false;
        }, 1000);
    });
});

onUnmounted(() => {
    // Limpar eventos e timeouts
    Echo.connector.pusher.connection.unbind("pusher:ping");
    Echo.connector.pusher.connection.unbind("pusher:pong");
    clearTimeout(pingPongTimeout);
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <!-- Título -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Monitoramento em Tempo Real
            </h1>
            <!-- Indicador de Conexão -->
            <div
                :class="[
                    'w-4 h-4 rounded-full transition',
                    isPongReceived ? 'bg-green-500' : 'bg-gray-400',
                ]"
                title="Status do WebSocket"
            ></div>
        </div>

        <!-- Área de Mensagens -->
        <div class="bg-white shadow-md rounded-lg p-4 max-w-3xl mx-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">
                Mensagens Recebidas:
            </h2>
            <ul class="space-y-2">
                <li
                    v-for="(message, index) in messages"
                    :key="index"
                    class="p-2 bg-gray-100 rounded-md shadow-sm text-gray-800"
                >
                    {{ message }}
                </li>
            </ul>
            <!-- Placeholder para quando não há mensagens -->
            <p
                v-if="messages.length === 0"
                class="text-gray-500 text-center mt-4"
            >
                Nenhuma mensagem recebida ainda.
            </p>
        </div>
    </div>
</template>

<style scoped>
/* Adicione animação para o indicador de ping/pong */
@keyframes pulse {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
}

.rounded-full.bg-green-500 {
    animation: pulse 1s infinite ease-in-out;
}
</style>
