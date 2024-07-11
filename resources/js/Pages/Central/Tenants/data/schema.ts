import { z } from "zod";

const activityLogSchema = z.object({
  id: z.number(),
  tenancy_id: z.number(),
  activity: z.string(),
  date: z.string(),
  created_at: z.string(),
  updated_at: z.string().optional(),
});

const supportTicketSchema = z.object({
  id: z.number(),
  tenancy_id: z.number(),
  status: z.string(),
  subject: z.string(),
  created_at: z.string(),
  updated_at: z.string(),
});

const resourceUsageSchema = z.object({
  id: z.number(),
  tenancy_id: z.number(),
  storage: z.string(),
  users: z.number(),
  created_at: z.string(),
  updated_at: z.string(),
});

const creatorSchema = z.object({
  id: z.number(),
  name: z.string(),
  email: z.string().email(),
  email_verified_at: z.string().nullable(),
  tenant_id: z.number().nullable(),
  created_at: z.string(),
  updated_at: z.string(),
});

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
  activity_logs: z.array(activityLogSchema),
  support_tickets: z.array(supportTicketSchema),
  resource_usage: resourceUsageSchema,
  creator: creatorSchema,
  creator_id: z.number(),
  data: z.any().nullable(),
});

export type Tenant = z.infer<typeof tenantSchema>;
