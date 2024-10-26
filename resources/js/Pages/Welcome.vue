<template>
    <div>
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white shadow-sm">
            <div
                class="sm:px-6 lg:px-8 max-w-7xl mx-auto flex h-16 items-center justify-between px-4 transition-all"
            >
                <!-- Logo and Nav -->
                <div class="flex items-center gap-5">
                    <router-link to="/" class="flex items-center gap-2">
                        <img
                            class="h-8 w-auto"
                            src="/logo.png"
                            alt="Supersaas logo"
                        />
                        <p class="text-lg sm:text-xl font-bold tracking-tight">
                            Super SaaS
                        </p>
                    </router-link>

                    <!-- Discount Banner -->
                    <div
                        class="group bg-gray-100 relative hidden items-center gap-2 rounded-full px-3 py-2 text-xs text-slate-900 ring-1 ring-inset ring-black/[0.08] hover:bg-gray-50 hover:ring-black/[0.13] lg:flex"
                    >
                        <span class="font-semibold">Discount Offer</span>
                        <svg
                            width="2"
                            height="2"
                            aria-hidden="true"
                            class="fill-slate-900"
                        >
                            <circle cx="1" cy="1" r="1"></circle>
                        </svg>
                        <span class="font-medium text-gray-500"
                            >Get $50 Off with code 50OFF</span
                        >
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden items-center gap-2 sm:flex">
                    <router-link
                        v-for="item in navigationItems"
                        :key="item.text"
                        :to="item.link"
                        class="focus:outline-none focus-visible:outline-0 disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-1.5 text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                    >
                        {{ item.text }}
                    </router-link>

                    <!-- Buy Now Button -->
                    <a
                        href="https://store.supersaas.dev/buy"
                        class="focus:outline-none focus-visible:outline-0 flex-shrink-0 font-medium rounded-full text-sm px-6 py-2 text-white bg-gray-900 hover:bg-gray-800 shiny-shimmer"
                    >
                        Press
                        <span
                            class="py-0.5 px-2 border border-white/30 rounded-md"
                            >B</span
                        >
                        to buy now for $199
                    </a>
                </div>

                <!-- Mobile Navigation -->
                <div class="sm:hidden flex items-center gap-2">
                    <div class="w-full">
                        <a
                            href="https://store.supersaas.dev/buy"
                            class="focus:outline-none focus-visible:outline-0 flex-shrink-0 font-medium rounded-md text-sm px-2.5 py-1.5 text-white bg-gray-900 hover:bg-gray-800"
                        >
                            Buy Now for $199
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button
                        @click="toggleMobileMenu"
                        class="menu-toggle relative flex h-8 w-8 items-center justify-center rounded-full border bg-white hover:bg-gray-100"
                    >
                        <div
                            class="menu-bar absolute h-[1.5px] w-[15px] translate-y-[-3px] bg-gray-700"
                        ></div>
                        <div
                            class="menu-bar absolute h-[1.5px] w-[15px] translate-y-[3px] bg-gray-700"
                        ></div>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="min-h-screen">
            <!-- Hero Section -->
            <div class="max-w-6xl mx-auto px-4 py-16 md:py-24">
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 place-items-center gap-16"
                >
                    <div>
                        <h1
                            class="text-5xl sm:text-6xl font-bold tracking-tight text-gray-800 font-display"
                        >
                            The Simple, Fast & Smart Nuxt 3 Fullstack Kit.
                        </h1>
                        <div
                            class="space-y-4 mt-6 text-lg text-gray-700 max-w-lg"
                        >
                            <p>I analysed the best starter kits.</p>
                            <p>
                                Cherry-picked their good features and enhanced
                                the areas that needed improvement & added
                                everything you need to build a modern SaaS
                            </p>
                        </div>

                        <!-- Purchase Section -->
                        <div class="mt-8 relative">
                            <div class="max-w-md">
                                <a
                                    href="https://store.supersaas.dev/buy"
                                    class="shiny-shimmer bg-black text-white focus:ring-2 focus:ring-black/20 w-full block text-center text-base sm:text-lg px-5 py-3 rounded-lg"
                                >
                                    Buy Now for $199
                                </a>
                            </div>
                        </div>

                        <!-- Users Section -->
                        <div class="flex items-center gap-4 mt-6">
                            <div
                                class="inline-flex flex-row-reverse justify-end"
                            >
                                <template
                                    v-for="user in users"
                                    :key="user.name"
                                >
                                    <span
                                        class="relative inline-flex items-center justify-center flex-shrink-0 rounded-full h-6 w-6 text-xs ring-2 ring-white -me-1.5"
                                    >
                                        <img
                                            :src="user.avatar"
                                            :alt="user.name"
                                            class="rounded-full h-6 w-6 text-xs"
                                        />
                                    </span>
                                </template>
                            </div>
                            <p class="text-sm text-gray-500 max-w-xs">
                                <span class="font-semibold text-primary-900"
                                    >148+ developers</span
                                >
                                shipping apps in production.
                            </p>
                        </div>
                    </div>

                    <!-- Feature Cards -->
                    <div>
                        <ul class="divide-y divide-gray-200 [&>li]:py-4">
                            <li
                                v-for="feature in features"
                                :key="feature.title"
                                class="flex gap-4"
                            >
                                <div
                                    :class="[
                                        'rounded-xl h-14 w-14 flex-shrink-0 text-white flex items-center justify-center border-4 border-white metal-shadow',
                                        feature.bgColor,
                                    ]"
                                >
                                    <component
                                        :is="feature.icon"
                                        class="h-6 w-6"
                                    />
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        {{ feature.title }}
                                    </p>
                                    <p class="mt-2 text-gray-600">
                                        {{ feature.description }}
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Additional sections would go here -->

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
                                Supersaas - A fullstack Nuxt 3 saas starterkit
                                with DB, Auth, Payments, File Storage and emails
                            </p>
                        </div>

                        <!-- YouTube Play Button -->
                        <div
                            class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 drop-shadow cursor-pointer z-10"
                            @click="playVideo"
                        >
                            <svg
                                class="w-full h-full"
                                viewBox="0 0 24 24"
                                fill="red"
                            >
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

            <section class="py-12 px-4 relative">
                <div class="relative max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center">
                        <h2
                            class="mx-auto max-w-xl text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 font-display"
                        >
                            6 major things Supersaas comes pre installed with
                        </h2>
                        <p class="mx-auto mt-4 max-w-xl text-gray-600 text-2xl">
                            I've purposely not added unnecessary things.
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="mt-24 space-y-32">
                        <!-- Feature sections -->
                        <div
                            v-for="(feature, index) in features"
                            :key="index"
                            class="grid grid-cols-1 md:grid-cols-2 gap-8"
                        >
                            <div>
                                <h3 class="text-xl font-semibold text-black">
                                    {{ feature.title }}
                                </h3>
                                <p class="mt-4">{{ feature.description }}</p>

                                <!-- Code examples if they exist -->
                                <div
                                    v-if="feature.codeExamples"
                                    class="flex items-center gap-x-2 gap-y-1 flex-wrap"
                                >
                                    <p
                                        v-for="(
                                            code, codeIndex
                                        ) in feature.codeExamples"
                                        :key="codeIndex"
                                        class="font-mono px-1 py-0.5 bg-gray-200 rounded text-sm mt-2 text-gray-700"
                                    >
                                        {{ code }}
                                    </p>
                                </div>

                                <!-- Feature List -->
                                <ul
                                    class="mt-6 grid grid-cols-2 gap-2 [&>li]:bg-gray-100 [&>li]:rounded-md [&>li]:p-2"
                                >
                                    <li
                                        v-for="(
                                            item, itemIndex
                                        ) in feature.items"
                                        :key="itemIndex"
                                        class="flex items-center gap-2"
                                    >
                                        <span
                                            :class="[
                                                'iconify',
                                                item.icon,
                                                'h-6 w-6',
                                            ]"
                                            aria-hidden="true"
                                        ></span>
                                        {{ item.text }}
                                    </li>
                                </ul>

                                <!-- Read More Link -->
                                <div class="mt-8">
                                    <router-link
                                        :to="feature.link"
                                        class="focus:outline-none focus-visible:outline-0 disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100 inline-flex items-center"
                                    >
                                        <span class="">Read more</span>
                                        <span
                                            class="iconify i-ph:arrow-right flex-shrink-0 h-5 w-5"
                                            aria-hidden="true"
                                        ></span>
                                    </router-link>
                                </div>
                            </div>

                            <!-- Video Demo -->
                            <div
                                class="h-full w-full relative rounded-xl overflow-hidden ring-8 ring-gray-500/10"
                            >
                                <video
                                    autoplay
                                    loop
                                    muted
                                    playsinline
                                    class="h-full w-full object-cover"
                                >
                                    <source
                                        :src="feature.videoUrl"
                                        type="video/mp4"
                                    />
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div
                    class="relative max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12 px-4 lg:px-0"
                >
                    <div
                        v-for="(card, index) in cards"
                        :key="card.title"
                        :class="[
                            'rounded-2xl p-8 flex flex-col border-4 border-white metal-shadow text-white transition-all duration-500 ease-spring',
                            card.bgColor,
                            {
                                'translate-x-0 lg:translate-x-[80px] rotate-0 lg:rotate-2':
                                    index === 0,
                                'translate-x-0 lg:translate-x-[40px] rotate-0 lg:-rotate-2':
                                    index === 1,
                                'translate-x-0 lg:translate-x-[-40px] rotate-0 lg:rotate-2':
                                    index === 2,
                                'translate-x-0 lg:translate-x-[-80px] rotate-0 lg:-rotate-2':
                                    index === 3,
                            },
                        ]"
                    >
                        <h3
                            class="text-4xl font-bold tracking-tight font-display mb-8"
                        >
                            {{ card.title }}
                        </h3>
                        <p class="mt-auto text-lg">
                            {{ card.description }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="py-24 px-4 relative">
                <div class="relative max-w-6xl mx-auto">
                    <div
                        class="grid grid-cols-1 gap-x-12 gap-y-8 lg:gap-y-24 pb-20 xl:grid-cols-3"
                    >
                        <!-- Product Info Sections -->
                        <template
                            v-for="(section, index) in sections"
                            :key="index"
                        >
                            <!-- Section Header -->
                            <div>
                                <h2
                                    class="max-w-2xl text-4xl font-semibold tracking-tight text-gray-900 font-display flex justify-center gap-2"
                                >
                                    <span
                                        :class="[
                                            'iconify',
                                            section.icon,
                                            'w-10 h-10',
                                        ]"
                                        aria-hidden="true"
                                    ></span>
                                    {{ section.title }}
                                </h2>
                            </div>

                            <!-- Section Content -->
                            <div
                                class="max-w-[54rem] text-lg leading-8 text-slate-600 col-span-2 prose"
                            >
                                <template
                                    v-for="(
                                        detail, detailIndex
                                    ) in section.details"
                                    :key="detailIndex"
                                >
                                    <p>
                                        <strong>{{ detail.title }}</strong>
                                        {{ detail.description }}
                                    </p>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 rounded-3xl overflow-hidden relative">
            <div class="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8 lg:py-32">
                <!-- Footer content -->
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    name: "LandingPage",

    data() {
        return {
            navigationItems: [
                { text: "Demo", link: "https://demo.supersaas.dev" },
                { text: "FAQ", link: "/#faq" },
                { text: "Docs", link: "/docs" },
            ],

            isVideoPlaying: false,
            videoUrl: "https://www.youtube.com/embed/MFAmnD5InIo", // Replace with your actual video ID

            users: [
                {
                    name: "Fayaz Ahmed",
                    avatar: "https://placehold.co/400x400?text=%2B140",
                },
                {
                    name: "Gavin Zhou",
                    avatar: "https://api.dicebear.com/9.x/glass/svg?seed=gavin",
                },
                // Add more users as needed
            ],

            features: [
                {
                    title: "Authentication",
                    description:
                        "Password, Passcode, Magic Link, Social login, Passkey.",
                    icon: "LockIcon",
                    bgColor: "bg-rose-500",
                },
                {
                    title: "Database",
                    description: "Supports major databases with Drizzle ORM.",
                    icon: "DatabaseIcon",
                    bgColor: "bg-teal-500",
                },
                {
                    title: "Easy Login and User Management",
                    description:
                        "We're all famililar with the pain of writing auth logic and the amount of use cases that come with it. I wanted to make it stupid simple and easy to use.",
                    link: "/authentication",
                    videoUrl:
                        "https://essentials.supersaas.dev/essentials_passkeys.mp4",
                    items: [
                        {
                            icon: "i-ph:password-duotone",
                            text: "Email/Password",
                        },
                        {
                            icon: "i-ph:numpad-duotone",
                            text: "One-time Password",
                        },
                        {
                            icon: "i-ph:link-simple-duotone",
                            text: "Magic Link",
                        },
                        {
                            icon: "i-logos:google-icon",
                            text: "15+ Social Logins",
                        },
                        {
                            icon: "i-ph:fingerprint-simple-duotone",
                            text: "Passkeys",
                        },
                        {
                            icon: "i-ph:user-circle-check-duotone",
                            text: "Email Verification",
                        },
                        {
                            icon: "i-ph:arrow-counter-clockwise-duotone",
                            text: "Password Reset",
                        },
                        {
                            icon: "i-ph:plugs-connected-duotone",
                            text: "Link Multiple IDs",
                        },
                    ],
                },
                {
                    title: "Simple Billing Management using a single composable",
                    description:
                        "Supports Stripe and Lemonsqueezy with one unified method.",
                    link: "/payments",
                    videoUrl:
                        "https://essentials.supersaas.dev/essentials_posts-subs.mp4",
                    codeExamples: [
                        'usePayment("stripe")',
                        'usePayment("lemonsqueezy")',
                    ],
                    items: [
                        { icon: "i-ph:receipt-duotone", text: "Manage Plans" },
                        {
                            icon: "i-ph:credit-card-duotone",
                            text: "Update Payment Methods",
                        },
                        {
                            icon: "i-ph:receipt-x-duotone",
                            text: "Cancel Subscriptions",
                        },
                        {
                            icon: "i-ph:upload-simple-duotone",
                            text: "Update Plans",
                        },
                        {
                            icon: "i-ph:webhooks-logo-duotone",
                            text: "Webhooks",
                        },
                    ],
                },
                {
                    title: "Manage Profile Settings",
                    description:
                        "A simple way for users to manage and update their profile settings.",
                    link: "/settings-page",
                    videoUrl:
                        "https://essentials.supersaas.dev/essentials_settings.mp4",
                    items: [
                        {
                            icon: "i-ph:user-circle-gear-duotone",
                            text: "Update Name and Avatar",
                        },
                        {
                            icon: "i-ph:link-break-duotone",
                            text: "Unlink Social Accounts",
                        },
                        {
                            icon: "i-ph:password-duotone",
                            text: "Change Passwords",
                        },
                        {
                            icon: "i-ph:fingerprint-simple-duotone",
                            text: "Remove Passkeys",
                        },
                    ],
                },
                {
                    title: "Easy File Storage",
                    description:
                        "Supports S3 and local files with a simple API.",
                    link: "/file-storage",
                    videoUrl:
                        "https://essentials.supersaas.dev/essentials_file-storage.mp4",
                    codeExamples: [
                        'useFileStorage("S3")',
                        'useFileStorage("local")',
                    ],
                    items: [
                        {
                            icon: "i-ph:upload-simple-duotone",
                            text: "Upload Files from Server",
                        },
                        {
                            icon: "i-ph:upload-duotone",
                            text: "Upload Directly to S3",
                        },
                        {
                            icon: "i-ph:folder-open-duotone",
                            text: "Local File Storage",
                        },
                        { icon: "i-ph:trash-duotone", text: "Delete Files" },
                    ],
                },
                {
                    title: "Admin Panel",
                    description:
                        "Manage users, transactions, and waitlists easily.",
                    link: "/admin-panel",
                    videoUrl:
                        "https://essentials.supersaas.dev/essentials_admin.mp4",
                    items: [
                        { icon: "i-ph:users-duotone", text: "User Management" },
                        {
                            icon: "i-ph:user-plus-duotone",
                            text: "Invite New Users",
                        },
                        {
                            icon: "i-ph:envelope-duotone",
                            text: "Resend Verification Emails",
                        },
                        {
                            icon: "i-ph:arrows-counter-clockwise-duotone",
                            text: "Send Password Reset Emails",
                        },
                        {
                            icon: "i-ph:user-circle-minus-duotone",
                            text: "Ban/Unban Users",
                        },
                        { icon: "i-ph:funnel-duotone", text: "Filter Users" },
                    ],
                },
                {
                    title: "Easy API Generation",
                    description:
                        "Generate API endpoints and database queries with a single command.",
                    link: "/api-generation",
                    videoUrl:
                        "https://essentials.supersaas.dev/essentials_api-generator.mp4",
                    codeExamples: ["pnpm generate:api posts"],
                    items: [],
                },
            ],

            cards: [
                {
                    title: "Always Fresh",
                    description:
                        "Our codebase is always current and up-to-date. I personally ensure that every piece of code is reviewed before it's published.",
                    bgColor: "bg-rose-500",
                },
                {
                    title: "Best Practices",
                    description:
                        "I adhere to best practices and standards to guarantee that the codebase remains maintainable and scalable.",
                    bgColor: "bg-emerald-500",
                },
                {
                    title: "Well Documented",
                    description:
                        "Thoroughly documented with videos explaining features, making it easy for developers to understand.",
                    bgColor: "bg-cyan-500",
                },
                {
                    title: "Easy to Customize",
                    description:
                        "Supersaas offers easy customization with sensible abstractions, enabling developers to tailor it to their specific needs.",
                    bgColor: "bg-indigo-500",
                },
            ],

            sections: [
                {
                    title: "What's in the box?",
                    icon: "i-ph:package-duotone",
                    details: [
                        {
                            title: "The Codebase.",
                            description:
                                " When you purchase an Supersaas license, you get invited to our Github repository. You will own access to the codebase for life, including future ehnancements and updates.",
                        },
                        {
                            title: "The License.",
                            description:
                                " A License Key with lifetime access to the codebase.",
                        },
                        {
                            title: "Saas recipes (Coming soon).",
                            description:
                                " I am planning on making regularly used saas recipes like Background Jobs, AI Image generation, Websockets, User onboarding, Realtime notifications using SSE, Caching data etc. Which you will get access to without any cost.",
                        },
                    ],
                },
                {
                    title: "Made to ship fast",
                    icon: "i-ph:rocket-launch-duotone",
                    details: [
                        {
                            title: "Easy to customise.",
                            description:
                                " Written in a way that makes it extremly easy to customise. No complex abstractions, open whatever file you like and just edit it directly.",
                        },
                        {
                            title: "Thoroughly tested.",
                            description:
                                " Every piece of code I write, I make sure to test it with a variety of inputs and edge cases.",
                        },
                        {
                            title: "Less Opinionated.",
                            description:
                                " I've not added my personal coding style, patterns and tried to keep it as generic as possible.",
                        },
                    ],
                },
                {
                    title: "Lifetime access",
                    icon: "i-ph:infinity-duotone",
                    details: [
                        {
                            title: "No Subscriptions.",
                            description:
                                " This is a one time purchase, we will never charge you for updates or subscriptions.",
                        },
                        {
                            title: "Unlimited Projects.",
                            description:
                                " Create as many projects as you like. Make saas apps, create freelance projects or just learn how to build a saas app from scratch.",
                        },
                        {
                            title: "Free updates.",
                            description:
                                " All future updates and releases will be free. You don't need to pay for updates.",
                        },
                    ],
                },
            ],

            isMobileMenuOpen: false,
        };
    },

    methods: {
        toggleMobileMenu() {
            this.isMobileMenuOpen = !this.isMobileMenuOpen;
        },
        playVideo() {
            this.isVideoPlaying = true;
            // Prevent body scrolling when modal is open
            document.body.style.overflow = "hidden";
        },

        closeVideo() {
            this.isVideoPlaying = false;
            // Restore body scrolling when modal is closed
            document.body.style.overflow = "auto";
        },
        beforeDestroy() {
            document.body.style.overflow = "auto";
        },
    },
};
</script>

