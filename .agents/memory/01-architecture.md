---
name: Project Architecture
description: WordPress PHP theme structure, content model and key patterns for the DIJA site
type: reference
source: Extracted from memory system for .agents/memory/
---

# Project Architecture — Quick Reference (DIJA)

## Overview
Site for ballroom dance teacher Dijaina (Dija) Ferreira (https://dija.com.br/).
- **CMS + rendering**: WordPress (server-rendered, no headless frontend, no build)
- **Theme**: classic PHP theme in `wp-theme/` (deployed as `wp-content/themes/dija-theme/`)
- **No required plugins**: native APIs only

## Directory Structure

```
wp-theme/
├── functions.php          # Loads inc/*
├── inc/
│   ├── helpers.php        # Defaults (dija_defaults), dija_mod, image/link helpers
│   ├── setup.php          # Theme supports, enqueue, nav items, favicon
│   ├── content-types.php  # CPTs turma/evento + meta fields + meta boxes
│   ├── customizer.php     # "Conteúdo do site" panel
│   └── contact.php        # REST dija/v1/contact, contact_message CPT, SMTP
├── template-parts/        # section-hero/turmas/eventos/sobre/contato, turma-card, evento-media
├── front-page.php, archive-turma.php, archive-evento.php,
│   page-bio.php, page-contato.php, page.php, single.php, index.php, 404.php
└── assets/                # styles.css, app.js, img/ (AVIF + fallbacks), icons/ (SVG sources)
scripts/                   # wp.sh (WP-CLI in Docker), seed-dev.sh
design-systems/            # Approved static HTML prototype (visual reference, not in git)
```

## Content Model
- `turma`: dija_dia, dija_horario, dija_ritmo, dija_nivel, dija_descricao, dija_cor, dija_icone, menu_order
- `evento`: title, dija_descricao, dija_botao_texto, dija_botao_link, featured image, menu_order
- Customizer settings `dija_*` for single texts/photos (defaults in `dija_defaults()`)

## Patterns
- Sections are reusable template parts; `$args['page']` switches heading level (h1 on its own page).
- Layout in flow (flexbox, min-heights) so editor content can grow; GRID is prohibited.
- `--u` CSS unit = 1px of the 1536px reference width; below 768px a stacked mobile layout.
- All output escaped; all input sanitized; nonce + capability checks on saves.

## SEO Architecture (CRITICAL)
- Semantic HTML, one `<h1>` per page, `title-tag` support, AVIF images with fallbacks.
