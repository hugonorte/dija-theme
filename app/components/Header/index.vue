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
      <UNavigationMenu
        :items="links"
        variant="link"
        orientation="vertical"
        class="w-full mobile-nav"
      />
    </template>
  </UHeader>
</template>

<style lang="scss">
.mobile-nav {
  a, span {
    font-size: 1.4rem !important;
  }
}

/* Estilos específicos para o Header se necessário */
</style>
