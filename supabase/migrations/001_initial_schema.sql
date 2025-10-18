-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Users table (extends Supabase auth.users)
CREATE TABLE public.profiles (
  id UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
  email TEXT UNIQUE NOT NULL,
  full_name TEXT,
  phone TEXT,
  address TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Veterinarians table
CREATE TABLE public.veterinarians (
  id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
  name TEXT NOT NULL,
  specialty TEXT NOT NULL,
  experience TEXT,
  rating DECIMAL(2,1) DEFAULT 0,
  reviews_count INTEGER DEFAULT 0,
  image_url TEXT,
  availability_status TEXT DEFAULT 'offline' CHECK (availability_status IN ('online', 'offline', 'busy')),
  bio TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Veterinarian badges/services
CREATE TABLE public.veterinarian_badges (
  id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
  veterinarian_id UUID REFERENCES public.veterinarians(id) ON DELETE CASCADE,
  badge_name TEXT NOT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Clinics table
CREATE TABLE public.clinics (
  id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
  name TEXT NOT NULL,
  address TEXT NOT NULL,
  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),
  rating DECIMAL(2,1) DEFAULT 0,
  open_hours TEXT,
  phone TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Services table
CREATE TABLE public.services (
  id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
  title TEXT NOT NULL,
  description TEXT,
  price_start INTEGER, -- in rupiah
  category TEXT DEFAULT 'general' CHECK (category IN ('general', 'vaccination', 'surgery', 'emergency', 'nutrition')),
  is_home_visit BOOLEAN DEFAULT false,
  is_24h BOOLEAN DEFAULT false,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Articles table
CREATE TABLE public.articles (
  id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
  title TEXT NOT NULL,
  content TEXT NOT NULL,
  author_name TEXT NOT NULL,
  author_id UUID REFERENCES public.veterinarians(id) ON DELETE SET NULL,
  published_date DATE DEFAULT CURRENT_DATE,
  rating DECIMAL(2,1) DEFAULT 0,
  views_count INTEGER DEFAULT 0,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Consultations table
CREATE TABLE public.consultations (
  id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
  user_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE,
  veterinarian_id UUID REFERENCES public.veterinarians(id) ON DELETE CASCADE,
  consultation_type TEXT DEFAULT 'chat' CHECK (consultation_type IN ('chat', 'video', 'phone')),
  status TEXT DEFAULT 'pending' CHECK (status IN ('pending', 'active', 'completed', 'cancelled')),
  scheduled_at TIMESTAMP WITH TIME ZONE,
  started_at TIMESTAMP WITH TIME ZONE,
  ended_at TIMESTAMP WITH TIME ZONE,
  notes TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Consultation messages
CREATE TABLE public.consultation_messages (
  id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
  consultation_id UUID REFERENCES public.consultations(id) ON DELETE CASCADE,
  sender_id UUID NOT NULL, -- can be user or vet
  sender_type TEXT CHECK (sender_type IN ('user', 'veterinarian')),
  message TEXT NOT NULL,
  message_type TEXT DEFAULT 'text' CHECK (message_type IN ('text', 'image', 'file')),
  sent_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Enable Row Level Security (RLS)
ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.veterinarians ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.veterinarian_badges ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.clinics ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.services ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.articles ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.consultations ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.consultation_messages ENABLE ROW LEVEL SECURITY;

-- RLS Policies
-- Profiles: Users can read/update their own profile
CREATE POLICY "Users can view own profile" ON public.profiles FOR SELECT USING (auth.uid() = id);
CREATE POLICY "Users can update own profile" ON public.profiles FOR UPDATE USING (auth.uid() = id);

-- Veterinarians: Public read, only authenticated vets can update
CREATE POLICY "Public can view veterinarians" ON public.veterinarians FOR SELECT USING (true);
-- Add policy for vets to update their own data if needed

-- Similar policies for other tables
CREATE POLICY "Public can view clinics" ON public.clinics FOR SELECT USING (true);
CREATE POLICY "Public can view services" ON public.services FOR SELECT USING (true);
CREATE POLICY "Public can view articles" ON public.articles FOR SELECT USING (true);

-- Consultations: Users can view their own, vets can view assigned
CREATE POLICY "Users can view own consultations" ON public.consultations FOR SELECT USING (auth.uid() = user_id);
CREATE POLICY "Vets can view assigned consultations" ON public.consultations FOR SELECT USING (
  veterinarian_id IN (
    SELECT id FROM public.veterinarians WHERE id = auth.uid() -- assuming vets are also users
  )
);

-- Messages: Participants can view messages
CREATE POLICY "Consultation participants can view messages" ON public.consultation_messages FOR SELECT USING (
  consultation_id IN (
    SELECT id FROM public.consultations WHERE user_id = auth.uid() OR veterinarian_id = auth.uid()
  )
);

-- Indexes for performance
CREATE INDEX idx_veterinarians_availability ON public.veterinarians(availability_status);
CREATE INDEX idx_clinics_location ON public.clinics(latitude, longitude);
CREATE INDEX idx_articles_published ON public.articles(published_date DESC);
CREATE INDEX idx_consultations_user ON public.consultations(user_id);
CREATE INDEX idx_consultations_vet ON public.consultations(veterinarian_id);
CREATE INDEX idx_messages_consultation ON public.consultation_messages(consultation_id);
