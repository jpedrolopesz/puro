import { z } from "zod";

export const tenantSchema = z.object({
  id: z.number(),
  name: z.string(),
  paymentStatus: z.string(),
  email: z.string().email(),
  tenancy_db_name: z.string(),
  created_at: z.string(),
  updated_at: z.string(),
  tenancy_name: z.string(),
  tenancy_about: z.string(),
  status: z.string(),
  creator_id: z.number(),
  data: z.any().nullable(),
});

export type Tenant = z.infer<typeof tenantSchema>;
