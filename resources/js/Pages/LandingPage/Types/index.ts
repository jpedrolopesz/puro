export interface NavigationItem {
  text: string;
  link: string;
}

export interface User {
  name: string;
  avatar: string;
}

interface FeatureItem {
  icon: string;
  text: string;
}
export interface Feature {
  title: string;
  description: string;
  icon?: string;
  bgColor?: string;
  link?: string;
  videoUrl?: string;
  codeExamples?: string[];
  items?: FeatureItem[];
}

export interface Card {
  title: string;
  description: string;
  bgColor: string;
}

export interface Section {
  title: string;
  icon: string;
  details: {
    title: string;
    description: string;
  }[];
}
export interface FaqItem {
  question: string;
  answer: string;
  isOpen?: boolean;
}
