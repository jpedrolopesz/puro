type UserRole = "super_admin" | "admin" | "user";

interface User {
  id: number;
  identifier: string;
  name: string;
  email: string;
  email_verified_at: string | null;
  password: string;
  tenant_id: number | null;
  role: UserRole;
  remember_token?: string;
  created_at: string;
  updated_at: string;
}

interface PasswordResetToken {
  email: string;
  token: string;
  created_at: string | null;
}

interface Session {
  id: string;
  user_id: number | null;
  ip_address: string | null;
  user_agent: string | null;
  payload: string;
  last_activity: number;
}

export interface UserSchema {
  users: User[];
  password_reset_tokens: PasswordResetToken[];
  sessions: Session[];
}
