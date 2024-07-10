import { h } from "vue";
import { Link } from "@inertiajs/vue3";
import type { ColumnDef } from "@tanstack/vue-table";
import {
  subscriptionLevels,
  subscriptionStatuses,
  priorities,
} from "../data/data";
import type { Billing } from "../data/schema";
import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import { Checkbox } from "@/Components/ui/checkbox";

export const columns: ColumnDef<Billing>[] = [
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
      h(DataTableColumnHeader, { column, title: "Billing Id" }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `/billing/${row.getValue("id")}`,
          class: "w-24 truncate",
        },
        () => row.getValue("id"), // Transformado em uma função
      ),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "customer_name",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Customer" }),
    cell: ({ row }) =>
      h("div", { class: "w-20" }, row.getValue("customer_name")),
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("customer_name"));
    },
  },
  {
    accessorKey: "amount",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Amount" }),
    cell: ({ row }) => h("div", { class: "w-20" }, row.getValue("amount")),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "description",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Description" }),
    cell: ({ row }) =>
      h("div", { class: "w-24 truncate" }, row.getValue("description")),
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("description"));
    },
  },
  {
    accessorKey: "status",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Status" }),

    cell: ({ row }) => {
      const status = subscriptionStatuses.find(
        (status) => status.value === row.getValue("status"),
      );

      if (!status) return null;

      return h("div", { class: "flex w-[100px] items-center" }, [
        status.icon &&
          h(status.icon, { class: "mr-2 h-4 w-4 text-muted-foreground" }),
        h("span", status.status),
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
