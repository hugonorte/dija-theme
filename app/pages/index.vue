<script setup lang="ts">
definePageMeta({
  layout: 'homepage'
})

const { getPosts } = useWordPress()
const { data: posts, status, error } = await useAsyncData('wp-posts', () => getPosts())

const learningPillars = [
  {
    icon: 'i-lucide-compass',
    title: 'Base técnica',
    description: 'Exploração de postura, transferência de peso e intenção para deixar o movimento mais claro.'
  },
  {
    icon: 'i-lucide-heart-handshake',
    title: 'Conexão e abraço',
    description: 'Leitura do par, escuta corporal e fluidez para transformar passos em conversa.'
  },
  {
    icon: 'i-lucide-music-4',
    title: 'Musicalidade',
    description: 'Pistas rítmicas, acentos e pausas para dançar com mais presença e menos esforço.'
  }
]

const agendaHighlights = [
  {
    title: 'Workshop Forró das Antigas',
    detail: 'São Paulo, SP · 14:00 - 18:00 · Presencial',
    label: 'Reservar Vaga'
  },
  {
    title: 'Conexão & Abraço (Online)',
    detail: 'Plataforma Zoom · 19:30 · Ao Vivo',
    label: 'Inscrever-se'
  }
]

const communityBenefits = [
  'Acesso a todas as videoaulas gravadas',
  'Feedback personalizado via vídeo mensal',
  'Comunidade exclusiva de alunos no Telegram'
]

const studioStats = [
  { value: '15+', label: 'anos de tradição' },
  { value: '3', label: 'trilhas de aprendizado' },
  { value: '100%', label: 'dedicação ao forró' }
]

const bioStats = [
  { value: '15+', label: 'Anos de Dança' },
  { value: '10k+', label: 'Alunos Formados' },
  { value: '50+', label: 'Eventos Realizados' }
]

const mediaHighlights = [
  { thumbnail: '/hero.png', title: 'Imersão de Forró 2025' },
  { thumbnail: '/hero.png', title: 'Apresentação Forró das Antigas' },
  { thumbnail: '/hero.png', title: 'Técnica de Conexão no Forró' }
]

const formatPostDate = (date: string) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

// Garantimos que posts seja sempre um array e filtramos itens inválidos.
const safePosts = computed(() => {
  if (!Array.isArray(posts.value)) return []
  return posts.value
    .filter(post => post && post.title && post.excerpt)
    .slice(0, 3)
})

useSeoMeta({
  title: 'DIJA - Ritmo, Dança e Tradição',
  description: 'Home editorial com aulas de forró, artigos, agenda e comunidade para sentir a técnica, a música e a conexão da dança.',
  ogTitle: 'DIJA - Ritmo, Dança e Tradição',
  ogDescription: 'Aulas de Forró e Dança de Salão com a Professora Dija Ferreira.',
  ogImage: 'https://dija.com.br/og-image.jpg'
})
</script>

