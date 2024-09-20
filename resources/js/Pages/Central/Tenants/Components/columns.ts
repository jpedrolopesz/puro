import { h } from "vue";
import { Link } from "@inertiajs/vue3";
import type { ColumnDef } from "@tanstack/vue-table";

import { subscriptionLevels, priorities, statuses } from "../data/data";
import type { Tenant } from "../data/schema";
import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import { Badge } from "@/Components/ui/badge";

export const columns: ColumnDef<Tenant>[] = [
  {
    accessorKey: "id",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Id" }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `/tenants/${row.getValue("id")}`,
          class: "w-24 ml-4",
        },
        () => row.getValue("id"),
      ),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "tenancy_name",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Tenant Name" }),

    cell: ({ row }) => {
      const subscriptionLevel = subscriptionLevels.find(
        (subscriptionLevel) =>
          subscriptionLevel.value === row.original.subscriptionLevel,
      );

      return h("div", { class: "ml-4 " }, [
        subscriptionLevel
          ? h(
              Badge,
              { variant: "outline " },
              () => subscriptionLevel.subscriptionLevel,
            )
          : null,
        h(
          "span",
          { class: "max-w-[500px] truncate font-medium" },
          row.getValue("tenancy_name"),
        ),
      ]);
    },
  },
  {
    accessorKey: "domain",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Domain" }),
    cell: ({ row }) => {
      const creatorName = row.original.domain.domain;
      return h("div", { class: "w-20" }, creatorName);
    },
    filterFn: (row, id, value) => {
      const creatorName = row.original.domain.domain;
      return creatorName.includes(value);
    },
  },
  {
    accessorKey: "creator",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Customer" }),
    cell: ({ row }) => {
      const creatorName = row.original.creator.name;
      return h("div", { class: "w-20" }, creatorName);
    },
    filterFn: (row, id, value) => {
      const creatorName = row.original.creator.name;
      return creatorName.includes(value);
    },
  },

  {
    accessorKey: "status",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Status" }),

    cell: ({ row }) => {
      const status = statuses.find(
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
      return value.includes(row.getValue("status"));
    },
  },

  {
    id: "actions",
    cell: ({ row }) => h(DataTableRowActions, { row }),
  },
];
