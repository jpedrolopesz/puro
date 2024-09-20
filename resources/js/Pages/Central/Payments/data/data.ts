import { h } from "vue";

import { XIcon, CheckIcon, Info, CornerDownLeft } from "lucide-vue-next";
import type { Payments } from "../data/schema";

import {
  CrossCircledIcon,
  ArrowUpIcon,
  ArrowRightIcon,
  ArrowDownIcon,
} from "@radix-icons/vue";

export interface Payment {
  disputed: boolean | number;
  refunded: boolean | number;
  status: string;
}

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

export const paymentStatus = (payment: Payment) => {
  if (payment.disputed === true || payment.disputed === 1) {
    return {
      label: "Disputed",
      style: "bg-orange-200 text-orange-950 font-bold",
      icon: Info,
      iconStyle: "text-orange-950",
    };
  } else if (payment.refunded === true || payment.refunded === 1) {
    return {
      label: "Refunded",
      style: "bg-gray-200 text-gray-950 font-bold",
      icon: CornerDownLeft,
      iconStyle: "text-gray-950",
    };
  } else if (payment.status === "succeeded") {
    return {
      label: "Succeeded",
      style: "bg-lime-200 text-green-950 font-bold",
      icon: CheckIcon,
      iconStyle: "text-green-950",
    };
  } else if (payment.status === "failed") {
    return {
      label: "Failed",
      style: "bg-red-200 text-red-950 font-bold",
      icon: XIcon,
      iconStyle: "text-red-950",
    };
  }
  return {
    label: "Unknown",
    style: "bg-gray-400 text-black font-bold",
    icon: null,
    iconStyle: "",
  };
};

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
