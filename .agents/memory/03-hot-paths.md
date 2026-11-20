---
name: Hot Paths & Critical Files
description: Frequently modified files, stable core areas, and change impact zones for the DIJA site
type: reference
source: Extracted from memory system for .agents/memory/
---

# Hot Paths & Critical Files — Quick Reference (DIJA)

## High-Frequency Zones (Change Often)
- `wp-theme/template-parts/` — section markup (hero, turmas, eventos, sobre, contato).
- `wp-theme/assets/styles.css` — all styles (desktop first, mobile block at the end).
- `wp-theme/inc/helpers.php` — default texts (`dija_defaults()`).

## Stable Core (Rarely Change)
- `wp-theme/inc/contact.php` — contact REST endpoint, spam protection, SMTP.
- `wp-theme/inc/content-types.php` — CPT and meta registration (changing meta keys orphans saved data).
- `docker-compose.yml` — versions pinned to production.

## Impact Zones (Changes Break Multiple Places)
- CSS tokens and masks at the top of `styles.css` (`--u`, `--tear`, `--mask-*`, `--icon-*`).
- Field definitions in `dija_fields()` — used by meta boxes, sanitizing, REST and templates.
- Customizer keys — renaming loses the professor's saved texts.

## Red Flags (Likely to Cause Issues)
- Renaming `dija_*` meta/theme_mod keys (silently drops saved content).
- Absolute positioning for editable content (breaks with longer texts) — keep sections in flow.
- Using GRID (project rule: FLEX only).
- Deploy: files left in `public_html/` root (old static HTML) would shadow WordPress routes.
