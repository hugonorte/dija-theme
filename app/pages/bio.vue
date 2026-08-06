<script setup lang="ts">
definePageMeta({
  layout: 'homepage'
})

useSeoMeta({
  title: 'Sobre a Professora - DIJA',
  description: 'Conheça Dijaina Ferreira, professora especialista em Forró com mais de 15 anos de dedicação ao ensino da dança de salão.',
})

const personalInfo = [
  { label: 'Especialidade:', value: 'Forró e Dança de Salão' },
  { label: 'Experiência:', value: '15+ Anos' },
  { label: 'Foco:', value: 'Conexão e Musicalidade' },
  { label: 'Disponibilidade:', value: 'Aulas, Workshops e Eventos' },
]

const stats = [
  { value: '15+', label: 'Anos de Ensino', icon: 'i-lucide-calendar' },
  { value: '10k+', label: 'Alunos Formados', icon: 'i-lucide-users' },
  { value: '50+', label: 'Eventos Realizados', icon: 'i-lucide-map-pin' },
  { value: '100%', label: 'Dedicação', icon: 'i-lucide-heart' }
]

const services = [
  { title: 'Aulas Particulares', desc: 'Atenção 100% focada no seu desenvolvimento corporal.', icon: 'i-lucide-user' },
  { title: 'Turmas Regulares', desc: 'Aprenda e conecte-se com a comunidade local na dança.', icon: 'i-lucide-users' },
  { title: 'Workshops', desc: 'Imersões intensivas de final de semana pelo Brasil inteiro.', icon: 'i-lucide-briefcase' },
]
</script>

<template>
  <div class="bio-page">
    <!-- Hero Section (Inspirado no Mockup Orix) -->
    <section class="bio-hero">
      <div class="bio-hero-bg"></div>
      <UContainer class="bio-hero-container">
        
        <div class="hero-content flex-column">
          <span class="hero-kicker">A PROFESSORA</span>
          <h1 class="hero-title">Dijaina Ferreira</h1>
          <p class="hero-subtitle">
            Especialista em Forró, Desenvolvedora de Metodologias e Mentora de Dança
          </p>
          <div class="hero-actions">
            <UButton label="Agendar Aula" size="xl" color="primary" to="/contato" />
            <UButton label="Ver Eventos" size="xl" variant="ghost" color="neutral" to="/eventos" />
          </div>
        </div>

        <div class="hero-visual">
          <div class="image-wrapper">
            <img src="/hero.png" alt="Dijaina Ferreira" class="profile-img" />
            
            <!-- Elementos Flutuantes da Imagem -->
            <div class="floating-badge">
              <strong>10K+</strong>
              <span>Alunos Transformados</span>
            </div>
            
            <div class="decorative-circle"></div>
            <div class="decorative-triangle"></div>
          </div>
        </div>

      </UContainer>
    </section>

    <!-- Seção Sobre Mim -->
    <section class="about-section">
      <UContainer class="about-container">
        <div class="about-content">
          <h2 class="section-title">Sobre Mim</h2>
          <p class="about-desc">
            Sou professora de dança de salão especialista em Forró, com mais de 15 anos de dedicação ao ensino. Minha missão é ajudar os alunos a desenvolverem consciência corporal, musicalidade e uma conexão genuína na dança, transformando cada passo em um diálogo sem palavras.
          </p>
          
          <div class="social-links">
            <UButton icon="i-simple-icons-instagram" color="primary" variant="soft" to="https://instagram.com/dijaina" target="_blank" />
            <UButton icon="i-simple-icons-youtube" color="primary" variant="soft" to="https://youtube.com" target="_blank" />
          </div>

          <div class="info-list">
            <div v-for="info in personalInfo" :key="info.label" class="info-item">
              <span class="info-label">{{ info.label }}</span>
              <span class="info-value">{{ info.value }}</span>
            </div>
          </div>
        </div>

        <!-- Cards de Estatísticas com Borda Roxa (Como no Mockup) -->
        <div class="about-stats">
          <div v-for="stat in stats" :key="stat.label" class="stat-card">
            <UIcon :name="stat.icon" class="stat-icon text-primary" />
            <div class="stat-info">
              <h3>{{ stat.value }}</h3>
              <span>{{ stat.label }}</span>
            </div>
          </div>
        </div>
      </UContainer>
    </section>

    <!-- Seção Meus Serviços -->
    <section class="services-section">
      <UContainer>
        <div class="section-header flex-column flex-center">
          <span class="section-subtitle">O QUE EU FAÇO</span>
          <h2 class="section-title">Meus Serviços</h2>
        </div>
        
        <div class="services-flex-row">
          <div v-for="service in services" :key="service.title" class="service-card card-premium">
            <div class="service-icon-wrap">
              <UIcon :name="service.icon" class="service-icon" />
            </div>
            <h3>{{ service.title }}</h3>
            <p>{{ service.desc }}</p>
          </div>
        </div>
      </UContainer>
    </section>
  </div>
</template>

<style scoped lang="scss">
.bio-page {
  /* Compensa o menu fixo */
  padding-top: 0rem;
}

/* --- Hero Section --- */
.bio-hero {
  position: relative;
  min-height: 50vh;
  display: flex;
  align-items: center;
  overflow: hidden;
  padding: 0 0 8rem;
}

