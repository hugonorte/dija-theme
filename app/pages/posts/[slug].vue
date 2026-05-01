<script setup lang="ts">
const route = useRoute()
const slug = route.params.slug as string
const { getPostBySlug } = useWordPress()

// Busca o post baseado no slug da URL
const { data: post, status, error } = await useAsyncData(`post-${slug}`, () => getPostBySlug(slug))

// SEO: Atualiza o título da página com o título do post
useSeoMeta({
  title: () => post.value?.title.rendered || 'Carregando post...',
  description: () => post.value?.excerpt.rendered.replace(/<[^>]*>/g, '') || 'Leia mais sobre dança de salão no Blog DIJA.'
})
</script>

<template>
  <!-- eslint-disable vue/no-v-html -->
  <UContainer class="py-10 max-w-4xl">
    <!-- Botão de Voltar -->
    <div class="mb-8">
      <UButton
        to="/"
        label="Voltar para a lista"
        variant="ghost"
        icon="i-lucide-arrow-left"
        color="neutral"
      />
    </div>

    <!-- Estado de Carregamento -->
    <div
      v-if="status === 'pending'"
      class="flex flex-col items-center justify-center py-20"
    >
      <UIcon
        name="i-lucide-loader-2"
        class="animate-spin size-12 text-primary mb-4"
      />
      <p class="text-lg text-muted">
        Buscando o conteúdo do post...
      </p>
    </div>

    <!-- Estado de Erro -->
    <div
      v-else-if="error || !post"
      class="py-10"
    >
      <UAlert
        color="error"
        variant="subtle"
        icon="i-lucide-circle-alert"
        title="Post não encontrado"
        description="Não foi possível carregar este conteúdo. Ele pode ter sido removido ou o link está incorreto."
      />
    </div>

    <!-- Conteúdo do Post -->
    <article
      v-else
      class="post-content"
    >
      <header class="mb-10">
        <!-- eslint-disable-next-line vue/no-v-html -->
        <h1
          class="text-4xl md:text-5xl font-bold text-primary mb-4 leading-tight"
          v-html="post.title.rendered"
        />

        <div class="flex items-center gap-4 text-sm text-muted">
          <div class="flex items-center">
            <UIcon
              name="i-lucide-calendar"
              class="mr-1"
            />
            {{ new Date(post.date).toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' }) }}
          </div>
          <div class="flex items-center">
            <UIcon
              name="i-lucide-clock"
              class="mr-1"
            />
            Leitura rápida
          </div>
        </div>
      </header>

      <!-- Corpo do Post (WordPress HTML) -->
      <UPageBody class="prose prose-primary dark:prose-invert max-w-none">
        <!-- eslint-disable-next-line vue/no-v-html -->
        <div
          class="wp-content"
          v-html="post.content.rendered"
        />
      </UPageBody>

      <UDivider class="my-12" />

      <!-- Footer do Post / Compartilhamento -->
      <footer class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
          <p class="font-medium text-lg">
            Gostou deste conteúdo?
          </p>
          <p class="text-muted">
            Aulas de dança de salão com Dijaina Ferreira.
          </p>
        </div>
        <UButton
          label="Agendar Aula"
          color="primary"
          size="lg"
          trailing-icon="i-lucide-external-link"
        />
      </footer>
    </article>
  </UContainer>
</template>

<style lang="scss">
/* Estilos globais para o conteúdo vindo do WordPress */
.wp-content {
  line-height: 1.8;
  font-size: 1.125rem;

  p {
    margin-bottom: 1.5rem;
    color: var(--ui-text-muted);
  }

  h2, h3, h4 {
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: var(--ui-primary);
  }

  h2 { font-size: 2rem; }
  h3 { font-size: 1.5rem; }

  img {
    border-radius: 0.75rem;
    margin: 2rem auto;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    max-width: 100%;
    height: auto;
  }

  ul, ol {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;

    li {
      margin-bottom: 0.5rem;
    }
  }

  blockquote {
    border-left: 4px solid var(--ui-primary);
    padding: 1rem 1.5rem;
    background: color-mix(in srgb, var(--ui-primary) 5%, transparent);
    font-style: italic;
    margin: 2rem 0;
    border-radius: 0 0.5rem 0.5rem 0;
  }
}
</style>
