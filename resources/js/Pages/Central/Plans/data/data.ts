import { h } from "vue";

import {
  StopwatchIcon,
  QuestionMarkCircledIcon,
  CrossCircledIcon,
  CircleIcon,
  CheckCircledIcon,
  ArrowUpIcon,
  ArrowRightIcon,
  ArrowDownIcon,
} from "@radix-icons/vue";

export const intervals = [
  {
    value: "month",
    interval: "Monthly",
  },
  {
    value: "year",
    interval: "Yearly",
  },
];

export const currencys = [
  {
    value: "USD",
    currency: "USD",
  },
];

export const subscriptionStatuses = [
  {
    value: "pending",
    paymentStatus: "Pending",
    icon: h(QuestionMarkCircledIcon),
  },
  {
    value: "paid",
    paymentStatus: "Paid",
    icon: h(QuestionMarkCircledIcon),
  },

  {
    value: "late",
    paymentStatus: "Late",
    icon: h(CheckCircledIcon),
  },
  {
    value: "canceled",
    paymentStatus: "Canceled",
    icon: h(CrossCircledIcon),
  },
];

export const priorities = [
  {
    label: "Low",
    value: "low",
    icon: h(ArrowDownIcon),
  },
  {
    label: "Medium",
    value: "medium",
    icon: h(ArrowRightIcon),
  },
  {
    label: "High",
    value: "high",
    icon: h(ArrowUpIcon),
  },
];
