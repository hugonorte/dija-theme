import { describe, it, expect } from 'vitest'
import { useWordPress } from '../../app/composables/useWordPress'

/**
 * ESPECIFICAÇÃO TDD - Integração API de Contato
 * 
 * Este arquivo define o comportamento esperado da comunicação entre o Frontend Nuxt 
 * e o Backend WordPress para o formulário de contato.
 */

describe('TDD: Comunicação HTTP - Form de Contato', () => {
  // Configuração simulando o ambiente de desenvolvimento (Docker)
  const devConfig = {
    public: {
      wpApiUrl: 'http://localhost:8080/wp-json/wp/v2/'
    }
  }

  const { submitContactForm } = useWordPress(devConfig as any)

  // --- CENÁRIO 1: CAMINHO FELIZ ---
  it('Deve processar com sucesso uma mensagem válida (Caminho Feliz)', async () => {
    const validData = {
      name: 'João Silva',
      email: 'joao@exemplo.com',
      whatsapp: '(11) 99999-9999',
      message: 'Olá, gostaria de saber mais sobre as aulas de dança.'
    }

    const response = await submitContactForm(validData)

    expect(response).toBeDefined()
    expect(response).toHaveProperty('message')
    // A API deve confirmar o recebimento
    expect(typeof response.message).toBe('string')
  })

  // --- CENÁRIO 2: VALIDAÇÃO DE NEGÓCIO ---
  it('Deve retornar erro ao enviar um e-mail com formato inválido', async () => {
    const invalidData = {
      name: 'Usuário Errado',
      email: 'email-sem-arroba.com',
      whatsapp: '(11) 99999-9999',
      message: 'Tentativa de envio com e-mail inválido.'
    }

    // No TDD, esperamos que a promessa seja rejeitada com erro de validação (400 ou similar)
    await expect(submitContactForm(invalidData)).rejects.toThrow()
  })

  // --- CENÁRIO 3: CAMPOS OBRIGATÓRIOS ---
  it('Deve retornar erro quando campos obrigatórios estão ausentes', async () => {
    const incompleteData = {
      name: '',
      email: 'teste@exemplo.com',
      whatsapp: '',
      message: ''
    }

    await expect(submitContactForm(incompleteData as any)).rejects.toThrow()
  })
})
