import { z } from "zod";

export const productSchema = z.object({
  id: z.number(),
  customer: z.object({
    Name: z.string(),
    Email: z.string().email(),
  }),
  type: z.string(),
  status: z.string(),
  date: z.string(),
  amount: z.string(),
});

export type Product = z.infer<typeof productSchema>;
