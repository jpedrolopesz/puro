import { z } from "zod";

export const orderSchema = z.object({
  id: z.number(),
  Customer: z.object({
    Name: z.string(),
    Email: z.string().email(),
  }),
  Type: z.string(),
  Status: z.string(),
  Date: z.string(),
  Amount: z.string(),
});

export type Order = z.infer<typeof orderSchema>;
