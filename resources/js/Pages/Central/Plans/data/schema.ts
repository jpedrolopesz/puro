import { z } from "zod";

export const planSchema = z.object({
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

export type Plan = z.infer<typeof planSchema>;
