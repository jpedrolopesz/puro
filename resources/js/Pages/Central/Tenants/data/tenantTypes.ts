export interface TenantDetails {
  tenancy_name: string;
  email: string;
  status: string;
  creator: {
    id: number;
    stripe_id: string;
    name: string;
    email: string;
  };
  domain: {
    domain: string;
  };
  tenancy_db_name: string;
  updated_at: string;
}

export interface Payment {
  id: number;
  amount: number;
  description: string;
  payment_date: string;
}

export interface CustomerPayments {
  payments: Payment[];
  totals: {
    total_amount: number;
    payment_count: number;
  };
  current_subscription?: {
    plan: {
      name: string;
      interval: string;
      amount: number;
      currency: string;
    };
    current_period_end: string;
  };
}
