---
name: Code Conventions & Patterns
description: Naming, testing conventions, and code style for the DIJA WordPress theme
type: reference
source: Extracted from memory system for .agents/memory/
---

# Code Conventions & Patterns — Quick Reference (DIJA)

## Styling Conventions
- **Plain CSS**: All styles in `wp-theme/assets/styles.css`, with custom properties in `:root`. No frameworks or preprocessors.
- **Fonts**: sizes with `clamp()` for smooth scaling between breakpoints.
- **Flexbox Only**: Always use FLEX for alignments. Never use GRID.
- **Responsive**: UI must adapt gracefully to smartphones, tablets, notebooks, and desktops.
- **Premium Aesthetics**: The interface should be attractive, intuitive, modern, and pleasant.

## Naming Conventions & Language
- **Variables/Constants/Functions**: Name them in **English**. PHP: snake_case with the `dija_` prefix (WordPress standard). JS: camelCase.
- **User-Facing Text**: Must always be in **Brazilian Portuguese (pt-BR)**.
- **Agent Responses**: The AI agent must always respond in Brazilian Portuguese.

## Verification
- `php -l` on every changed PHP file (CI runs it with PHP 8.5).
- Check the page in the local Docker site (desktop and phone widths) after layout changes.
- `wp-content/debug.log` must stay empty (WP_DEBUG_LOG is on locally).

## Form & Validation
- **Contact form**: posts JSON to `/wp-json/dija/v1/contact`; keep the `fax_number` honeypot field.
- **Security**: Sanitize input, escape output (`esc_html`, `esc_attr`, `esc_url`), nonce + `current_user_can` on saves.

## Code Quality
- **DRY Principle**: Don't Repeat Yourself. Always suggest/create helper functions or services instead of duplicating logic.
- **Small Releases & CI**: Perform small releases whenever possible and work with Continuous Integration.
