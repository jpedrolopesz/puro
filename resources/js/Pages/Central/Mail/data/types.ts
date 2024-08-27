interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  tenant_id?: number | null;
  created_at: string;
  updated_at: string;
}

interface Admin {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  tenant_id?: number | null;
  created_at: string;
  updated_at: string;
}

interface Tenant {
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

interface Participant {
  id: number;
  name: string;
  email: string;
  type: "admin" | "user";
}

interface Conversation {
  id: string;
  admin_id: number;
  user_id: number;
  labels: Record<string, any>; // labels como objeto
  read: boolean;
  subject?: string; // Tornar opcional se necessário
  participant: Participant;
  created_at: string;
  updated_at: string;
  messages: Message[];
  admin: Admin;
  user: User;
}

interface Message {
  id: string;
  conversation_id: string;
  sender_id: number;
  sender_type: "admin" | "user";
  content: string;
  read: boolean;
  date: string; // Adicionar o campo 'date'
  data?: any;
}

export interface ChatProps {
  conversations: Conversation[];
  tenantsWithUsers: Tenant[];
  defaultLayout?: number[];
  defaultCollapsed?: boolean;
  navCollapsedSize: number;
}
