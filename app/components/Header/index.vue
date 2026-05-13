<script setup lang="ts">
const links = [
  { label: 'A Professora', to: '/a-professora' },
  { label: 'Artigos', to: '/artigos' },
  { label: 'Agenda', to: '/agenda' },
  { label: 'Vídeos', to: '/videos' },
  { label: 'Comunidade', to: '/comunidade' }
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

    <template #content>
      <UNavigationMenu
        :items="links"
        variant="link"
      />
    </template>
  </UHeader>
</template>

<style lang="scss" scoped>
/* Estilos específicos para o Header se necessário */
</style>
