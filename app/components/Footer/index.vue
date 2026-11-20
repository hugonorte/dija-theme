<template>
  <UFooter class="site-footer">
    <template #left>
      <div class="footer-left-content">
        <p class="footer-brand">
          DIJA - Ritmo, Dança e Tradição
        </p>
        <p class="footer-copyright">
          © {{ new Date().getFullYear() }} Professora Dija Ferreira. Todos os direitos reservados.
        </p>
      </div>
    </template>

    <template #right>
      <div class="footer-links">
        <NuxtLink
          to="/privacidade"
          class="footer-link"
        >
          Privacidade
        </NuxtLink>
        <NuxtLink
          to="/termos"
          class="footer-link"
        >
          Termos
        </NuxtLink>
        <NuxtLink
          to="/contato"
          class="footer-link"
        >
          Contato
        </NuxtLink>

        <div class="footer-social">
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

<style lang="scss">
.site-footer {
  background: var(--color-black);
  border-top: 1px solid var(--color-border-dark);
  padding: 32px 24px;
}

.footer-left-content {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.footer-brand {
  font-family: var(--font-primary);
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--color-brand);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin: 0;
}

.footer-copyright {
  font-size: 0.85rem;
  color: var(--color-gray);
  margin: 0;
}

.footer-links {
  display: flex;
  align-items: center;
  gap: 24px;
  
  @media (max-width: 768px) {
    flex-wrap: wrap;
    margin-top: 24px;
  }
}

.footer-link {
  font-size: 0.85rem;
  color: var(--color-gray);
  text-decoration: none;
  text-transform: uppercase;
  transition: color var(--transition-fast);

  &:hover {
    color: var(--color-white);
  }
}

.footer-social {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-left: 16px;
  
  @media (max-width: 768px) {
    margin-left: 0;
  }
}
</style>

