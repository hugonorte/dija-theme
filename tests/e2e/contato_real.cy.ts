describe('Teste de Interação Real com WordPress', () => {
  it('deve preencher o formulário e enviar para o WordPress real', () => {
    // 1. Visita a página de contato
    cy.visit('/contato')

    // 2. Preenche os campos com dados identificáveis
    const timestamp = new Date().getTime()
    const testName = `Teste Automatizado ${timestamp}`
    const testEmail = `teste-${timestamp}@exemplo.com`
    const testMessage = `Mensagem enviada via Cypress em ${new Date().toLocaleString()}. Favor verificar no admin do WordPress.`

    cy.get('#contact-name', { timeout: 10000 }).should('be.visible').type(testName)
    cy.get('#contact-email').type(testEmail)
    cy.get('#contact-whatsapp').type('11999999999') // Digita apenas números para testar a máscara
    cy.get('#contact-message').type(testMessage)

    // 3. Envia o formulário
    // Nota: Não usamos cy.intercept aqui para que a requisição vá para o Docker
    cy.contains('button', 'Enviar Mensagem').click()

    // 4. Verifica se o toast de sucesso aparece
    // O tempo de espera pode ser um pouco maior dependendo da velocidade do container
    cy.contains('Sucesso!', { timeout: 15000 }).should('be.visible')
    cy.contains('Mensagem enviada.', { timeout: 15000 }).should('be.visible')

    // 5. Opcional: Log de sucesso no console do Cypress
    cy.log('Formulário enviado com sucesso para o WordPress!')
  })
})
