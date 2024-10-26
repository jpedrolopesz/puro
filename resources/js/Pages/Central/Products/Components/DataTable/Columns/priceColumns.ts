import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { GitCompareArrows } from "lucide-vue-next";
import { Badge } from "@/Components/ui/badge";
import DataTableColumnHeader from "../DataTableColumnHeader.vue";
import DataTableRowActionsPrice from "../DataTableRowActionsPrice.vue";

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

function formatDate(timestamp: number) {
  return new Date(timestamp * 1000).toLocaleDateString();
}

function formatPrice(amount: number, currency: string) {
  return h("div", { class: "flex flex-col" }, [
    h("div", { class: "flex items-end font-semibold space-x-1.5" }, [
      h("span", { class: "uppercase" }, currency),
      h("span", {}, (amount / 100).toFixed(2)),
    ]),
  ]);
}

export const columns: ColumnDef<Price>[] = [
  {
    accessorKey: "unit_amount",
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: "Price",
      }),
    cell: ({ row }) => {
      const price = row.original;
      return h("div", { class: "flex flex-col" }, [
        formatPrice(price.unit_amount, price.currency),
        h("div", { class: "flex items-center space-x-1 font-normal text-xs" }, [
          h(GitCompareArrows, { class: "w-3 h-3" }),
          h("span", {}, price.recurring.interval),
        ]),
      ]);
    },
  },
  {
    accessorKey: "id",
    header: "",
    cell: ({ row }) => {
      const price = row.original;
      return h("div", { class: "flex space-x-2" }, [
        price.active === false &&
          h(Badge, { variant: "outline" }, () => "Archived"),
      ]);
    },
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "nickname",
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: "Description",
      }),
    cell: ({ row }) => {
      const price = row.original;
      return h("div", { class: "w-[200px]" }, price.nickname || "_");
    },
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("nickname"));
    },
  },
  {
    accessorKey: "subscription_count",
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: "Subscriptions",
      }),
    cell: ({ row }) => {
      const price = row.original;
      return h("div", { class: "flex items-center space-x-2" }, [
        h("span", {}, price.subscription_count),
        h("span", { class: "text-muted-foreground" }, "active"),
      ]);
    },
  },
  {
    accessorKey: "created",
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: "Created",
      }),
    cell: ({ row }) =>
      h("div", { class: "w-[100px]" }, formatDate(row.getValue("created"))),
  },
  {
    id: "actions",
    cell: ({ row }) => h(DataTableRowActionsPrice, { row }),
    enableHiding: false,
  },
];
