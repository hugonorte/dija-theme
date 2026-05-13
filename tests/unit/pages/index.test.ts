import { describe, it, expect, vi } from 'vitest'
import { mountSuspended, mockNuxtImport } from '@nuxt/test-utils/runtime'
import Index from '../../../app/pages/index.vue'

// Mock do useWordPress
mockNuxtImport('useWordPress', () => {
  return () => ({
    getPosts: vi.fn().mockResolvedValue([
      {
        id: 1,
        title: { rendered: 'Post de Teste 1' },
        excerpt: { rendered: 'Resumo do post 1' },
        slug: 'post-teste-1',
        date: '2024-05-11T12:00:00'
      }
    ])
  })
})

describe('Página Inicial', () => {
  it('deve renderizar o título principal (Hero)', async () => {
    const wrapper = await mountSuspended(Index)

    // Verificamos o título principal conforme o design do Stitch
    // Verificamos partes do título para evitar problemas com a quebra de linha <br>
    expect(wrapper.text()).toContain('DIJA - Ritmo')
    expect(wrapper.text()).toContain('Dança e Tradição')
  })

  it('deve renderizar a seção de artigos', async () => {
    const wrapper = await mountSuspended(Index)

    // Verificamos se o post mockado aparece
    expect(wrapper.text()).toContain('Post de Teste 1')
  })

  it('deve renderizar a seção de agenda', async () => {
    const wrapper = await mountSuspended(Index)

    // Verificamos textos específicos da agenda no design
    expect(wrapper.text()).toContain('Workshop Forró das Antigas')
  })

  it('deve renderizar a seção de comunidade', async () => {
    const wrapper = await mountSuspended(Index)

    // Verificamos benefícios listados
    expect(wrapper.text()).toContain('Acesso a todas as videoaulas')
  })
})
