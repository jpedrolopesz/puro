<script setup lang="ts">
import { ref, onBeforeUnmount } from "vue";

const isVideoPlaying = ref(false);
const videoUrl = ref("https://www.youtube.com/embed/MFAmnD5InIo");

const playVideo = () => {
    isVideoPlaying.value = true;
    document.body.style.overflow = "hidden";
};

const closeVideo = () => {
    isVideoPlaying.value = false;
    document.body.style.overflow = "auto";
};

onBeforeUnmount(() => {
    document.body.style.overflow = "auto";
});
</script>

<template>
    <section class="py-12 px-4 relative">
        <div class="relative max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center">
                <h2
                    class="mx-auto max-w-xl text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 font-display"
                >
                    How Supersaas works.
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-gray-600 text-2xl">
                    Demo, folder structure, and why I built it.
                </p>
            </div>

            <!-- Video Container -->
            <div
                class="relative rounded-xl overflow-hidden max-w-3xl mx-auto mt-8"
            >
                <!-- Gradient Overlay with Author Info -->
                <div
                    class="absolute top-0 w-full h-24 bg-gradient-to-b from-black to-transparent flex items-center gap-4 px-4 z-10"
                >
                    <span
                        class="relative inline-flex items-center justify-center flex-shrink-0 rounded-full h-10 w-10 text-base"
                    >
                        <img
                            class="rounded-full h-10 w-10 text-base"
                            src="https://github.com/fayazara.png"
                            alt="Author"
                        />
                    </span>
                    <p
                        class="text-white font-medium truncate"
                        style="text-shadow: 0 0 2px rgba(0, 0, 0, 0.5)"
                    >
                        Supersaas - A fullstack Nuxt 3 saas starterkit with DB,
                        Auth, Payments, File Storage and emails
                    </p>
                </div>

                <!-- YouTube Play Button -->
                <div
                    class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 drop-shadow cursor-pointer z-10"
                    @click="playVideo"
                >
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="red">
                        <path
                            d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"
                        />
                    </svg>
                </div>

                <!-- Video Thumbnail/Player -->
                <img
                    src="https://i.ytimg.com/vi/MFAmnD5InIo/maxresdefault.jpg"
                    alt="Supersaas Video Intro"
                    class="mt-8 cursor-pointer w-full h-auto"
                    loading="lazy"
                    @click="playVideo"
                />

                <!-- Video Modal -->
                <div
                    v-if="isVideoPlaying"
                    class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                    @click="closeVideo"
                >
                    <div class="relative w-full max-w-4xl mx-4">
                        <button
                            @click="closeVideo"
                            class="absolute -top-10 right-0 text-white hover:text-gray-300"
                        >
                            Close
                        </button>
                        <div class="relative pb-[56.25%] h-0">
                            <iframe
                                :src="videoUrl + '?autoplay=1'"
                                class="absolute top-0 left-0 w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
