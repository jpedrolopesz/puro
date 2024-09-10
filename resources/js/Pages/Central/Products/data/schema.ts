import { z } from "zod";

export const productSchema = z.object({
  id: z.number(),
  customer: z.object({
    name: z.string(),
    email: z.string().email(),
  }),
  type: z.string(),
  status: z.string(),
  date: z.string(),
  unit_amount: z.string(),
});

export type Product = z.infer<typeof productSchema>;
