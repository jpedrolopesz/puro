import { h } from "vue";
import type { ColumnDef } from "@tanstack/vue-table";
import { paymentStatus } from "../data/data";
import type { Payments } from "../data/schema";
import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import { Badge } from "@/Components/ui/badge";
import { CreditCard } from "lucide-vue-next";

export const columns: ColumnDef<Payments>[] = [
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
      return value.includes(id);
    },
  },

  {
    accessorKey: "payment_method_type",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Payment Method" }),
    cell: ({ row }) => {
      const paymentMethodBrand = row.original.payment_method_brand;
      const paymentMethodLast4 = row.original.payment_method_last4;

      return h("div", { class: "w-110" }, [
        h("div", { class: "flex items-center" }, [
          h(CreditCard, { class: "h-3.5 w-3.5 mr-2" }),
          h("dl", { class: "flex items-center gap-2 text-muted-foreground" }, [
            h("dt", { class: "font-medium capitalize" }, paymentMethodBrand),
            h("dd", `**** ${paymentMethodLast4}`),
          ]),
        ]),
      ]);
    },
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("payment_method_type"));
    },
  },

  {
    accessorKey: "description",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Description" }),
    cell: ({ row }) => h("div", { class: "w-50" }, row.getValue("description")),
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("description"));
    },
  },

  {
    accessorKey: "customer_email",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Customer" }),
    cell: ({ row }) =>
      h("div", { class: "w-110" }, row.getValue("customer_email")),
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("customer_email"));
    },
  },

  {
    accessorKey: "payment_date",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Data" }),
    cell: ({ row }) => {
      const date = new Date(row.getValue("payment_date"));
      return h("div", { class: "w-110" }, date.toLocaleDateString());
    },
    sortingFn: (rowA, rowB, columnId) => {
      const dateA = new Date(rowA.getValue(columnId));
      const dateB = new Date(rowB.getValue(columnId));
      return dateA.getTime() - dateB.getTime(); // Ordem decrescente (mais recente primeiro)
    },
    filterFn: (row, id, value) => {
      return value.includes(row.getValue("payment_date"));
    },
  },

  {
    id: "actions",
    cell: ({ row }) => h(DataTableRowActions, { row }),
  },
];
