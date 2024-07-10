import { z } from "zod";

export const tenantSchema = z.object({
  id: z.number(),
  name: z.string(),
  paymentStatus: z.string(),
  email: z.string().email(),
  tenancy_db_name: z.string(),
});
export type Tenant = z.infer<typeof tenantSchema>;
