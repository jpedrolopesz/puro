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
