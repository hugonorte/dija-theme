<script setup lang="ts">
const route = useRoute()
// O [...slug] retorna um array. Pegamos o último item ou montamos o caminho completo.
// Para páginas simples do WP, geralmente é apenas um nível.
const slug = Array.isArray(route.params.slug) ? route.params.slug.join('/') : route.params.slug
const { getPageBySlug } = useWordPress()

// Busca a página baseado no slug
const { data: page, status, error } = await useAsyncData(`page-${slug}`, () => getPageBySlug(slug))

// SEO
useSeoMeta({
  title: () => page.value?.title.rendered || 'Página',
  description: () => 'Veja mais informações no Blog DIJA.'
})
</script>

<template>
  <UContainer class="py-12 max-w-4xl">
    <!-- Estado de Carregamento -->
    <div v-if="status === 'pending'" class="flex flex-col items-center justify-center py-20">
      <UIcon name="i-lucide-loader-2" class="animate-spin size-12 text-primary" />
    </div>

    <!-- Estado de Erro / Não encontrado -->
    <div v-else-if="error || !page" class="py-20 text-center">
      <h1 class="text-4xl font-bold mb-4">404</h1>
      <p class="text-xl text-muted mb-8">Página não encontrada.</p>
      <UButton to="/" label="Voltar para a Home" color="primary" />
    </div>

    <!-- Conteúdo da Página -->
    <article v-else class="page-content">
      <header class="mb-12 border-b border-primary/10 pb-8">
        <h1 class="text-4xl md:text-5xl font-bold text-primary leading-tight" v-html="page.title.rendered" />
      </header>

      <UPageBody class="prose prose-primary dark:prose-invert max-w-none">
        <div class="wp-content" v-html="page.content.rendered" />
      </UPageBody>
    </article>
  </UContainer>
</template>

<style lang="scss">
/* Reutilizamos os estilos definidos para o conteúdo do WordPress */
.page-content {
  .wp-content {
    line-height: 1.8;
    font-size: 1.125rem;

    p {
      margin-bottom: 1.5rem;
    }

    h2, h3 {
      margin-top: 2rem;
      color: var(--ui-primary);
    }
    
    blockquote {
      border-left: 4px solid var(--ui-primary);
      padding-left: 1.5rem;
      font-style: italic;
      margin: 2rem 0;
    }
  }
}
</style>
