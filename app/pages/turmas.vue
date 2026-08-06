<script setup lang="ts">
definePageMeta({
  layout: 'homepage'
})

useSeoMeta({
  title: 'Turmas e Horários - DIJA',
  description: 'Encontre a turma perfeita para você. Aulas de Forró, Ritmos e Dança de Salão para todos os níveis.',
})

const classesList = [
  {
    title: 'Turma de Ritmos',
    schedule: 'Toda Quinta',
    level: 'Todos os níveis',
    image: '/hero.png'
  },
  {
    title: 'Turma de Forró',
    schedule: 'Toda Sexta às 19h',
    level: 'Nível iniciante',
    image: '/hero.png'
  },
  {
    title: 'Turma de Dança de Salão',
    schedule: 'Todo Sábado às 17h',
    level: 'Nível iniciante',
    image: '/hero.png'
  },
  {
    title: 'Turma de Forró',
    schedule: 'Todo Sábado às 18h',
    level: 'Nível iniciante',
    image: '/hero.png'
  }
]
</script>

<template>
  <div class="classes-page">
    <!-- Hero Section Inspirado no Mockup EventPlace -->
    <section class="classes-hero">
      <div class="classes-hero-bg"></div>
      <UContainer class="classes-hero-container">
        <div class="hero-content">
          <span class="hero-kicker">Encontre seu ritmo</span>
          <h1 class="hero-title">Descubra e Participe das Nossas Turmas</h1>
          <p class="hero-subtitle">
            Escolha o estilo e o horário que melhor se encaixam na sua rotina e venha dançar com a gente.
          </p>
        </div>
        
        <div class="hero-visual">
          <div class="abstract-shape shape-1">
            <img src="/hero.png" alt="Dança" />
          </div>
          <div class="abstract-shape shape-2">
            <img src="/hero.png" alt="Turma" />
          </div>
        </div>
      </UContainer>
    </section>

    <!-- Seção de Cards Inspirada em "Featured Events" -->
    <section class="classes-list-section">
      <!-- Glows Ambiente (como no design para não ficar totalmente liso) -->
      <div class="ambient-glow glow-orange"></div>
      <div class="ambient-glow glow-purple"></div>
      
      <UContainer>
        <div class="section-header flex-column flex-center">
          <span class="section-kicker">Próximas Turmas</span>
          <h2 class="section-title">Aulas e Imersões</h2>
        </div>

        <div class="classes-grid">
          <article v-for="(cls, index) in classesList" :key="index" class="class-card">
            <div class="card-image-wrap">
              <img :src="cls.image" :alt="cls.title" class="card-image" />
            </div>
            
            <div class="card-content">
              <h3 class="card-title">{{ cls.title }}</h3>
              
              <div class="card-schedule">
                <UIcon name="i-lucide-calendar" class="schedule-icon text-primary" />
                <span>{{ cls.schedule }}</span>
              </div>
              
              <div class="card-footer">
                <div class="card-level">
                  <span class="level-label">Nível</span>
                  <strong class="level-value">{{ cls.level }}</strong>
                </div>
                <UButton label="Matricular" variant="outline" color="primary" class="buy-button" />
              </div>
            </div>
          </article>
        </div>
        
        <div class="flex-center mt-12" style="position: relative; z-index: 1;">
          <UButton label="Mais informações via WhatsApp" size="xl" variant="soft" color="primary" trailing-icon="i-lucide-arrow-right" />
        </div>
      </UContainer>
    </section>
  </div>
</template>

<style scoped lang="scss">
.classes-page {
  /* Compensa o menu fixo */
  padding-top: 0rem;
}

/* --- Hero Section --- */
.classes-hero {
  position: relative;
  padding: 8rem 0 10rem;
  overflow: hidden;
}