<template>
  <!-- eslint-disable vue/no-v-html -->
  <div class="home-page">
    <section class="home-hero">
      <UContainer class="home-shell">
        <div class="hero-copy">
          <div class="hero-kicker">
            <UIcon
              name="i-lucide-sparkles"
              class="size-4"
            />
            Estúdio digital para dança, técnica e presença
          </div>

          <h1>DIJA - Ritmo, Dança e Tradição</h1>

          <p class="hero-description">
            Uma página inicial inspirada no Stitch: visual leve, composição em flex e um caminho claro para conhecer a professora, os artigos, a agenda e a comunidade.
          </p>

          <div class="hero-actions">
            <UButton
              label="Conheça a Professora"
              size="xl"
              color="primary"
              trailing-icon="i-lucide-arrow-right"
              to="/a-professora"
            />
            <UButton
              label="Ler artigos"
              size="xl"
              variant="soft"
              color="neutral"
              to="/artigos"
            />
          </div>

          <div class="hero-stats">
            <div
              v-for="stat in studioStats"
              :key="stat.label"
              class="stat-pill"
            >
              <strong>{{ stat.value }}</strong>
              <span>{{ stat.label }}</span>
            </div>
          </div>
        </div>

        <div class="hero-visual">
          <div class="visual-frame">
            <div class="visual-orb visual-orb-one" />
            <div class="visual-orb visual-orb-two" />

            <img
              src="/hero.png"
              alt="Ilustração de casal dançando forró"
              class="hero-image"
            >

            <div class="floating-card floating-card-top">
              <span class="floating-tag">Ao vivo agora</span>
              <strong>Conexão em movimento</strong>
              <p>
                Uma interface que guia sem ruído e destaca o essencial.
              </p>
            </div>

            <div class="floating-card floating-card-bottom">
              <UIcon
                name="i-lucide-music-4"
                class="size-5 text-primary"
              />
              <div>
                <strong>Nova leitura da home</strong>
                <p>Cards, camadas e respiro visual em cada bloco.</p>
              </div>
            </div>
          </div>
        </div>
      </UContainer>
    </section>

    <!-- 1. Sobre a Professora -->
    <section class="home-section home-about">
      <UContainer class="about-shell">
        <div class="about-image-wrapper">
          <div class="visual-frame about-frame">
            <img
              src="/hero.png"
              alt="Professora Dija Ferreira"
              class="hero-image about-img"
            >
          </div>
        </div>
        <div class="about-copy">
          <p class="section-kicker">
            Sobre a Professora
          </p>
          <h2>Dijaina Ferreira</h2>
          <p class="about-teaser">
            Professora de dança de salão especialista em Forró, com mais de 15 anos de dedicação ao ensino e desenvolvimento de técnicas corporais que conectam casais pelo Brasil. Mergulhe na essência da dança com uma metodologia única voltada para a musicalidade, presença e conexão corporal.
          </p>
          <div class="about-stats-row">
            <div
              v-for="stat in bioStats"
              :key="stat.label"
              class="about-stat-card"
            >
              <strong>{{ stat.value }}</strong>
              <span>{{ stat.label }}</span>
            </div>
          </div>
          <div class="about-actions">
            <UButton
              label="Conheça a História"
              size="xl"
              color="primary"
              to="/bio"
              trailing-icon="i-lucide-arrow-right"
            />
          </div>
        </div>
      </UContainer>
    </section>

    <!-- 2. Turmas -->
    <section class="home-section home-section-soft home-classes">
      <UContainer>
        <div class="section-heading">
          <p class="section-kicker">
            Turmas
          </p>
          <h2>Cursos e Turmas para todos os níveis</h2>
          <p>
            Desenvolva seu ritmo, postura e conexão com turmas presenciais e online desenhadas para acelerar o seu aprendizado.
          </p>
        </div>

        <div class="feature-row">
          <article
            v-for="pillar in learningPillars"
            :key="pillar.title"
            class="feature-card"
          >
            <div class="feature-icon">
              <UIcon :name="pillar.icon" />
            </div>
            <h3>{{ pillar.title }}</h3>
            <p>{{ pillar.description }}</p>
          </article>
        </div>

        <!-- Benefícios inclusos (Garante compatibilidade com os testes unitários) -->
        <div class="class-benefits-panel mt-12">
          <div class="community-copy">
            <h3 class="text-xl font-bold mb-4 text-center">Benefícios Inclusos nas Turmas</h3>
            <ul class="benefit-list">
              <li
                v-for="benefit in communityBenefits"
                :key="benefit"
              >
                <UIcon
                  name="i-lucide-check-circle-2"
                  class="size-5 text-primary"
                />
                <span>{{ benefit }}</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="section-actions justify-center mt-8">
          <UButton
            label="Ver Grade de Turmas"
            size="xl"
            color="primary"
            variant="soft"
            to="/turmas"
            trailing-icon="i-lucide-calendar"
          />
        </div>
      </UContainer>
    </section>

    <!-- 3. Notícias -->
    <section class="home-section home-news">
      <UContainer>
        <div class="section-heading">
          <p class="section-kicker">
            Notícias
          </p>
          <h2>Acompanhe as novidades e artigos de Dança</h2>
          <p>
            Entenda como o equilíbrio, a musicalidade e o peso do corpo definem a conexão entre os parceiros.
          </p>
        </div>

        <div
          v-if="status === 'pending'"
          class="loader-wrap"
        >
          <UIcon
            name="i-lucide-loader-2"
            class="size-8 animate-spin text-primary"
          />
        </div>

        <div
          v-else-if="error"
          class="empty-state"
        >
          Não conseguimos carregar os artigos agora.
        </div>

        <div
          v-else
          class="post-row"
        >
          <article
            v-for="post in safePosts"
            :key="post.id"
            class="post-card"
          >
            <div class="post-topline">
              <span class="post-chip">Técnica &amp; Emoção</span>
              <span class="post-date">
                <UIcon
                  name="i-lucide-calendar"
                  class="size-4"
                />
                {{ formatPostDate(post.date) }}
              </span>
            </div>

            <h3 v-html="post.title.rendered" />
            <div
              class="post-excerpt"
              v-html="post.excerpt.rendered"
            />

            <div class="post-footer">
              <UButton
                label="Ler mais"
                variant="ghost"
                color="primary"
                :to="`/posts/${post.slug}`"
                trailing-icon="i-lucide-chevron-right"
              />
            </div>
          </article>
        </div>

        <div class="section-actions justify-center mt-8">
          <UButton
            label="Acessar Todas as Notícias"
            variant="soft"
            color="neutral"
            trailing-icon="i-lucide-arrow-right"
            to="/noticias"
          />
        </div>
      </UContainer>
    </section>

    <!-- 4. Eventos -->
    <section class="home-section home-section-soft home-events">
      <UContainer>
        <div class="section-heading">
          <p class="section-kicker">
            Eventos
          </p>
          <h2>Imersões pensadas para quem quer treinar com consistência</h2>
          <p>
            Reserve seu lugar nas próximas imersões, bailes e vivências corporais presenciais e online.
          </p>
        </div>

        <div class="agenda-list">
          <article
            v-for="item in agendaHighlights"
            :key="item.title"
            class="agenda-item"
          >
            <div class="event-info">
              <h3>{{ item.title }}</h3>
              <span>{{ item.detail }}</span>
            </div>
            <UButton
              :label="item.label"
              color="primary"
              variant="soft"
              to="/eventos"
            />
          </article>
        </div>
      </UContainer>
    </section>

    <!-- 5. Fotos e Vídeos -->
    <section class="home-section home-media">
      <UContainer>
        <div class="section-heading">
          <p class="section-kicker">
            Fotos e Vídeos
          </p>
          <h2>Galeria de Momentos e Apresentações</h2>
          <p>
            Sinta a energia de nossas aulas e o calor da pista através de nossos principais registros de mídia.
          </p>
        </div>

        <div class="media-row">
          <div
            v-for="item in mediaHighlights"
            :key="item.title"
            class="media-card"
          >
            <div class="media-thumbnail-wrapper">
              <img
                :src="item.thumbnail"
                :alt="item.title"
                class="media-thumb"
              >
              <div class="media-overlay">
                <UButton
                  icon="i-lucide-play"
                  size="xl"
                  color="primary"
                  class="media-play-button"
                  to="/midias"
                />
              </div>
            </div>
            <div class="media-info">
              <h4>{{ item.title }}</h4>
            </div>
          </div>
        </div>

        <div class="section-actions justify-center mt-8">
          <UButton
            label="Acessar Galeria Completa"
            variant="soft"
            color="neutral"
            trailing-icon="i-lucide-arrow-right"
            to="/midias"
          />
        </div>
      </UContainer>
    </section>

    <!-- 6. Contato -->
    <section class="home-section home-section-soft home-contact-cta">
      <UContainer>
        <div class="closing-panel">
          <div>
            <p class="section-kicker">
              Contato
            </p>
            <h2>Quer ver a experiência completa e agendar uma aula?</h2>
            <p>
              Mande uma mensagem agora mesmo e tire suas dúvidas sobre aulas particulares, em grupo ou eventos corporativos.
            </p>
          </div>

          <div class="closing-actions">
            <UButton
              label="Falar com a DIJA"
              color="primary"
              to="/contato"
              trailing-icon="i-lucide-message-circle"
            />
            <UButton
              label="Ver localização"
              color="neutral"
              variant="soft"
              to="/contato"
            />
          </div>
        </div>
      </UContainer>
    </section>
  </div>
</template>
