import { z } from "zod";

export const paymentsSchema = z.object({
  stripe_payment_id: z.number(),
  customer: z.object({
    Name: z.string(),
    Email: z.string().email(),
  }),
  type: z.string(),
  status: z.string(),
  date: z.string(),
  amount: z.string(),
  application_fee_amount: z.array(z.unknown()),
  capture_method: z.number(),
  card_brand: z.string(),
  card_exp_month: z.number(),
  card_exp_year: z.number(),
  card_last4: z.string(),
  created: z.number(),
  currency: z.string(),
  customer_address: z.null(),
  customer_email: z.string().email(),
  customer_name: z.string(),
  customer_phone: z.null(),
  description: z.string(),
  payment_method: z.string(),
  receipt_email: z.null(),
  status: z.string(),
});

export type Payments = z.infer<typeof paymentsSchema>;
