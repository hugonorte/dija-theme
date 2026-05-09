---
name: Code Conventions & Patterns
description: Naming, import patterns, testing conventions, and code style for DIJA Blog
type: reference
source: Extracted from memory system for .agents/memory/
---

# Code Conventions & Patterns — Quick Reference (DIJA Blog)

## Styling Conventions
- **No Tailwind Classes**: Never use Tailwind classes directly in the template, unless there is absolutely no other option.
- **SCSS Only**: Use `.scss` code for CSS styles.
- **Flexbox Only**: Always use FLEX for alignments. Never use GRID.
- **Responsive**: UI must adapt gracefully to smartphones, tablets, notebooks, and desktops.
- **Premium Aesthetics**: The interface should be attractive, intuitive, modern, and pleasant.

## Naming Conventions & Language
- **Variables/Constants/Functions**: Name them in **English**, following standard JS community guidelines (e.g., camelCase for variables/functions, SCREAMING_SNAKE_CASE for constants).
- **User-Facing Text**: Must always be in **Brazilian Portuguese (pt-BR)**.
- **Agent Responses**: The AI agent must always respond in Brazilian Portuguese.

## Testing Conventions (TDD)
- **Methodology**: Test-Driven Development (TDD).
- **Workflow**: 
  1. Create test first (Vitest for unit, Cypress for E2E).
  2. Run the test (it should fail).
  3. Create the main code.
  4. Run the test again (it should pass).
- **Refactoring**: When refactoring, rewrite the corresponding test and run it to ensure everything works.

## Form & Validation
- **Vee-Validate**: When using `vee-validate`, you must use the `defineField` function from `useForm` to generate mutable refs compatible with `v-model`. This avoids "readonly" errors in `form.values`.
- **Security**: Form scripts must be secure to prevent spam and vulnerabilities (e.g., honeypots).

## Code Quality
- **DRY Principle**: Don't Repeat Yourself. Always suggest/create helper functions or services instead of duplicating logic.
- **Small Releases & CI**: Perform small releases whenever possible and work with Continuous Integration.