.bio-hero-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, var(--color-background) 0%, var(--color-primary-dark) 100%);
  z-index: -1;
  /* Base Curvada semelhante ao mockup */
  clip-path: ellipse(150% 100% at 50% 0%);
}

html.dark .bio-hero-bg {
  background: linear-gradient(135deg, #1a0512 0%, var(--color-primary-dark) 100%);
}

.bio-hero-container {
  display: flex;
  align-items: center;
  gap: 4rem;
  
  @media (max-width: 1024px) {
    flex-direction: column;
    text-align: center;
  }
}

.hero-content {
  flex: 1;
  gap: 1.5rem;
}

.hero-kicker {
  font-family: var(--font-primary);
  font-weight: 900;
  font-size: 1.5rem;
  color: transparent;
  -webkit-text-stroke: 1px rgba(255, 255, 255, 0.4);
  letter-spacing: 0.1em;
}

.hero-title {
  font-size: clamp(3rem, 6vw, 4.5rem);
  color: #ffffff;
  line-height: 1.1;
  font-weight: 900;
  margin: 0;
}

.hero-subtitle {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.2rem;
  max-width: 30rem;
  margin: 0;
}

.hero-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
  
  @media (max-width: 1024px) {
    justify-content: center;
  }
}

.hero-visual {
  flex: 1;
  display: flex;
  justify-content: center;
  position: relative;
}

.image-wrapper {
  position: relative;
  width: 24rem;
  height: 24rem;
  border-radius: 50%;
  background: var(--color-primary);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  border: 10px solid var(--glass-bg);
  box-shadow: var(--shadow-premium);
  
  @media (max-width: 768px) {
    width: 18rem;
    height: 18rem;
  }
}

.profile-img {
  width: 90%;
  height: 90%;
  object-fit: cover;
  border-radius: 50%;
}

.floating-badge {
  position: absolute;
  bottom: 2rem;
  left: -3rem;
  background: #ffffff;
  padding: 1rem 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 15px 30px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  color: #333333;
  
  strong {
    font-size: 1.8rem;
    font-weight: 900;
    color: var(--color-primary);
    line-height: 1;
  }
  
  span {
    font-size: 0.85rem;
    font-weight: 700;
    margin-top: 0.2rem;
  }
  
  @media (max-width: 768px) {
    left: -1rem;
  }
}

html.dark .floating-badge {
  background: var(--glass-bg);
  color: var(--color-text-primary);
}

.decorative-circle {
  position: absolute;
  top: 1rem;
  left: -1rem;
  width: 3rem;
  height: 3rem;
  border: 6px solid #FFD700;
  border-radius: 50%;
}

.decorative-triangle {
  position: absolute;
  top: 4rem;
  right: 1rem;
  width: 0;
  height: 0;
  border-left: 15px solid transparent;
  border-right: 15px solid transparent;
  border-bottom: 25px solid var(--glass-bg);
  transform: rotate(45deg);
}


/* --- About Section --- */
.about-section {
  padding: 6rem 0;
}

.about-container {
  display: flex;
  gap: 4rem;
  align-items: center;
  
  @media (max-width: 1024px) {
    flex-direction: column;
  }
}

.about-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--color-text-primary);
  margin: 0;
}

.section-subtitle {
  font-size: 0.9rem;
  font-weight: 800;
  letter-spacing: 0.1em;
  color: var(--color-primary);
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}

.about-desc {
  font-size: 1.1rem;
  line-height: 1.8;
  color: var(--color-text-muted);
  margin: 0;
}

.social-links {
  display: flex;
  gap: 1rem;
}

.info-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 1rem;
  background: var(--glass-bg);
  padding: 1.5rem;
  border-radius: 1rem;
  border: 1px solid var(--glass-border);
}

.info-item {
  display: flex;
  gap: 1rem;
  border-bottom: 1px solid var(--glass-border);
  padding-bottom: 0.5rem;
  
  &:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
}

.info-label {
  font-weight: 700;
  width: 140px;
  color: var(--color-text-primary);
}

.info-value {
  color: var(--color-text-muted);
}

.about-stats {
  flex: 1;
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  justify-content: center;
}

.stat-card {
  flex: 1 1 40%;
  min-width: 200px;
  padding: 2rem;
  background: var(--glass-bg);
  border: 2px solid var(--color-primary);
  border-radius: var(--radius-premium);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  gap: 1rem;
  transition: var(--transition-smooth);
  
  &:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-premium);
  }
}

.stat-icon {
  font-size: 2.5rem;
}

.stat-info h3 {
  font-size: 2.2rem;
  font-weight: 900;
  margin: 0 0 0.25rem 0;
  color: var(--color-text-primary);
}

.stat-info span {
  font-size: 0.95rem;
  color: var(--color-text-muted);
}


/* --- Services Section --- */
.services-section {
  padding: 4rem 0 8rem;
}

.section-header {
  margin-bottom: 4rem;
  text-align: center;
}

.services-flex-row {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.service-card {
  flex: 1 1 300px;
  align-items: center;
  text-align: center;
  
  h3 {
    margin: 1.5rem 0 0.5rem;
    font-size: 1.3rem;
  }
}

.service-icon-wrap {
  width: 4.5rem;
  height: 4.5rem;
  border-radius: 1rem;
  background: var(--color-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 10px 20px rgba(255, 56, 157, 0.3);
}

.service-icon {
  color: #ffffff;
  font-size: 2rem;
}
</style>
