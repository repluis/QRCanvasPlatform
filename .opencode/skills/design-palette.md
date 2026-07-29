# Design Palette & Frontend Architecture

## Palette Config
`config/palette.php` — Single source of truth. Add/edit colors here.

## CSS Variables
Set in `resources/views/app.blade.php` inside `<style>:root { ... }`  
Read from `config('palette.*')` so they're always in sync.

## Tailwind Colors
`resources/css/app.css` defines `@theme` with same values — use classes:
```
bg-surface    text-primary    border-border
bg-bg         text-text       border-border-light
bg-surface-alt                from-primary to-accent
```

## Frontend Architecture

```
resources/js/
├── layouts/
│   └── MainLayout.vue          # Persistent layout (Header + slot + Footer)
├── components/
│   ├── layout/
│   │   ├── AppHeader.vue       # Top bar: logo, nav links, user menu, logout
│   │   ├── AppSidebar.vue      # Side panel: page list, new page button
│   │   └── AppFooter.vue       # Simple centered footer
│   └── ui/
│       ├── BaseButton.vue      # Variants: primary, secondary, ghost, danger
│       ├── BaseCard.vue        # Padding: sm, md, lg; optional hover
│       └── BaseInput.vue       # Styled input with label, error state
├── composables/
│   └── useTheme.js             # color(name) → hex, allColors() → object
├── Pages/                      # Page-specific components
└── app.js                      # Auto-apply MainLayout as default layout
```

## Usage in Pages
```vue
<script setup>
import BaseButton from '../../components/ui/BaseButton.vue'
import BaseCard from '../../components/ui/BaseCard.vue'
</script>

<template>
    <BaseCard>
        <h1 style="color: var(--text)">Title</h1>
        <p style="color: var(--text-muted)">Subtitle</p>
        <BaseButton variant="primary">Action</BaseButton>
    </BaseCard>
</template>
```

To skip MainLayout on a page: `defineOptions({ layout: null })`

## Inspecting Current Colors
```js
import { useTheme } from '@/composables/useTheme'
const { allColors } = useTheme()
console.log(allColors())
```
