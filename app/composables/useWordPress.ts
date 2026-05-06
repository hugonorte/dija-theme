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

interface ContactSubmission {
  name: string
  email: string
  whatsapp: string
  message: string
  fax_number?: string // Campo Honeypot
}

interface WordPressRuntimeConfig {
  public: {
    wpApiUrl: string
  }
}

export const useWordPress = (config?: WordPressRuntimeConfig) => {
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
      return posts[0] || null
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
      return pages[0] || null
    } catch (error) {
      console.error(`Erro ao buscar página com slug ${slug}:`, error)
      throw error
    }
  }

  /**
   * Envia uma mensagem de contato para o WordPress
   */
  const submitContactForm = async (data: ContactSubmission): Promise<{ message: string }> => {
    try {
      // Removemos 'wp/v2/' da URL base para acessar o namespace customizado 'dija/v1'
      const customApiUrl = wpApiUrl.replace('wp/v2/', 'dija/v1/')
      return await $fetch<{ message: string }>(`${customApiUrl}contact`, {
        method: 'POST',
        body: data
      })
    } catch (error) {
      console.error('Erro ao enviar formulário de contato:', error)
      throw error
    }
  }

  return {
    getPosts,
    getPostBySlug,
    getPageBySlug,
    submitContactForm
  }
}
