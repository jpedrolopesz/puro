import { z } from "zod";

export const taskSchema = z.object({
  id: z.number(),
  name: z.string(),
  paymentStatus: z.string(),
  email: z.string().email(),
  tenancy_db_name: z.string(),
});
export type Task = z.infer<typeof taskSchema>;
