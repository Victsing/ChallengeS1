describe('template spec', () => {
  it('Enter to the app', () => {
    cy.origin('http://localhost:3000', () => {

      cy.visit('/')
      cy.contains('Se connecter').click()
      cy.contains('S\'inscrire').click()
      cy.contains('Mot de passe oublié').click()
      /*
      login as user set credentials
      cy.get("[type='email']").type(Cypress.env(process.env.USER_LOGIN))
      cy.get("[type='password']").type(Cypress.env(process.env.USER_PWD))
      cy.get("[type='submit']").click()*/
    })
  })
})