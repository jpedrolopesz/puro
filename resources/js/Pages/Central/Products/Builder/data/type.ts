export interface Recurring {
  interval: string;
  interval_count: number;
}

export interface Price {
  id: string;
  currency: string;
  unit_amount: number;
  billing_scheme: string;
  trial_period_days: number | null;
  recurring: Recurring;
}

export interface Product {
  id: string;
  name: string;
  description: string;
  active: boolean;
  created: number;
  updated: number;
  statement_descriptor: string | null;
  prices: Price[];
}
