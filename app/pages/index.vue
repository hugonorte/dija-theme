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

    <section class="home-section home-section-soft">
      <UContainer>
        <div class="section-heading">
          <p class="section-kicker">
            Direção visual
          </p>
          <h2>Três pontos que organizam o conteúdo da home</h2>
          <p>
            O layout foi desenhado para parecer uma vitrine editorial: cada bloco tem hierarquia, ritmo e respiro.
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
      </UContainer>
    </section>

    <section class="home-section">
      <UContainer>
        <div class="section-heading">
          <p class="section-kicker">
            Leitura recente
          </p>
          <h2>Artigos sobre a alma e a mecânica do Forró</h2>
          <p>
            Entenda como o equilíbrio e o peso do corpo definem a conexão entre os parceiros.
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

        <div class="section-actions">
          <UButton
            label="Ver todos os artigos"
            variant="soft"
            color="neutral"
            trailing-icon="i-lucide-arrow-right"
            to="/artigos"
          />
        </div>
      </UContainer>
    </section>

    <section class="home-section home-section-soft">
      <UContainer>
        <div class="section-heading">
          <p class="section-kicker">
            Agenda ao vivo
          </p>
          <h2>Imersões pensadas para quem quer treinar com consistência</h2>
          <p>
            Reserve seu lugar nas próximas experiências presenciais e online. Vagas limitadas.
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
            />
          </article>
        </div>
      </UContainer>
    </section>

    <section class="home-section">
      <UContainer>
        <div class="community-panel">
          <div class="community-copy">
            <div class="section-heading section-heading-left">
              <p class="section-kicker">
                Comunidade DIJA
              </p>
              <h2>Junte-se a centenas de alunos e evolua sua dança com suporte contínuo.</h2>
              <p>
                Conte com aulas, orientações e um espaço coletivo para praticar com mais clareza.
              </p>
            </div>

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

            <div class="section-actions section-actions-left">
              <UButton
                label="Fazer parte da comunidade"
                size="xl"
                color="primary"
                to="/comunidade"
              />
            </div>
          </div>

          <div class="community-visual">
            <div class="community-card">
              <span class="floating-tag">Próximo ciclo</span>
              <h3>Movimento, presença e musicalidade em uma mesma jornada.</h3>
              <p>
                Um bloco de apoio para manter a página viva, clara e fácil de explorar.
              </p>
              <div class="community-mini-stats">
                <div>
                  <strong>Videoaulas</strong>
                  <span>acesso contínuo</span>
                </div>
                <div>
                  <strong>Feedback</strong>
                  <span>mensal e direcionado</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </UContainer>
    </section>

    <section class="home-section home-section-cta">
      <UContainer>
        <div class="closing-panel">
          <div>
            <p class="section-kicker">
              Próximo passo
            </p>
            <h2>Quer ver a experiência completa em outra tela?</h2>
            <p>
              Navegue para a página da professora, confira os artigos ou continue pela agenda.
            </p>
          </div>

          <div class="closing-actions">
            <UButton
              label="Falar com a DIJA"
              color="neutral"
              variant="soft"
              to="/contato"
            />
            <UButton
              label="Explorar o blog"
              color="primary"
              to="/artigos"
            />
          </div>
        </div>
      </UContainer>
    </section>
  </div>
</template>
