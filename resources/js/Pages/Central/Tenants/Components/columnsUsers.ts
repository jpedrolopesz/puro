import { h } from "vue";
import { Link } from "@inertiajs/vue3";
import type { ColumnDef } from "@tanstack/vue-table";
import { userRoles, userStatuses } from "../data/data";
import type { User } from "../data/schema";
import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import { Badge } from "@/Components/ui/badge";

export const columns: ColumnDef<User>[] = [
  {
    accessorKey: "id",
    header: () => null,
    cell: () => null,
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "identifier",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Identifier" }),
    cell: ({ row }) => h("div", { class: "w-20" }, row.getValue("identifier")),
  },
  {
    accessorKey: "name",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Name" }),
    cell: ({ row }) => {
      const userRole = userRoles.find(
        (role) => role.value === row.getValue("role"),
      );
      return h("div", { class: "ml-4 " }, [
        userRole
          ? h(Badge, { variant: "outline " }, () => userRole.label)
          : null,
        h(
          "span",
          { class: "max-w-[500px] truncate font-medium" },
          row.getValue("name"),
        ),
      ]);
    },
  },
  {
    accessorKey: "email",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Email" }),
    cell: ({ row }) => h("div", { class: "w-[200px]" }, row.getValue("email")),
    filterFn: (row, id, value) => {
      return row.getValue("email").toLowerCase().includes(value.toLowerCase());
    },
  },

  {
    accessorKey: "stripe_id",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Customer Id" }),
    cell: ({ row }) =>
      h("div", { class: "w-20" }, row.getValue("stripe_id") || "N/A"),
  },

  {
    id: "actions",
    cell: ({ row }) =>
      h(
        DataTableRowActions,
        { row },
        {
          default: () => h(),
        },
      ),
  },
];
