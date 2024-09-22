import { z } from "zod";

const creatorSchema = z.object({
  id: z.number(),
  name: z.string(),
  email: z.string().email(),
  email_verified_at: z.string().nullable(),
  identifier: z.string(),
  role: z.string(),
  pm_last_four: z.string().nullable(),
  pm_type: z.string().nullable(),
  stripe_id: z.string().nullable(),
  tenant_id: z.number().nullable(),
  trial_ends_at: z.string().nullable(),
  created_at: z.string(),
  updated_at: z.string(),
});

const domainSchema = z.object({
  id: z.number(),
  domain: z.string(),
  tenant_id: z.number(),
  created_at: z.string(),
  updated_at: z.string(),
});

export const tenantSchema = z.object({
  id: z.number(),
  name: z.string(),
  email: z.string().email(),
  status: z.string(),
  tenancy_db_name: z.string(),
  tenancy_name: z.string(),
  tenancy_about: z.string(),
  creator_id: z.number(),
  created_at: z.string(),
  updated_at: z.string(),
  data: z.any().nullable(),
  creator: creatorSchema,
  domain: domainSchema,
});

export type Tenant = z.infer<typeof tenantSchema>;

const UserRole = z.enum(["super_admin", "admin", "user"]);

export const UserSchema = z.object({
  id: z.number().int().positive(),
  identifier: z.string().min(1),
  name: z.string().min(1),
  email: z.string().email(),
  email_verified_at: z
    .string()
    .nullable()
    .transform((val) => (val ? new Date(val) : null)),
  password: z.string().min(6), // Assumindo uma senha de pelo menos 6 caracteres
  tenant_id: z.number().int().positive().nullable(),
  role: UserRole,
  remember_token: z.string().optional(),
  created_at: z.string().transform((val) => new Date(val)),
  updated_at: z.string().transform((val) => new Date(val)),
});

// Tipo derivado do schema
export type User = z.infer<typeof UserSchema>;
