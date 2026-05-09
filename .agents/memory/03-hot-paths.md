---
name: Hot Paths & Critical Files
description: Frequently modified files, stable core areas, and change impact zones for DIJA Blog
type: reference
source: Extracted from memory system for .agents/memory/
---

# Hot Paths & Critical Files — Quick Reference (DIJA Blog)

## High-Frequency Zones (Change Often)

These files/dirs change frequently and should be understood when context is needed:

### Pages
- `app/pages/` — Blog routes, post viewing, and archive pages.
- Expect modifications when introducing new types of content or optimizing SEO tags for specific pages.

### Components
- `app/components/` — Nuxt UI wrapped components.
- Heavily modified files include layouts, forms, and cards displaying blog posts.

### WordPress API Integrations
- `server/api/` or `app/composables/` handling the WordPress fetching logic.

### Tests
- `*.spec.ts` files (Vitest) — Added/updated with features under TDD.
- `cypress/` tests (Cypress) — E2E test coverage for forms and critical user flows.

## Stable Core (Rarely Change)

These are unlikely to need modification:
- `package.json` — Nuxt UI and other dependencies.
- `vitest.config.ts` — Vitest setup stable.
- `cypress.config.ts` — Cypress setup stable.

## Impact Zones (Changes Break Multiple Places)

Editing these requires careful consideration of side effects:
- `nuxt.config.ts` — Affects Nuxt UI, global SCSS, SEO modules, and routing.
- Global SCSS files in `app/assets/scss/` — Because we rely on SCSS for all styling, changes here can impact the entire site.
- Shared Nuxt UI layout wrappers.

## Red Flags (Likely to Cause Issues)

When editing these, slow down and test thoroughly:
- **SCSS Flexbox Layouts**: Ensure responsive testing since we do not use Tailwind Grid.
- **Form components using Vee-Validate**: Make sure `defineField` is strictly used to avoid readonly proxy errors.
- **SEO tags**: Changing meta configurations can impact search rankings, verify any changes with `<Head>` or `useSeoMeta()`.
