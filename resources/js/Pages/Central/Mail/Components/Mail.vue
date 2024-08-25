<script lang="ts" setup>
import { Search } from "lucide-vue-next";

import { computed, ref } from "vue";
import { refDebounced } from "@vueuse/core";
import { MailPlus } from "lucide-vue-next";

import MailList from "./MailList.vue";
import MailDisplay from "./MailDisplay.vue";
import { Separator } from "@/Components/ui/separator";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import { TooltipProvider } from "@/Components/ui/tooltip";
import {
    ResizableHandle,
    ResizablePanel,
    ResizablePanelGroup,
} from "@/Components/ui/resizable";

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    tenant_id?: number | null;
    created_at: string;
    updated_at: string;
}

interface Tenant {
    id: number;
    name: string;
    email: string;
    creator_id: number;
    status: string;
    tenancy_name: string;
    tenancy_db_name: string;
    tenancy_about: string;
    created_at: string;
    updated_at: string;
    users: User[];
    creator: User;
    data?: any;
}

interface TenantsWithUsersProps {
    tenantsWithUsers: Tenant[];
}

interface Mail {
    id: string;
    name: string;
    email: string;
    subject: string;

    date: string;
    read: boolean;
    receiver_id: number;
    sender_id: number;
    labels: string[];
}

interface MailProps {
    mails: Mail[];
    defaultLayout?: number[];
    defaultCollapsed?: boolean;
    navCollapsedSize: number;
}

interface CombinedProps extends TenantsWithUsersProps, MailProps {}

const props = withDefaults(defineProps<CombinedProps>(), {
    defaultCollapsed: false,
    defaultLayout: () => [30, 40],
});

const isCollapsed = ref(props.defaultCollapsed);
const selectedMail = ref<string | undefined>(undefined);
const searchValue = ref("");
const debouncedSearch = refDebounced(searchValue, 250);

const filteredMailList = computed(() => {
    let output: Mail[] = [];
    const searchValue = debouncedSearch.value?.trim();
    if (!searchValue) {
        output = props.mails;
    } else {
        output = props.mails.filter((item) => {
            return (
                item.name.includes(debouncedSearch.value) ||
                item.email.includes(debouncedSearch.value) ||
                item.name.includes(debouncedSearch.value) ||
                item.subject.includes(debouncedSearch.value)
                //item.text.includes(debouncedSearch.value)
            );
        });
    }

    return output;
});

const unreadMailList = computed(() =>
    filteredMailList.value.filter((item) => !item.read),
);

const selectedMailData = computed(() =>
    props.mails.find((item) => item.id === selectedMail.value || null),
);

function onCollapse() {
    isCollapsed.value = true;
}

function onExpand() {
    isCollapsed.value = false;
}

function createNewMail() {
    selectedMail.value = null;
}

function handleMailSent(mailId: string) {
    selectedMail.value = mailId;
}
</script>

<template>
    <TooltipProvider :delay-duration="0">
        <ResizablePanelGroup
            id="resize-panel-group-1"
            direction="horizontal"
            class="max-h-full items-stretch"
        >
            <ResizablePanel
                id="resize-panel-1"
                :default-size="defaultLayout[1]"
                :min-size="30"
            >
                <Tabs default-value="all">
                    <div class="flex items-center px-4 py-2">
                        <h1 class="text-xl font-bold ml-4">Inbox</h1>
                        <TabsList class="ml-auto">
                            <TabsTrigger
                                value="all"
                                class="text-zinc-600 dark:text-zinc-200"
                            >
                                All mail
                            </TabsTrigger>
                            <TabsTrigger
                                value="unread"
                                class="text-zinc-600 dark:text-zinc-200"
                            >
                                Unread
                            </TabsTrigger>
                        </TabsList>
                    </div>
                    <Separator class="my-1" />
                    <div
                        class="grid grid-cols-5 gap-2 bg-background/95 p-4 backdrop-blur supports-[backdrop-filter]:bg-background/60"
                    >
                        <form class="col-span-4">
                            <div class="relative">
                                <Search
                                    class="absolute left-2 top-2.5 size-4 text-muted-foreground"
                                />
                                <Input
                                    v-model="searchValue"
                                    placeholder="Search"
                                    class="pl-8"
                                />
                            </div>
                        </form>
                        <div>
                            <Button class="w-full" @click="createNewMail">
                                <MailPlus />
                            </Button>
                        </div>
                    </div>
                    <div class="p-4"></div>
                    <TabsContent value="all" class="m-0">
                        <MailList
                            v-model:selected-mail="selectedMail"
                            :items="filteredMailList"
                        />
                    </TabsContent>
                    <TabsContent value="unread" class="m-0">
                        <MailList
                            v-model:selected-mail="selectedMail"
                            :items="unreadMailList"
                        />
                    </TabsContent>
                </Tabs>
            </ResizablePanel>
            <ResizableHandle id="resiz-handle-2" with-handle />
            <ResizablePanel
                id="resize-panel-2"
                :default-size="defaultLayout[2]"
            >
                <MailDisplay
                    v-model:selected-mail="selectedMail"
                    :mail="selectedMailData || null"
                    :tenantsWithUsers="tenantsWithUsers"
                    @mail-sent="handleMailSent"
                />
            </ResizablePanel>
        </ResizablePanelGroup>
        <Separator />
    </TooltipProvider>
</template>
