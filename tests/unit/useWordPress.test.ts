/**
 * @vitest-environment happy-dom
 */
import { describe, it, expect, vi } from 'vitest'
import { useWordPress } from '../../app/composables/useWordPress'

// Mock do $fetch global
vi.stubGlobal('$fetch', vi.fn())

describe('useWordPress Composable', () => {
  it('deve buscar a lista de posts do WordPress', async () => {
    const mockPosts = [{ id: 1, title: { rendered: 'Post de Teste' } }]
    const mockConfig = {
      public: {
        wpApiUrl: 'https://dija.com.br/wp-json/wp/v2/'
      }
    }

    // Configura o mock do $fetch
    vi.mocked($fetch).mockResolvedValue(mockPosts)

    // Passamos o mockConfig diretamente (Injeção de Dependência)
    const { getPosts } = useWordPress(mockConfig)
    const posts = await getPosts()

    expect(posts).toEqual(mockPosts)
    expect($fetch).toHaveBeenCalledWith('https://dija.com.br/wp-json/wp/v2/posts')
  })
})
