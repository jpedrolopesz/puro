import { z } from "zod";

export const productSchema = z.object({
  id: z.number(),
  name: z.string(),
  customer: z.object({
    name: z.string(),
    email: z.string().email(),
  }),
  has_subscription: z.boolean(),
  type: z.string(),
  status: z.string(),
  date: z.string(),
  unit_amount: z.string(),
});

export type Product = z.infer<typeof productSchema>;
