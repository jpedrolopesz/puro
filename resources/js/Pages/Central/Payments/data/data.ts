import { h } from "vue";

import {
  CrossCircledIcon,
  CheckIcon,
  ArrowUpIcon,
  ArrowRightIcon,
  ArrowDownIcon,
} from "@radix-icons/vue";

export const subscriptionLevels = [
  {
    value: "basic",
    subscriptionLevel: "Basic",
  },
  {
    value: "standard",
    subscriptionLevel: "Standard",
  },
  {
    value: "platinum",
    subscriptionLevel: "Platinum",
  },
];

export const subscriptionStatuses = [
  {
    value: "succeeded",
    status: "OK",
    icon: h(CheckIcon),
  },
  {
    value: "canceled",
    status: "NOTOK",
    icon: h(CrossCircledIcon),
  },
];

export const currencys = [
  {
    value: "brl",
    currency: "BRL",
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
