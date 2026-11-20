<script setup lang="ts">
const links = [
  { label: 'Sobre a professora', to: '/bio' },
  { label: 'Turmas', to: '/turmas' },
  { label: 'Notícias', to: '/noticias' },
  { label: 'Eventos', to: '/eventos' },
  { label: 'Fotos e vídeos', to: '/midias' },
  { label: 'Contato', to: '/contato' }
]
</script>

<template>
  <UHeader :links="links" class="fixed-header">
    <template #left>
      <NuxtLink
        to="/"
        class="brand-link"
      >
        <span class="brand-logo">
          Dija Ferreira
        </span>
      </NuxtLink>
    </template>

    <UNavigationMenu :items="links" class="desktop-nav" />

    <template #right>
      <ColorModeButton />

      <UButton
        to="https://instagram.com/dijaina"
        target="_blank"
        icon="i-simple-icons-instagram"
        aria-label="Instagram"
        color="neutral"
        variant="ghost"
      />
    </template>

    <template #body>
      <div class="mobile-menu-container">
        <UNavigationMenu
          :items="links"
          variant="link"
          orientation="vertical"
          class="mobile-nav"
        />
        <div class="cta-container">
          <UButton
            to="/contato"
            label="Entre em Contato"
            size="xl"
            block
            class="cta-button"
          />
        </div>
      </div>
    </template>
  </UHeader>
</template>

<style lang="scss">
.fixed-header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 50;
  background-color: var(--color-background);
  border-bottom: 1px solid var(--color-border);
  transition: background-color var(--transition-default);
}

.brand-link {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.brand-logo {
  font-family: var(--font-primary);
  font-size: 2rem;
  letter-spacing: -0.02em;
  font-weight: 700;
  color: var(--color-text-primary);
  text-transform: uppercase;
}

.desktop-nav {
  display: none;
  
  @media (min-width: 1024px) {
    display: flex;
  }
}

.mobile-menu-container {
  display: flex;
  flex-direction: column;
  gap: 32px;
  padding: 16px 8px;
}

.mobile-nav {
  width: 100%;
  
  a, span {
    font-family: var(--font-secondary);
    font-size: 1.2rem !important;
    font-weight: 500;
    color: var(--color-text-primary) !important;
    text-transform: uppercase;
    transition: color var(--transition-fast);

    &:hover, &:focus, &.router-link-active {
      color: var(--color-brand) !important;
    }
  }
}

.cta-container {
  display: flex;
  padding: 16px 0;
  margin-top: 16px;
}

.cta-button {
  font-weight: 600 !important;
  border-radius: var(--radius-default) !important;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  font-size: 0.9rem !important;
  font-family: var(--font-secondary);
  background: var(--color-brand) !important;
  color: var(--color-white) !important;
}
</style>

