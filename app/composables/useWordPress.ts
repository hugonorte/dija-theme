interface WordPressPost {
  id: number
  date: string
  slug: string
  title: {
    rendered: string
  }
  excerpt: {
    rendered: string
  }
  content: {
    rendered: string
  }
}

interface WordPressPage {
  id: number
  date: string
  slug: string
  title: {
    rendered: string
  }
  content: {
    rendered: string
  }
}

export const useWordPress = (config?: any) => {
  // Se não for passada configuração (ex: em produção), usa o useRuntimeConfig() do Nuxt
  const runtimeConfig = config || useRuntimeConfig()
  const wpApiUrl = runtimeConfig.public.wpApiUrl

  /**
   * Busca a lista de posts do WordPress
   */
  const getPosts = async (): Promise<WordPressPost[]> => {
    try {
      // Usamos o $fetch global do Nuxt com o tipo genérico
      return await $fetch<WordPressPost[]>(`${wpApiUrl}posts`)
    } catch (error) {
      console.error('Erro ao buscar posts do WordPress:', error)
      throw error
    }
  }

  /**
   * Busca um post específico pelo seu slug
   */
  const getPostBySlug = async (slug: string): Promise<WordPressPost | null> => {
    try {
      // A API retorna um array, pegamos o primeiro item
      const posts = await $fetch<WordPressPost[]>(`${wpApiUrl}posts?slug=${slug}`)
      return posts.length > 0 ? posts[0] : null
    } catch (error) {
      console.error(`Erro ao buscar post com slug ${slug}:`, error)
      throw error
    }
  }

  /**
   * Busca uma página específica pelo seu slug
   */
  const getPageBySlug = async (slug: string): Promise<WordPressPage | null> => {
    try {
      const pages = await $fetch<WordPressPage[]>(`${wpApiUrl}pages?slug=${slug}`)
      return pages.length > 0 ? pages[0] : null
    } catch (error) {
      console.error(`Erro ao buscar página com slug ${slug}:`, error)
      throw error
    }
  }

  return {
    getPosts,
    getPostBySlug,
    getPageBySlug
  }
}
