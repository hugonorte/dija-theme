describe('Header Mobile Menu CTA', () => {
  beforeEach(() => {
    // Set viewport to mobile
    cy.viewport('iphone-xr')
    cy.visit('/')
  })

  it('deve exibir o botão CTA no menu mobile e navegar para /contato', () => {
    // Abre o menu mobile
    // O UHeader do Nuxt UI costuma ter um botão de toggle
    cy.get('header button').last().click({ force: true })
    cy.wait(2000) // Aguarda a animação do menu

    // Verifica se o botão CTA está visível
    cy.contains('Entre em Contato', { timeout: 15000 }).should('be.visible')

    // Clica no botão e verifica a navegação
    cy.contains('Entre em Contato').click()
    cy.url().should('include', '/contato')
  })
})
