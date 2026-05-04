import { describe, it, expect, vi } from 'vitest'
import { mountSuspended, mockNuxtImport } from '@nuxt/test-utils/runtime'
import Contato from '../../../app/pages/contato.vue'

// Mock dos composables do Nuxt
mockNuxtImport('useWordPress', () => {
  return () => ({
    submitContactForm: vi.fn().mockResolvedValue({ message: 'Sucesso!' })
  })
})

mockNuxtImport('useToast', () => {
  return () => ({
    add: vi.fn()
  })
})

describe('Página de Contato', () => {
  it('deve renderizar os campos do formulário', async () => {
    const wrapper = await mountSuspended(Contato)
    
    expect(wrapper.find('input').exists()).toBe(true)
    expect(wrapper.find('textarea').exists()).toBe(true)
    expect(wrapper.find('button[type="submit"]').exists()).toBe(true)
  }, 15000)

  it('deve validar o formato do email', async () => {
    const wrapper = await mountSuspended(Contato)
    
    // Preenche o email com valor inválido
    const emailInput = wrapper.find('input[type="email"]')
    await emailInput.setValue('email-invalido')
    
    // Tenta submeter
    const formElement = wrapper.find('form')
    await formElement.trigger('submit')
    
    // Aguarda validação (Nuxt UI Form validation is async)
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Verifica se a mensagem de erro aparece
    // Verifica se a mensagem de erro aparece
    expect(wrapper.text()).toContain('Email inválido')
  }, 15000)

  it('deve validar campos obrigatórios ao submeter vazio', async () => {
    const wrapper = await mountSuspended(Contato)
    
    // Tenta submeter sem preencher nada
    const formElement = wrapper.find('form')
    await formElement.trigger('submit')
    
    // Aguarda validação
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Verifica se as mensagens de erro aparecem
    const text = wrapper.text()
    expect(text).toContain('Nome é obrigatório')
    expect(text).toContain('Mensagem é obrigatória')
  }, 15000)
})
