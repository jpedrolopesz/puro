export const accounts = [
  {
    label: "Alicia Koch",
    email: "alicia@example.com",
    icon: "ion:logo-vercel",
  },
  {
    label: "Alicia Koch",
    email: "alicia@gmail.com",
    icon: "mdi:google",
  },
  {
    label: "Alicia Koch",
    email: "alicia@me.com",
    icon: "bx:bxl-gmail",
  },
];

export type Account = (typeof accounts)[number];
