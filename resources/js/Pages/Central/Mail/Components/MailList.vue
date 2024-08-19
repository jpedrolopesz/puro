<script lang="ts" setup>
import { ref } from "vue";
import { formatDistanceToNow } from "date-fns";
import type { Mail } from "../data/mails";
import { ScrollArea } from "@/Components/ui/scroll-area";
import { cn } from "@/lib/utils";
import { Badge } from "@/Components/ui/badge";

interface MailListProps {
    items: Mail[];
}

defineProps<MailListProps>();
const selectedMail = defineModel<string>("selectedMail", { required: false });

const showCreateEmailModal = ref(false);
const newEmail = ref({ name: "", subject: "", text: "" });

function openCreateEmailModal() {
    showCreateEmailModal.value = true;
}

function closeCreateEmailModal() {
    showCreateEmailModal.value = false;
    // Clear the newEmail object
    newEmail.value = { name: "", subject: "", text: "" };
}

function createEmail() {
    // Add logic to create the email
    console.log("Creating new email:", newEmail.value);

    // Close the modal and reset the form
    closeCreateEmailModal();
}

function getBadgeVariantFromLabel(label: string) {
    if (["work"].includes(label.toLowerCase())) return "default";

    if (["personal"].includes(label.toLowerCase())) return "outline";

    return "secondary";
}
</script>

<template>
    <ScrollArea class="h-screen flex">
        <div class="flex-1 flex flex-col gap-2 p-4 pt-0">
            <!-- Botão para criar um novo e-mail -->
            <button
                class="mb-4 rounded-lg border p-3 text-sm bg-blue-600 text-white hover:bg-blue-700 transition"
                @click="openCreateEmailModal"
            >
                Criar Novo E-mail
            </button>

            <!-- Modal ou formulário para criar um novo e-mail -->
            <div
                v-if="showCreateEmailModal"
                class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50"
            >
                <div class="bg-white p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4">Novo E-mail</h2>
                    <form @submit.prevent="createEmail">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium"
                                >Nome</label
                            >
                            <input
                                type="text"
                                id="name"
                                v-model="newEmail.name"
                                class="border rounded-lg w-full p-2"
                                required
                            />
                        </div>
                        <div class="mb-4">
                            <label
                                for="subject"
                                class="block text-sm font-medium"
                                >Assunto</label
                            >
                            <input
                                type="text"
                                id="subject"
                                v-model="newEmail.subject"
                                class="border rounded-lg w-full p-2"
                                required
                            />
                        </div>
                        <div class="mb-4">
                            <label for="text" class="block text-sm font-medium"
                                >Texto</label
                            >
                            <textarea
                                id="text"
                                v-model="newEmail.text"
                                class="border rounded-lg w-full p-2"
                                rows="4"
                                required
                            ></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="button"
                                @click="closeCreateEmailModal"
                                class="mr-2 px-4 py-2 bg-gray-500 text-white rounded-lg"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg"
                            >
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <TransitionGroup name="list" appear>
                <button
                    v-for="item of items"
                    :key="item.id"
                    :class="
                        cn(
                            'flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm transition-all hover:bg-accent',
                            selectedMail === item.id && 'bg-muted',
                        )
                    "
                    @click="selectedMail = item.id"
                >
                    <div class="flex w-full flex-col gap-1">
                        <div class="flex items-center">
                            <div class="flex items-center gap-2">
                                <div class="font-semibold">
                                    {{ item.name }}
                                </div>
                                <span
                                    v-if="!item.read"
                                    class="flex h-2 w-2 rounded-full bg-blue-600"
                                />
                            </div>
                            <div
                                :class="
                                    cn(
                                        'ml-auto text-xs',
                                        selectedMail === item.id
                                            ? 'text-foreground'
                                            : 'text-muted-foreground',
                                    )
                                "
                            >
                                {{
                                    formatDistanceToNow(new Date(item.date), {
                                        addSuffix: true,
                                    })
                                }}
                            </div>
                        </div>

                        <div class="text-xs font-medium">
                            {{ item.subject }}
                        </div>
                    </div>
                    <div class="line-clamp-2 text-xs text-muted-foreground">
                        {{ item.text.substring(0, 300) }}
                    </div>
                    <div class="flex items-center gap-2">
                        <Badge
                            v-for="label of item.labels"
                            :key="label"
                            :variant="getBadgeVariantFromLabel(label)"
                        >
                            {{ label }}
                        </Badge>
                    </div>
                </button>
            </TransitionGroup>
        </div>
    </ScrollArea>
</template>

<style scoped>
.list-move,
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(15px);
}

.list-leave-active {
    position: absolute;
}
</style>
