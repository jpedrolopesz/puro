import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";

import {
  subscriptionStatuses,
  priorities,
  intervals,
  currencys,
} from "../data/data";
import type { Order } from "../data/schema";
import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import { Checkbox } from "@/Components/ui/checkbox";
import { Badge } from "@/Components/ui/badge";

function formatAmount(amount) {
  return (amount / 100).toFixed(2);
}

function formatDate(timestamp) {
  const date = new Date(timestamp * 1000); // Convert Unix timestamp to milliseconds
  return date.toLocaleDateString(); // Adjust the format as needed
}

export const columns: ColumnDef<Order>[] = [
  {
    id: "select",
    header: ({ table }) =>
      h(Checkbox, {
        checked:
          table.getIsAllPageRowsSelected() ||
          (table.getIsSomePageRowsSelected() && "indeterminate"),
        "onUpdate:checked": (value) => table.toggleAllPageRowsSelected(!!value),
        ariaLabel: "Select all",
        class: "translate-y-0.5",
      }),
    cell: ({ row }) =>
      h(Checkbox, {
        checked: row.getIsSelected(),
        "onUpdate:checked": (value) => row.toggleSelected(!!value),
        ariaLabel: "Select row",
        class: "translate-y-0.5",
      }),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "id",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Product Id" }),
    cell: ({ row }) => h("div", { class: "w-24 truncate" }, row.getValue("id")),
    enableSorting: false,
    enableHiding: false,
  },

  {
    accessorKey: "name",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Name" }),
    cell: ({ row }) => h("div", { class: "w-20" }, row.getValue("name")),
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("name"));
    },
  },

  {
    accessorKey: "prices",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Prices" }),
    cell: ({ row }) => {
      const prices = row.getValue("prices");
      return h("div", { class: "w-8" }, prices.length.toString());
    },
  },

  {
    accessorKey: "active",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Active" }),
    cell: ({ row }) =>
      h("div", { class: "flex items-center justify-start w-20" }, [
        h("div", {
          class: `w-2 h-2 rounded-full ${row.getValue("active") ? "bg-gray-800/80" : "bg-gray-400/40"}`,
          title: row.getValue("active") ? "Active" : "Inactive",
        }),
        h(
          "span",
          { class: "ml-2" },
          row.getValue("active") ? "Active" : "Inactive",
        ),
      ]),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "created",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Created" }),
    cell: ({ row }) =>
      h("div", { class: "w-20" }, formatDate(row.getValue("created"))),
  },

  {
    accessorKey: "updated",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Updated" }),
    cell: ({ row }) =>
      h("div", { class: "w-20" }, formatDate(row.getValue("updated"))),
  },

  {
    accessorKey: "priority",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Priority" }),
    cell: ({ row }) => {
      const priority = priorities.find(
        (priority) => priority.value === row.getValue("priority"),
      );

      if (!priority) return null;

      return h("div", { class: "flex items-center" }, [
        priority.icon &&
          h(priority.icon, { class: "mr-2 h-4 w-4 text-muted-foreground" }),
        h("span", {}, priority.label),
      ]);
    },
    filterFn: (row, id, value) => {
      return value.includes(row.getValue(id));
    },
  },
  {
    id: "actions",
    cell: ({ row }) => h(DataTableRowActions, { row }),
  },
];
