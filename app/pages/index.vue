<script setup lang="ts">
definePageMeta({
  layout: 'homepage'
})
// Buscamos nosso service de WordPress
const { getPosts } = useWordPress()

// Usamos useAsyncData para buscar os posts no lado do servidor (SSR)
const { data: posts, status, error } = await useAsyncData('wp-posts', () => getPosts())

// Garantimos que posts seja sempre um array e filtramos itens inválidos para evitar erros de renderização
const safePosts = computed(() => {
  if (!Array.isArray(posts.value)) return []
  return posts.value.filter(post => post && post.title && post.excerpt)
})
</script>

<template>
  <!-- eslint-disable vue/no-v-html -->
  <UContainer>
    <!-- Hero Section: Identidade da Dijaina -->
    <UPageHero
      title="Blog Vue WP"
      description="Testando conexão com WordPress"
      class="py-12"
    />

    <!-- Estado de Carregamento -->
    <UPageSection v-if="status === 'pending'">
      <div class="flex items-center justify-center py-20">
        <UIcon
          name="i-lucide-loader-2"
          class="animate-spin size-10 text-primary"
        />
        <span class="ml-3 text-lg text-muted">Buscando novidades no salão...</span>
      </div>
    </UPageSection>

    <!-- Estado de Erro ou Dados Inválidos -->
    <UPageSection v-else-if="error || !Array.isArray(posts)">
      <UAlert
        color="error"
        variant="subtle"
        title="Opa! Tivemos um problema na conexão."
        description="Não foi possível carregar os posts do WordPress. Verifique se a URL da API está correta e se o WordPress está instalado."
        icon="i-lucide-circle-alert"
      />
    </UPageSection>

    <!-- Lista de Posts -->
    <UPageSection
      v-else
      title="Posts Recentes"
      description="Acompanhe as últimas postagens sobre dança de salão."
    >
      <div
        v-if="safePosts.length > 0"
        class="flex flex-wrap -mx-4"
      >
        <!-- Loop de Posts usando FLEX como solicitado -->
        <div
          v-for="post in safePosts"
          :key="post.id"
          class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8 flex"
        >
          <UCard
            class="flex-1 flex flex-col hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer border-primary/10"
          >
            <!-- Título do Post -->
            <template #header>
              <!-- eslint-disable-next-line vue/no-v-html -->
              <h3
                class="text-xl font-bold text-primary line-clamp-2"
                v-html="post.title.rendered"
              />
            </template>

            <!-- Resumo (Excerpt) -->
            <!-- eslint-disable-next-line vue/no-v-html -->
            <div
              class="flex-1 text-sm text-muted line-clamp-3 mb-4"
              v-html="post.excerpt.rendered"
            />

            <!-- Rodapé com Data e Botão -->
            <template #footer>
              <div class="flex justify-between items-center w-full">
                <div class="flex items-center text-xs text-muted">
                  <UIcon
                    name="i-lucide-calendar"
                    class="mr-1"
                  />
                  {{ new Date(post.date).toLocaleDateString('pt-BR') }}
                </div>
                <UButton
                  :to="`/posts/${post.slug}`"
                  label="Ler mais"
                  variant="ghost"
                  trailing-icon="i-lucide-arrow-right"
                  color="primary"
                />
              </div>
            </template>
          </UCard>
        </div>
      </div>

      <!-- Caso não haja posts -->
      <div
        v-else
        class="text-center py-10 text-muted"
      >
        Nenhum post encontrado no momento. Comece a escrever no seu WordPress!
      </div>
    </UPageSection>

    <!-- Seção de Call to Action (Inspirado no visual moderno) -->
    <UPageSection v-if="!error && status !== 'pending'">
      <UPageCTA
        title="Quer aprender a dançar?"
        description="Aulas particulares e em grupo com Dijaina Ferreira. Entre em contato e agende sua primeira aula!"
        variant="subtle"
        :links="[{
          label: 'Ver aulas',
          to: '#',
          color: 'primary',
          size: 'lg'
        }]"
      />
    </UPageSection>
  </UContainer>
</template>

<style lang="scss" scoped>
/* Estilos SCSS como solicitado */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
