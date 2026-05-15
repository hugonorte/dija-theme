<script setup lang="ts">
const links = [
  { label: 'A Professora', to: '/bio' },
  { label: 'Eventos', to: '/eventos' },
  { label: 'Turmas', to: '/turmas' },
  { label: 'Aulas Particulares', to: '/aulas-particulares' },
  { label: 'Fotos', to: '/fotos' }
]
</script>

<template>
  <UHeader :links="links">
    <template #left>
      <NuxtLink
        to="/"
        class="flex items-center gap-2"
      >
        <AppLogo class="w-auto h-8 shrink-0" />
        <span class="font-black text-xl tracking-tighter text-primary">DIJA</span>
      </NuxtLink>
    </template>

    <template #right>
      <UColorModeButton />

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
  }
}

/* Estilos específicos para o Header se necessário */
</style>

