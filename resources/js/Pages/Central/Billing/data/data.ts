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
    status: "Succeeded",
    icon: h(QuestionMarkCircledIcon),
  },
  {
    value: "paid",
    status: "Paid",
    icon: h(QuestionMarkCircledIcon),
  },

  {
    value: "late",
    status: "Late",
    icon: h(CheckCircledIcon),
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