<style scoped>
.font-display {
    font-family:
        system-ui,
        -apple-system,
        sans-serif;
}

.font-display {
    font-family:
        system-ui,
        -apple-system,
        sans-serif;
}

.metal-shadow {
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.ease-spring {
    transition-timing-function: cubic-bezier(0.87, 0, 0.13, 1);
}

/* Hover effects */
.rounded-2xl:hover {
    transform: translateY(-4px);
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .rounded-2xl {
        transform: none !important;
        rotate: none !important;
    }
}

/* Optional: Add smooth transition for hover effects */
.ring-gray-500\/10 {
    transition: all 0.3s ease;
}

.ring-gray-500\/10:hover {
    box-shadow: 0 0 0 8px rgba(107, 114, 128, 0.15);
}

/* Optional: Add hover effect to play button */
.drop-shadow {
    filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.07))
        drop-shadow(0 2px 2px rgb(0 0 0 / 0.06));
    transition: transform 0.2s ease-in-out;
}

.drop-shadow:hover {
    transform: scale(1.1) translate(-45%, -45%);
}

/* Ensure modal appears above other content */
.z-50 {
    z-index: 50;
}
.metal-shadow {
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.shiny-shimmer {
    position: relative;
    overflow: hidden;
}

.shiny-shimmer::after {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.3) 50%,
        rgba(255, 255, 255, 0) 100%
    );
    transform: rotate(30deg);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    from {
        transform: rotate(30deg) translateX(-100%);
    }
    to {
        transform: rotate(30deg) translateX(100%);
    }
}

/* Style for prose content */
.prose {
    max-width: 54rem;
}

.prose p {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
}

.prose strong {
    color: #111827;
    font-weight: 600;
}

/* Optional: Add animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.grid > div {
    animation: fadeIn 0.5s ease-out forwards;
}

/* Stagger animation for multiple elements */
.grid > div:nth-child(1) {
    animation-delay: 0.1s;
}
.grid > div:nth-child(2) {
    animation-delay: 0.2s;
}
.grid > div:nth-child(3) {
    animation-delay: 0.3s;
}
.grid > div:nth-child(4) {
    animation-delay: 0.4s;
}
.grid > div:nth-child(5) {
    animation-delay: 0.5s;
}
.grid > div:nth-child(6) {
    animation-delay: 0.6s;
}
</style>
