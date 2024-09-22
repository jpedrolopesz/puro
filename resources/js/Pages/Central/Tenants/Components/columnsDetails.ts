import { h } from "vue";
import { Link } from "@inertiajs/vue3";
import type { ColumnDef } from "@tanstack/vue-table";

import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";

import { Badge } from "@/Components/ui/badge";

import { statuses, paymentStatus } from "../data/data";

import type { Payment } from "../data/schema";

export const columns: ColumnDef<Payment>[] = [
  {
    accessorKey: "id",
    header: () => null,
    cell: () => null,
    enableHiding: false,
    enableSorting: false,
  },
  {
    accessorKey: "customer_id",
    header: () => null,
    cell: () => null,
    enableHiding: false,
    enableSorting: false,
  },
  {
    accessorKey: "amount",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Amount" }),
    cell: ({ row }) => {
      const payment = row.original;
      const amount = payment.amount;
      if (!amount) return null;

      const status = paymentStatus(payment);
      if (!status) return null;

      return h("div", { class: "flex w-[150px] space-x-2 items-center" }, [
        h("span", { class: "max-w-[50px] font-medium" }, amount),
        h("span", { class: "font-medium uppercase" }, payment.currency),
        h(
          Badge,
          {
            variant: status.style,
            class: `flex items-center space-x-1 ${status.style}`,
          },
          () => [
            h("span", { class: "font-medium" }, status.label),
            status.icon &&
              h(status.icon, {
                class: `h-4 w-4 ml-1 ${status.iconStyle || "text-current"}`,
              }),
          ],
        ),
      ]);
    },
    filterFn: (row, id, value) => {
      return value.includes(id || row.getValue("status"));
    },
  },
  {
    accessorKey: "description",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Description" }),
    cell: ({ row }) =>
      h("div", { class: "w-[200px]" }, row.getValue("description")),
  },
  {
    accessorKey: "payment_date",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Payment Date" }),
    cell: ({ row }) => {
      const date = new Date(row.getValue("payment_date"));
      return h("div", { class: "w-[100px]" }, date.toLocaleDateString());
    },
  },
  {
    id: "status",
    accessorFn: (row) => paymentStatus(row)?.value,
    header: () => null,
    cell: () => null,
    enableHiding: false,
    enableSorting: false,
    filterFn: (row, id, value) => {
      const status = paymentStatus(row.original);
      return value.includes(status?.value);
    },
  },

  {
    id: "actions",
    cell: ({ row }) =>
      h(
        DataTableRowActions,
        { row },
        {
          default: () =>
            h(
              Link,
              {
                href: route("payments.details", {
                  paymentsId: row.original.stripe_payment_id,
                }),
                class: "block w-full",
              },
              () => "View payment details",
            ),
        },
      ),
  },
];
