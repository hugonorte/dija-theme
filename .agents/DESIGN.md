---
name: DIJA Editorial System
colors:
  surface: '#0b1326'
  surface-dim: '#0b1326'
  surface-bright: '#31394d'
  surface-container-lowest: '#060e20'
  surface-container-low: '#131b2e'
  surface-container: '#171f33'
  surface-container-high: '#222a3d'
  surface-container-highest: '#2d3449'
  on-surface: '#dae2fd'
  on-surface-variant: '#e4bdc2'
  inverse-surface: '#dae2fd'
  inverse-on-surface: '#283044'
  outline: '#ab888c'
  outline-variant: '#5b3f43'
  surface-tint: '#ffb2be'
  primary: '#ffb2be'
  on-primary: '#660025'
  primary-container: '#ff4e7c'
  on-primary-container: '#5a0020'
  inverse-primary: '#bc004b'
  secondary: '#bdc2ff'
  on-secondary: '#1b247f'
  secondary-container: '#343d96'
  on-secondary-container: '#a8afff'
  tertiary: '#ffb0c9'
  on-tertiary: '#5c1333'
  tertiary-container: '#d27394'
  on-tertiary-container: '#530b2c'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#ffd9de'
  primary-fixed-dim: '#ffb2be'
  on-primary-fixed: '#400014'
  on-primary-fixed-variant: '#900038'
  secondary-fixed: '#e0e0ff'
  secondary-fixed-dim: '#bdc2ff'
  on-secondary-fixed: '#000767'
  on-secondary-fixed-variant: '#343d96'
  tertiary-fixed: '#ffd9e2'
  tertiary-fixed-dim: '#ffb0c9'
  on-tertiary-fixed: '#3e001e'
  on-tertiary-fixed-variant: '#792b4a'
  background: '#0b1326'
  on-background: '#dae2fd'
  surface-variant: '#2d3449'
typography:
  headline-xl:
    fontFamily: Inter
    fontSize: 4rem
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.04em
  headline-lg:
    fontFamily: Inter
    fontSize: 2.5rem
    fontWeight: '600'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  body-md:
    fontFamily: Inter
    fontSize: 1.125rem
    fontWeight: '400'
    lineHeight: '1.7'
  label-sm:
    fontFamily: Inter
    fontSize: 0.875rem
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  gutter: 2rem
  margin-page: 5vw
  stack-sm: 1rem
  stack-md: 2.5rem
  stack-lg: 5rem
---

## Brand & Style

The design system for DIJA is built upon the intersection of rhythmic movement and high-end editorial aesthetics. It targets an audience that appreciates the technical mastery of ballroom dance while demanding a digital experience that feels as fluid and refined as a waltz. 

The visual style is a fusion of **Minimalism** and **Glassmorphism**. By utilizing translucent layers and background blurs, we create a sense of depth that mimics the atmospheric lighting of a ballroom floor. The interface is intentionally "airy," favoring whitespace to let high-quality photography of Dija Ferreira take center stage. The result is a Nuxt UI-inspired aesthetic: clean, technically precise, and unmistakably luxury.

## Colors

The palette is anchored by **Sophisticated Deep Pink** and **Deep Blue**, creating a high-contrast, emotive environment. We utilize a dark default mode to emphasize the premium nature of the brand and to make the glassmorphic effects more pronounced.

- **Primary (Deep Pink):** Used for calls to action, active states, and highlights that represent energy and passion.
- **Secondary (Deep Blue):** Used for background depths and structural elements, providing a grounded, professional foundation.
- **Subtle Gradients:** Backgrounds should transition from the Deep Blue (#1A237E) into a darker Slate (#0F172A) to create a sense of infinite space. Linear gradients at 135 degrees are preferred for interactive surfaces.

## Typography

This design system utilizes **Inter** exclusively to achieve a modern, utilitarian clarity that balances the vibrant color palette. Typography is treated with an editorial eye—extreme scale differences between headlines and body text create a rhythmic visual hierarchy.

Headlines use tight tracking and bold weights to command attention, while body text remains spacious with a generous line height for readability. Labels and "overlines" are set in uppercase with increased letter spacing to serve as architectural markers within the layout.

## Layout & Spacing

The layout philosophy is **Spaced Out and Flexbox-based**, moving away from rigid, boxy constraints toward a more fluid, dynamic arrangement. We use a 12-column grid for alignment, but components often break the grid or overlap to create a sense of movement.

Spacing is generous. We employ "breathable" margins (5vw) to ensure the content never feels cramped. Vertical rhythm is driven by large stack units (5rem) between sections, mimicking the pacing of a premium magazine.

## Elevation & Depth

Depth is conveyed through **Glassmorphism** and **Backdrop Blurs**. Instead of traditional drop shadows, we use:

- **Surface Translucency:** Containers have a semi-transparent background (e.g., `rgba(255, 255, 255, 0.05)`).
- **Backdrop Blur:** A minimum of 12px blur on the background layer behind floating elements.
- **Inner Borders:** 1px solid borders with low opacity (`rgba(255, 255, 255, 0.1)`) to define edges without adding visual weight.
- **Floating Headers:** The navigation bar remains fixed at the top, utilizing a high-saturation blur effect to let content scroll underneath it like a ghosted reflection.

## Shapes

The shape language is consistently **Rounded**, reflecting the soft curves and fluid motion of ballroom dance. Corner radii are set to 0.5rem (8px) for small components, while larger cards and section containers use 1.5rem (24px) to create a friendly yet sophisticated silhouette. Circular elements are used for avatars and play buttons to contrast against the structured grid.

## Components

- **Floating Headers:** Navigation items should have no background until scroll. Upon scroll, the header adopts a glassmorphic background with a thin bottom border in Deep Pink.
- **Dynamic Cards:** Blog cards should appear as frosted glass panels. On hover, the background opacity increases, and the Deep Pink accent border should glow using a soft outer shadow.
- **Buttons:** Primary buttons use a linear gradient (Deep Pink to a slightly lighter shade). They feature a subtle "lift" on hover with a 2px vertical translation.
- **Glass Chips:** Small tags for categories (e.g., "Tango," "Technique") should have a background blur and a 1px border.
- **Input Fields:** Search and contact inputs are ultra-minimal—transparent backgrounds with a simple bottom border that expands to a full Deep Pink outline on focus.
- **Video Overlays:** Since this is a dance blog, video thumbnails feature a centered, glass-style play button that expands slightly on hover.