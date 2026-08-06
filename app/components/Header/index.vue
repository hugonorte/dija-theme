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
        class="flex items-center gap-2"
      >
        <span class="text-6xl tracking-tighter text-primary" style="font-family: 'Damion', cursive; font-weight: 400;">
          Dija Ferreira
        </span>
      </NuxtLink>
    </template>

    <UNavigationMenu :items="links" class="hidden lg:flex" />

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
          class="w-full mobile-nav"
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
.mobile-menu-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  padding: 1rem 0.5rem;
}

.cta-container {
  display: flex;
  padding: 1rem 0;
  margin-top: 1rem;
}

.cta-button {
  font-weight: 800 !important;
  border-radius: 999px !important;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.9rem !important;
}

.mobile-nav {
  a, span {
    font-size: 1.4rem !important;
    color: var(--color-text-primary) !important;
    transition: color 0.2s ease;

    &:hover, &:focus, &.router-link-active {
      color: var(--color-primary) !important;
    }
  }
}

/* Estilos específicos para o Header se necessário */
.fixed-header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 50;
  background-color: var(--glass-bg);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--glass-border);
  transition: var(--transition-smooth);
}
</style>

