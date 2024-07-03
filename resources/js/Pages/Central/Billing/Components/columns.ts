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
      h(DataTableColumnHeader, { column, title: "Order" }),
    cell: ({ row }) => h("div", { class: "w-20" }, row.getValue("id")),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "customer",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Customer" }),

    cell: ({ row }) => {
      const subscriptionLevel = subscriptionLevels.find(
        (subscriptionLevel) =>
          subscriptionLevel.value === row.original.subscriptionLevel,
      );

      return h("div", { class: "flex space-x-2" }, [
        subscriptionLevel
          ? h(
              Badge,
              { variant: "outline" },
              () => subscriptionLevel.subscriptionLevel,
            )
          : null,
        h(
          "span",
          { class: "max-w-[500px] truncate font-medium" },
          row.getValue("customer"),
        ),
      ]);
    },
  },

  {
    accessorKey: "amount",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "amount" }),
    cell: ({ row }) => h("div", { class: "w-20" }, row.getValue("amount")),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "Description",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Description" }),
    cell: ({ row }) => h("div", { class: "w-20" }, row.getValue("description")),
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("description"));
    },
    enableSorting: false,
    enableHiding: false,
  },

  {
    accessorKey: "paymentStatus",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Status" }),

    cell: ({ row }) => {
      const status = subscriptionStatuses.find(
        (status) => status.value === row.getValue("paymentStatus"),
      );

      if (!status) return null;

      return h("div", { class: "flex w-[100px] items-center" }, [
        status.icon &&
          h(status.icon, { class: "mr-2 h-4 w-4 text-muted-foreground" }),
        h("span", status.paymentStatus),
      ]);
    },
    filterFn: (row, id, value) => {
      return value.includes(row.getValue(id));
    },
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
