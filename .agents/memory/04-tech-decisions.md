---
name: Technical Decisions & Rationale
description: Why specific tools/patterns were chosen and constraints to respect for the DIJA site
type: reference
source: Extracted from memory system for .agents/memory/
---

# Technical Decisions & Rationale — Quick Reference (DIJA)

## Architecture
- **Classic WordPress PHP theme (replaced Nuxt headless, Nov 2026)**: content edited in the panel appears immediately, without rebuild/deploy; simpler deploy on Hostinger shared hosting; SEO equal to SSG (server-rendered HTML).
- **No plugins required**: avoids surprises with plugin/WordPress updates. Only stable native APIs.

## Content Editing
- **CPTs with fixed fields** (turma, evento) instead of free block editing: the professor cannot break the layout.
- **Customizer** for single texts/photos, with defaults so sections are never empty.
- CPTs live in the theme (trade-off: switching themes hides them from the panel; data stays in the DB).

## Styling & Layout
- **Plain CSS** in one file with custom properties; no preprocessors, no frameworks.
- **Flexbox ONLY** — Grid is strictly prohibited.
- Proportional scale (`--u`) on desktop up to 4K; `clamp()` for fonts; stacked layout below 768px.

## Environment & Deploy
- Docker pinned to production versions (WordPress 7.1.5 + PHP 8.5); core auto-update disabled locally.
- GitHub Actions: PHP lint + rsync of `wp-theme/` to the theme folder only.

## Critical Constraints (Never Violate)
1. ✅ **Flexbox only** — never use Grid.
2. ✅ **Escape output, sanitize input** — nonce and capability checks on every save.
3. ✅ **English for code, PT-BR for UI**.
4. ✅ **SEO first** — semantic HTML, one h1 per page.
5. ✅ **Content without deploy** — never hardcode content the professor should edit.
6. ✅ **Native APIs only** — no required plugins, no core changes.
