<script setup lang="ts">
import type {
    ColumnFiltersState,
    ExpandedState,
    SortingState,
    VisibilityState,
} from "@tanstack/vue-table";
import {
    FlexRender,
    createColumnHelper,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from "@tanstack/vue-table";
import { ArrowUpDown, ChevronDown, GitCompareArrows } from "lucide-vue-next";

import { h, ref } from "vue";
import DropdownAction from "./DropdownAction.vue";

import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";

import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Input } from "@/Components/ui/input";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { cn, valueUpdater } from "@/lib/utils";
import { defineProps } from "vue";

export interface Price {
    id: string;
    active: boolean;
    billing_scheme: "per_unit" | "tiered";
    created: number;
    currency: string;
    nickname: string;
    recurring: {
        interval: "month" | "year";
        interval_count: number;
    };
    trial_period_days: number | null;
    unit_amount: number;
    subscription_count?: number;
}

const props = defineProps<{
    data: Price[];
    priceDefault: string;
}>();

const data = props.data;

const columnHelper = createColumnHelper<Price>();

const columns = [
    columnHelper.accessor("unit_amount", {
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Price", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })],
            );
        },
        cell: ({ row }) => {
            const price = row.original;
            return h("div", { class: "flex flex-col" }, [
                h(
                    "div",
                    { class: "flex items-end font-semibold space-x-1.5" },
                    [
                        h("span", { class: "uppercase" }, price.currency),
                        h("span", {}, (price.unit_amount / 100).toFixed(2)),
                    ],
                ),
                h(
                    "div",
                    {
                        class: "flex items-center space-x-1 font-normal text-xs",
                    },
                    [
                        h(GitCompareArrows, { class: "w-3 h-3" }),
                        h("span", {}, price.recurring.interval),
                    ],
                ),
            ]);
        },
    }),

    columnHelper.accessor("id", {
        header: "",
        cell: ({ row }) => {
            const price = row.original;
            return h(TableCell, {}, () => [
                price.id === props.priceDefault &&
                    h(Badge, { variant: "outline" }, () => "Default"),
                price.active === false &&
                    h(Badge, { variant: "outline" }, () => "Archived"),
            ]);
        },
    }),

    columnHelper.accessor("nickname", {
        header: "Description",
        cell: ({ row }) => {
            const price = row.original;
            return h(TableCell, {}, () => [
                price.nickname
                    ? h("span", {}, price.nickname)
                    : h("span", {}, "_"),
            ]);
        },
    }),

    columnHelper.accessor("subscription_count", {
        header: "Subscriptions",
        cell: ({ row }) => {
            const price = row.original;
            return h(TableCell, {}, () => [
                h("span", {}, price.subscription_count),
                h("span", { class: "ml-2" }, "active"),
            ]);
        },
    }),

    columnHelper.accessor("created", {
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Date", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })],
            );
        },
        cell: ({ row }) => {
            const createdDate = new Date(row.getValue("created") * 1000);
            return h(
                "div",

                createdDate.toLocaleDateString(),
            );
        },
    }),

    columnHelper.display({
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const price = row.original;
            const priceDefaultId = props.priceDefault;

            return h(
                "div",
                { class: "relative" },
                h(DropdownAction, {
                    price,
                    priceDefaultId,
                    onExpand: row.toggleExpanded,
                }),
            );
        },
    }),
];

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});
const expanded = ref<ExpandedState>({});
const pagination = ref<PaginationState>({
    pageIndex: 0,
    pageSize: 5,
});

const table = useVueTable({
    data,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),

    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnVisibility),
    onRowSelectionChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, rowSelection),
    onExpandedChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, expanded),
    onPaginationChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, pagination),
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
        get expanded() {
            return expanded.value;
        },
        get pagination() {
            return pagination.value;
        },
    },
});
</script>

<template>
    <div class="w-full">
        <div class="flex gap-2 items-center py-4">
            <Input
                class="max-w-sm"
                placeholder="Filter by Description..."
                :model-value="
                    table.getColumn('nickname')?.getFilterValue() as string
                "
                @update:model-value="
                    table.getColumn('nickname')?.setFilterValue($event)
                "
            />
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline" class="ml-auto">
                        Columns <ChevronDown class="ml-2 h-4 w-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuCheckboxItem
                        v-for="column in table
                            .getAllColumns()
                            .filter((column) => column.getCanHide())"
                        :key="column.id"
                        class="capitalize"
                        :checked="column.getIsVisible()"
                        @update:checked="
                            (value) => {
                                column.toggleVisibility(!!value);
                            }
                        "
                    >
                        {{ column.id }}
                    </DropdownMenuCheckboxItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            :data-pinned="header.column.getIsPinned()"
                            :class="
                                cn(
                                    {
                                        'sticky bg-background/95':
                                            header.column.getIsPinned(),
                                    },
                                    header.column.getIsPinned() === 'left'
                                        ? 'left-0'
                                        : 'right-0',
                                )
                            "
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <template
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                        >
                            <TableRow
                                :data-state="row.getIsSelected() && 'selected'"
                            >
                                <TableCell
                                    v-for="cell in row.getVisibleCells()"
                                    :key="cell.id"
                                    :data-pinned="cell.column.getIsPinned()"
                                    :class="
                                        cn(
                                            {
                                                'sticky bg-background/95':
                                                    cell.column.getIsPinned(),
                                            },
                                            cell.column.getIsPinned() === 'left'
                                                ? 'left-0'
                                                : 'right-0',
                                        )
                                    "
                                >
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="row.getIsExpanded()">
                                <TableCell :colspan="row.getAllCells().length">
                                    {{ row.original }}
                                </TableCell>
                            </TableRow>
                        </template>
                    </template>

                    <TableRow v-else>
                        <TableCell
                            :colspan="columns.length"
                            class="h-24 text-center"
                        >
                            No results.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
            <div class="flex-1 text-sm text-muted-foreground">
                {{ table.getFilteredRowModel().rows.length }} results
            </div>
            <div
                class="flex w-[100px] items-center justify-center text-sm font-medium"
            >
                Page {{ table.getState().pagination.pageIndex + 1 }} of
                {{ table.getPageCount() }}
            </div>
            <div class="space-x-2">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!table.getCanPreviousPage()"
                    @click="table.previousPage()"
                >
                    Previous
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!table.getCanNextPage()"
                    @click="table.nextPage()"
                >
                    Next
                </Button>
            </div>
        </div>
    </div>
</template>
