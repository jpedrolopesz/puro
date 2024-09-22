import { h } from "vue";

import { CrossCircledIcon, CheckCircledIcon } from "@radix-icons/vue";
import {
  XIcon,
  CheckIcon,
  Info,
  CornerDownLeft,
  Shield,
  User,
  Star,
} from "lucide-vue-next";

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

export const userRoles = [
  {
    value: "super_admin",
    label: "Super Admin",
    icon: Shield,
    description: "Full access to all features and settings",
  },
  {
    value: "admin",
    label: "Admin",
    icon: Star,
    description: "Access to most features and settings",
  },
  {
    value: "user",
    label: "User",
    icon: User,
    description: "Standard user access",
  },
] as const;

export const userStatuses = [
  {
    value: "Yes",
    label: "Verified",
    icon: CheckIcon,
    description: "Email has been verified",
    color: "green",
  },
  {
    value: "No",
    label: "Unverified",
    icon: XIcon,
    description: "Email has not been verified",
    color: "red",
  },
] as const;

export type UserRole = (typeof userRoles)[number]["value"];
export type UserStatus = (typeof userStatuses)[number]["value"];

export const getUserRole = (value: UserRole) =>
  userRoles.find((role) => role.value === value) || userRoles[2]; // Default to 'user' if not found

export const getUserStatus = (value: UserStatus) =>
  userStatuses.find((status) => status.value === value) || userStatuses[1];
