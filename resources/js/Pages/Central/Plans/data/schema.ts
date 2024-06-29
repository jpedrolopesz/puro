import { z } from "zod";

export const orderSchema = z.object({
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

export type Order = z.infer<typeof orderSchema>;