.classes-hero-bg {
  position: absolute;
  inset: 0;
  /* Gradiente escuro puxando para o azul/roxo, inspirado na imagem */
  background: linear-gradient(135deg, var(--color-primary-dark) 0%, #1a0f30 50%, #0d061c 100%);
  border-bottom-left-radius: 50% 12%;
  border-bottom-right-radius: 50% 12%;
  z-index: -1;
}

.classes-hero-container {
  display: flex;
  align-items: center;
  gap: 2rem;
  
  @media (max-width: 1024px) {
    flex-direction: column;
    text-align: center;
  }
}

.hero-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.hero-kicker {
  font-family: 'Damion', cursive;
  font-size: 2.5rem;
  color: #a4f4d2; /* Cor destacada simulando o verde água do mockup */
  line-height: 1;
}

.hero-title {
  font-size: clamp(2.5rem, 5vw, 4rem);
  font-weight: 900;
  color: #ffffff;
  line-height: 1.1;
  margin: 0;
}

.hero-subtitle {
  font-size: 1.1rem;
  color: rgba(255, 255, 255, 0.85);
  max-width: 32rem;
  margin: 0;
  
  @media (max-width: 1024px) {
    margin: 0 auto;
  }
}

.hero-visual {
  flex: 1;
  position: relative;
  min-height: 400px;
  width: 100%;
  
  @media (max-width: 1024px) {
    display: none;
  }
}

.abstract-shape {
  position: absolute;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0,0,0,0.4);
  
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.shape-1 {
  top: -20px;
  right: 15%;
  width: 200px;
  height: 200px;
  /* Formato curvado simulando a abstração da imagem superior */
  border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
}

.shape-2 {
  bottom: 0;
  right: -5%;
  width: 320px;
  height: 320px;
  /* Círculo cortado, encostando na base */
  border-radius: 50% 50% 0 0;
}


/* --- Seção de Listagem de Turmas --- */
.classes-list-section {
  position: relative;
  padding: 6rem 0 8rem;
  background-color: var(--color-background);
  overflow: hidden;
}

/* Glows simulando o fundo macio da imagem de inspiração */
.ambient-glow {
  position: absolute;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  filter: blur(120px);
  z-index: 0;
  pointer-events: none;
}

.glow-orange {
  top: 10%;
  left: -250px;
  background: rgba(255, 178, 190, 0.15);
}

.glow-purple {
  bottom: 10%;
  right: -250px;
  background: rgba(var(--color-primary-dark), 0.1);
}

.section-header {
  position: relative;
  z-index: 1;
  margin-bottom: 4rem;
}

.section-kicker {
  font-family: 'Damion', cursive;
  font-size: 2rem;
  color: var(--color-primary);
  line-height: 1;
}

.section-title {
  font-size: 3rem;
  font-weight: 900;
  color: var(--color-text-primary);
  margin: 0;
}

.classes-grid {
  position: relative;
  z-index: 1;
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.class-card {
  flex: 1 1 320px;
  max-width: 420px;
  background: var(--glass-bg);
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  border: 1px solid var(--glass-border);
  transition: var(--transition-smooth);
  display: flex;
  flex-direction: column;
  
  &:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
  }
}

html.dark .class-card {
  box-shadow: var(--shadow-premium);
}

.card-image-wrap {
  width: 100%;
  height: 220px;
  overflow: hidden;
}

.card-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
  
  .class-card:hover & {
    transform: scale(1.05);
  }
}

.card-content {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.card-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--color-text-primary);
  margin: 0 0 1rem 0;
  line-height: 1.3;
}

.card-schedule {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-bottom: 1.5rem;
  
  span {
    color: var(--color-text-muted);
    font-size: 0.95rem;
    font-weight: 600;
  }
}

.schedule-icon {
  font-size: 1.2rem;
}

.card-footer {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-top: auto;
  padding-top: 1.5rem;
  border-top: 1px solid var(--glass-border);
}

.card-level {
  display: flex;
  flex-direction: column;
  gap: 0.1rem;
}

.level-label {
  font-size: 0.75rem;
  color: var(--color-text-muted);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 700;
}

.level-value {
  font-size: 0.95rem;
  color: var(--color-primary);
  font-weight: 900;
}

.buy-button {
  font-weight: 800;
  border-radius: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.8rem;
  padding: 0.5rem 1rem;
}
</style>
