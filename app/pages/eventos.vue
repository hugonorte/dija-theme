<script setup lang="ts">
definePageMeta({
  layout: 'homepage'
})

useSeoMeta({
  title: 'Eventos - DIJA',
  description: 'Contrate nossos serviços para Inagurações, Eventos corporativos, Promoção de lojas e Workshops.',
})

const eventsList = [
  {
    title: 'Inaugurações',
    desc: 'Atraia o público e crie um clima inesquecível para a abertura do seu novo espaço com a energia e alegria da dança.',
    image: '/hero.png'
  },
  {
    title: 'Eventos Corporativos',
    desc: 'Integração, descontração e bem-estar para os colaboradores da sua empresa através de dinâmicas e apresentações.',
    image: '/hero.png'
  },
  {
    title: 'Promoção de Lojas',
    desc: 'Movimente o seu ponto de venda com aulas curtas e interações que chamam a atenção e fidelizam clientes.',
    image: '/hero.png'
  },
  {
    title: 'Workshops',
    desc: 'Imersões intensivas e aulas especiais sobre Forró e Dança de Salão desenvolvidas especialmente para o seu público.',
    image: '/hero.png'
  }
]
</script>

<template>
  <div class="events-page">
    <!-- Hero Section Inspirado no Mockup EventPlace -->
    <section class="events-hero">
      <div class="events-hero-bg"></div>
      <UContainer class="events-hero-container">
        <div class="hero-content">
          <span class="hero-kicker">Leve a dança até você</span>
          <h1 class="hero-title">Eventos e Parcerias</h1>
          <p class="hero-subtitle">
            Transforme o seu evento em uma experiência única com as nossas soluções focadas em dança, ritmo e conexão.
          </p>
        </div>
        
        <div class="hero-visual">
          <div class="abstract-shape shape-1">
            <img src="/hero.png" alt="Dança em Eventos" />
          </div>
          <div class="abstract-shape shape-2">
            <img src="/hero.png" alt="Celebração" />
          </div>
        </div>
      </UContainer>
    </section>

    <!-- Seção de Cards -->
    <section class="events-list-section">
      <!-- Glows Ambiente -->
      <div class="ambient-glow glow-orange"></div>
      <div class="ambient-glow glow-purple"></div>
      
      <UContainer>
        <div class="section-header flex-column flex-center">
          <span class="section-kicker">Nossas Soluções</span>
          <h2 class="section-title">Tipos de Eventos</h2>
        </div>

        <div class="events-grid">
          <article v-for="(event, index) in eventsList" :key="index" class="event-card">
            <div class="card-image-wrap">
              <img :src="event.image" :alt="event.title" class="card-image" />
            </div>
            
            <div class="card-content">
              <h3 class="card-title">{{ event.title }}</h3>
              <p class="card-desc">{{ event.desc }}</p>
              
              <div class="card-footer">
                <UButton label="Solicitar Orçamento" variant="outline" color="primary" class="contact-button" block />
              </div>
            </div>
          </article>
        </div>
        
        <div class="flex-center mt-12" style="position: relative; z-index: 1;">
          <UButton label="Falar com a equipe" size="xl" variant="soft" color="primary" trailing-icon="i-lucide-arrow-right" to="/contato" />
        </div>
      </UContainer>
    </section>
  </div>
</template>

<style scoped lang="scss">
.events-page {
  padding-top: 0rem;
}

/* --- Hero Section --- */
.events-hero {
  position: relative;
  padding: 8rem 0 10rem;
  overflow: hidden;
}

.events-hero-bg {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--color-primary-dark) 0%, #1a0f30 50%, #0d061c 100%);
  border-bottom-left-radius: 50% 12%;
  border-bottom-right-radius: 50% 12%;
  z-index: -1;
}

.events-hero-container {
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
  color: #a4f4d2; 
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
  border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
}

.shape-2 {
  bottom: 0;
  right: -5%;
  width: 320px;
  height: 320px;
  border-radius: 50% 50% 0 0;
}


/* --- Seção de Listagem de Eventos --- */
.events-list-section {
  position: relative;
  padding: 6rem 0 8rem;
  background-color: var(--color-background);
  overflow: hidden;
}

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

.events-grid {
  position: relative;
  z-index: 1;
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.event-card {
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

html.dark .event-card {
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
  
  .event-card:hover & {
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

.card-desc {
  color: var(--color-text-muted);
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0 0 1.5rem 0;
}

.card-footer {
  margin-top: auto;
  padding-top: 1.5rem;
  border-top: 1px solid var(--glass-border);
}

.contact-button {
  font-weight: 800;
  border-radius: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.85rem;
  padding: 0.75rem 1rem;
  justify-content: center;
}
</style>
