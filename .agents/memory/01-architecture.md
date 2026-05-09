---
name: Project Architecture
description: Nuxt structure, component organization, and key patterns for DIJA Blog
type: reference
source: Extracted from memory system for .agents/memory/
---

# Project Architecture — Quick Reference (DIJA Blog)

## Overview
This is the frontend for the DIJA Blog (https://dija.com.br/), providing content about ballroom dance teacher Dijaina Ferreira.
- **Frontend**: Nuxt
- **UI Module**: Nuxt UI
- **Backend/CMS**: WordPress (Headless API integration)

## Directory Structure

```
app/
├── components/          # Vue 3 components organized by feature
├── composables/         # Reusable logic (DRY principle)
├── layouts/             # Root layout templates (Nuxt UI based)
├── middleware/          # Route guards
├── pages/               # File-based routing
│   └── index.vue        # Home
├── plugins/             # Vue plugins and initialization
├── assets/
│   ├── scss/            # Global SCSS files
│   └── img/             # Static images
└── app.vue              # Root component

server/                   # Nitro backend / API endpoints
public/                  # Static assets (favicon, etc.)
```

## Component Organization
- Separate code into reusable components.
- Use Nuxt UI components and scripts.
- DRY philosophy: Create helpers/services instead of repeating code.

## SEO Architecture (CRITICAL)
- **Extremely Important**: SEO is a core requirement for this blog.
- Layouts, components, and pages must use semantic HTML.
- Proper meta tags, structured data, and image optimization must be implemented globally.

## Backend Integration
- The frontend fetches data from and sends data to the WordPress REST API/GraphQL.
- Form scripts must be secure and communicate reliably with WordPress.
