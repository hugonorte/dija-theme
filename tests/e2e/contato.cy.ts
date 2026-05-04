describe('Página de Contato E2E', () => {
  beforeEach(() => {
    cy.visit('/contato', { timeout: 60000 })
    // Garante que o Nuxt carregou e o formulário está pronto
    cy.window().should('have.property', '__NUXT__')
    cy.contains('Enviar Mensagem', { timeout: 20000 }).should('be.visible')
  })

  it('deve validar os campos ao clicar no botão', () => {
    // Scroll até o botão e clica sem force para respeitar os handlers Vue
    cy.contains('button', 'Enviar Mensagem').scrollIntoView().click()

    // Verifica se as mensagens de erro aparecem na página
    cy.contains('Nome é obrigatório', { timeout: 10000 }).should('be.visible')
    cy.contains('Email inválido', { timeout: 10000 }).should('be.visible')
    cy.contains('Mensagem é obrigatória', { timeout: 10000 }).should('be.visible')
  })

  it('deve enviar o formulário com sucesso', () => {
    cy.intercept('POST', '**/wp-json/dija/v1/contact', {
      statusCode: 200,
      body: { message: 'OK' }
    }).as('submit')

    cy.get('input[name="name"]').type('Teste')
    cy.get('input[name="email"]').type('teste@teste.com')
    cy.get('textarea[name="message"]').type('Mensagem de teste')

    cy.contains('button', 'Enviar Mensagem').scrollIntoView().click()

    cy.wait('@submit', { timeout: 10000 })
    cy.contains('Sucesso!', { timeout: 10000 }).should('be.visible')
  })
})
