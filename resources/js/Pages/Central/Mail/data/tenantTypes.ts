export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  tenant_id?: number | null;
  created_at: string;
  updated_at: string;
}

export interface Tenant {
  id: number;
  name: string;
  email: string;
  creator_id: number;
  status: string;
  tenancy_name: string;
  tenancy_db_name: string;
  tenancy_about: string;
  created_at: string;
  updated_at: string;
  users: User[];
  creator: User;
  data?: any;
}

export interface TenantsWithUsersProps {
  tenantsWithUsers: Tenant[];
}
