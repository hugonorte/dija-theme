<template>
  <UFooter>
    <template #left>
      <div class="flex flex-col gap-1">
        <p class="text-sm font-medium text-primary">
          DIJA - Ritmo, Dança e Tradição
        </p>
        <p class="text-xs text-muted">
          © {{ new Date().getFullYear() }} Professora Dija Ferreira. Todos os direitos reservados.
        </p>
      </div>
    </template>

    <template #right>
      <div class="flex items-center gap-4">
        <NuxtLink
          to="/privacidade"
          class="text-xs text-muted hover:text-primary transition-colors"
        >
          Privacidade
        </NuxtLink>
        <NuxtLink
          to="/termos"
          class="text-xs text-muted hover:text-primary transition-colors"
        >
          Termos
        </NuxtLink>
        <NuxtLink
          to="/contato"
          class="text-xs text-muted hover:text-primary transition-colors"
        >
          Contato
        </NuxtLink>

        <div class="flex items-center ml-2 gap-1">
          <UButton
            icon="i-lucide-play-circle"
            variant="ghost"
            color="neutral"
            size="xs"
          />
          <UButton
            icon="i-lucide-share-2"
            variant="ghost"
            color="neutral"
            size="xs"
          />
        </div>
      </div>
    </template>
  </UFooter>
</template>
