---
name: Technical Decisions & Rationale
description: Why specific tools/patterns were chosen and constraints to respect for DIJA Blog
type: reference
source: Extracted from memory system for .agents/memory/
---

# Technical Decisions & Rationale — Quick Reference (DIJA Blog)

## Framework & UI
- **Nuxt**: Chosen for its robust SSR capabilities, auto-routing, and Vue 3 foundation.
- **Nuxt UI**: The primary module for UI templates. Provides accessible, customizable components out of the box.

## Styling & Layout
- **SCSS**: Primary styling language.
- **Flexbox**: EXCLUSIVELY used for layout alignments. Grid is strictly prohibited.
- **Tailwind Restrictions**: Tailwind classes should NEVER be used directly in the code, except in the absolute absence of alternatives. This ensures the codebase relies on organized SCSS and Flexbox rules.

## Backend Integration
- **WordPress Headless CMS**: The backend is WordPress. The Nuxt frontend fetches data from it.
- **Rationale**: Familiarity for the client (blog management), while retaining modern frontend performance via Nuxt.

## Forms & Validation
- **Vee-Validate**: Used for form validation.
- **Constraint (`defineField`)**: Must always use `defineField` from `useForm` when integrating Vee-Validate to avoid proxy "readonly" errors with Vue `v-model`.

## Testing Framework
- **Vitest**: Used for Unit/Component tests.
- **Cypress**: Used for End-to-End (E2E) testing.
- **Rationale**: TDD approach demands fast unit tests (Vitest) and reliable browser tests (Cypress).

## SEO & Accessibility
- **MUITO IMPORTANTE**: SEO is heavily prioritized. Code must be highly semantic, and meta tags must accurately reflect content. This is a blog, so organic search ranking is paramount.

## Deployment & Content Strategy (Hybrid Architecture)
- **Decoupled Workflow**: Core pages (Home, Contato, etc.) use **SSG** for maximum performance and SEO. Blog content uses **Dynamic Runtime Fetching (SPA fallback)**.
- **Rationale**: Allows users to publish WordPress posts instantly without triggering GitHub Actions. This avoids technical coupling and optimizes Hostinger shared resources.
- **Deployment**: Assets are isolated in the theme folder (`cdnURL`), while HTML files are served from the root.

## Code Quality & Architecture
- **DRY Principle**: Always abstract repetitive code into helpers, composables, or services.
- **Componentization**: Split logic into reusable UI components for maintainability.

## Critical Constraints (Never Violate)
1. ✅ **No Tailwind Classes** — rely on `.scss` and Nuxt UI components.
2. ✅ **Use Flexbox ONLY** — never use Grid for alignment.
3. ✅ **TDD Workflow** — write/update tests before writing the implementation.
4. ✅ **Vee-Validate strict usage** — `defineField` is mandatory.
5. ✅ **English for Code, PT-BR for UI** — strict language boundaries.
6. ✅ **SEO First** — never compromise semantic HTML and meta tags.
7. ✅ **Decoupled Content** — WordPress posts must not require a code rebuild to be visible.
