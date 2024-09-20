import { h } from "vue";

import { CrossCircledIcon, CheckCircledIcon } from "@radix-icons/vue";

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
