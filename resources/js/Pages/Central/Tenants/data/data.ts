import { h } from "vue";

import {
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

export const statuses = [
  {
    value: "active",
    status: "Active",
    icon: h(CheckCircledIcon),
  },
  {
    value: "inactive",
    status: "Inactive",
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
