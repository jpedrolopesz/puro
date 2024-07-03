<script setup lang="ts">
import {
    ChevronLeft,
    CircleUser,
    Home,
    LineChart,
    Package,
    Package2,
    PanelLeft,
    PlusCircle,
    Search,
    Settings,
    ShoppingCart,
    Upload,
    Users2,
} from "lucide-vue-next";

import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { Label } from "@/Components/ui/label";
import { ToggleGroup, ToggleGroupItem } from "@/Components/ui/toggle-group";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/Components/ui/tooltip";

import AuthenticatedCentralLayout from "../../Layouts/AuthenticatedCentralLayout.vue";

import { defineProps } from "vue";

const props = defineProps({
    product: {
        type: Object as () => any, // Defina o tipo correto do objeto product
        required: true, // Indica que product é obrigatório
    },
});

// Função para formatar a data a partir de um timestamp Unix em segundos
function formatDate(timestamp) {
    const date = new Date(timestamp * 1000); // Convert Unix timestamp to miliseconds
    return date.toLocaleDateString(); // Adjust the format as needed
}
</script>

<template>
    <AuthenticatedCentralLayout>
        <main
            class="grid flex-1 items-start gap-4 p-4 sm:px-1 sm:py-0 md:gap-8"
        >
            <div class="mx-auto grid flex-1 auto-rows-max gap-4">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" class="h-7 w-7">
                        <ChevronLeft class="h-4 w-4" />
                        <span class="sr-only">Back</span>
                    </Button>
                    <h1
                        class="flex-1 shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0"
                    >
                        {{ product.name }}
                    </h1>
                    <Badge
                        :variant="product.active ? 'outline' : ''"
                        :class="[
                            'ml-auto hover:non',
                            'sm:ml-0',
                            product.active
                                ? 'text-white bg-gray-800/80'
                                : 'text-gray-800/80 bg-gray-400/40',
                        ]"
                    >
                        {{ product.active ? "Active" : "Inactive" }}
                    </Badge>

                    <div class="hidden items-center gap-2 md:ml-auto md:flex">
                        <Button variant="outline" size="sm"> Discard </Button>
                        <Button size="sm"> Save Product </Button>
                    </div>
                </div>
                <div
                    class="grid gap-4 md:grid-cols-[1fr_250px] lg:grid-cols-3 lg:gap-8"
                >
                    <div
                        class="grid auto-rows-max items-start gap-4 lg:col-span-2 lg:gap-8"
                    >
                        <Card>
                            <CardHeader>
                                <CardTitle>Plan Details</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-6">
                                    <div class="grid gap-3">
                                        <Label for="name">Name</Label>
                                        <Input
                                            id="name"
                                            type="text"
                                            class="w-full"
                                            v-model="product.name"
                                        />
                                    </div>
                                    <div class="grid gap-3">
                                        <Label for="description"
                                            >Description</Label
                                        >
                                        <Textarea
                                            id="description"
                                            class="min-h-22"
                                            v-model="product.description"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <TabsContent value="week">
                            <Card>
                                <CardHeader class="px-7">
                                    <CardTitle>Plans</CardTitle>
                                    <CardDescription>
                                        Recent orders from your store.
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead> Price </TableHead>
                                                <TableHead>
                                                    Recurrency
                                                </TableHead>
                                                <TableHead>
                                                    Assinantes
                                                </TableHead>
                                                <TableHead> Date </TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            <TableRow
                                                v-for="price in product.prices"
                                                :key="price.id"
                                            >
                                                <TableCell>
                                                    {{
                                                        price.unit_amount / 100
                                                    }}
                                                </TableCell>
                                                <TableCell>
                                                    <span
                                                        v-if="price.recurring"
                                                    >
                                                        {{
                                                            price.recurring
                                                                .interval
                                                        }}
                                                        /
                                                        {{
                                                            price.recurring
                                                                .interval_count
                                                        }}
                                                    </span>
                                                </TableCell>
                                                <TableCell>
                                                    Assinantes
                                                    {{ price.active }}
                                                </TableCell>
                                                <TableCell>
                                                    {{
                                                        formatDate(
                                                            price.created,
                                                        )
                                                    }}
                                                </TableCell>
                                            </TableRow>
                                        </TableBody>
                                    </Table>
                                </CardContent>
                                <CardFooter class="justify-center border-t p-4">
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        class="gap-1"
                                    >
                                        <PlusCircle class="h-3.5 w-3.5" />
                                        Add Variant
                                    </Button>
                                </CardFooter>
                            </Card>
                        </TabsContent>
                    </div>
                    <div class="grid auto-rows-max items-start gap-4 lg:gap-8">
                        <Card>
                            <CardHeader>
                                <CardTitle>Plan Status</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-6">
                                    <div class="grid gap-3">
                                        <Label for="status">Status</Label>
                                        <Select>
                                            <SelectTrigger
                                                id="status"
                                                aria-label="Select status"
                                            >
                                                <SelectValue
                                                    placeholder="Select status"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="draft">
                                                    Draft
                                                </SelectItem>
                                                <SelectItem value="published">
                                                    Active
                                                </SelectItem>
                                                <SelectItem value="archived">
                                                    Archived
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                        <Card class="overflow-hidden">
                            <CardHeader>
                                <CardTitle>Plan imgs</CardTitle>
                                <CardDescription>
                                    Lipsum dolor sit amet, consectetur
                                    adipiscing elit
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <button>
                                            <img
                                                alt="Product image"
                                                class="aspect-square w-full rounded-md object-cover"
                                                height="84"
                                                src="/images/placeholder.svg"
                                                width="84"
                                            />
                                        </button>
                                        <button
                                            class="flex aspect-square w-full items-center justify-center rounded-md border border-dashed"
                                        >
                                            <Upload
                                                class="h-4 w-4 text-muted-foreground"
                                            />
                                            <span class="sr-only">Upload</span>
                                        </button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-2 md:hidden">
                    <Button variant="outline" size="sm"> Discard </Button>
                    <Button size="sm"> Save Plan</Button>
                </div>
            </div>
        </main>
    </AuthenticatedCentralLayout>
</template>
