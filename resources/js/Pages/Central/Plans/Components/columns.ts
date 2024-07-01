import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";

import {
  subscriptionLevels,
  subscriptionStatuses,
  priorities,
} from "../data/data";
import type { Order } from "../data/schema";
import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import { Checkbox } from "@/Components/ui/checkbox";
import { Badge } from "@/Components/ui/badge";
import { intervals } from "../data/data";
import { currencys } from "../data/data";

function formatAmount(amount) {
  // Exemplo simples de formatação de moeda, ajuste conforme necessário
  return (amount / 100).toFixed(2); // Assumindo que 'amount' está em centavos, formatado para 2 casas decimais
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
    cell: ({ row }) => h("div", { class: "w-20" }, row.getValue("id")),
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
    accessorKey: "interval",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Interval" }),
    cell: ({ row }) => {
      const interval = row.getValue("interval");
      const formattedInterval =
        interval.charAt(0).toUpperCase() + interval.slice(1);
      return h("div", { class: "w-20" }, formattedInterval);
    },
    filterFn: (row, id, value) => {
      return value
        .toLowerCase()
        .includes(row.getValue("interval").toLowerCase());
    },
  },
  {
    accessorKey: "price",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Price" }),

    cell: ({ row }) => {
      const currency = currencys.find(
        (currency) => currency.value === row.original.currency,
      );

      // Construindo a string formatada diretamente
      const formattedAmount = currency
        ? ` ${formatAmount(row.original.unit_amount)}`
        : "";

      return h("div", { class: "flex space-x-2" }, [
        currency
          ? h(Badge, { variant: "outline" }, () => currency.currency)
          : null,
        h(
          "span",
          { class: "max-w-[500px] truncate font-medium" },
          formattedAmount,
        ),
      ]);
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
