# Templates System

QRCanvasPlatform supports **page templates** — pre-designed multi-card compositions
that any authenticated user can instantiate with one click. The first card of every
template is a **hidden QR business card**; the remaining cards are the public view.

This document explains how templates work, where they live, and how to create new ones.

---

## 1. Architecture (DDD)

Templates follow the project's Domain-Driven Design layout:

```
src/Pages/
├── Domain/
│   └── Templates/                       ← NEW
│       ├── PageTemplate.php             (interface / contract)
│       ├── TemplateRegistry.php         (service: lists all available templates)
│       └── Presets/                     (concrete templates — one file per template)
│           ├── DatingProposalTemplate.php
│           ├── BirthdayInvitationTemplate.php
│           ├── MarriageProposalTemplate.php
│           └── GraduationInvitationTemplate.php
├── Application/
│   ├── Actions/
│   │   └── CreatePageFromTemplateAction.php    ← NEW
│   └── DTO/
│       └── (reuses existing SavePageDTO)
└── Infrastructure/
    ├── Controllers/
    │   └── TemplateController.php              ← NEW
    └── Routes/
        └── pages.php                           ← adds /templates + /pages/from-template
```

Templates are **discovered automatically** by `TemplateRegistry::all()` which reads
files in `Presets/`. To add a new template, drop a file in that folder — no registration step.

---

## 2. The `PageTemplate` contract

```php
namespace Src\Pages\Domain\Templates;

interface PageTemplate
{
    /** Unique stable id, used in URLs (e.g. "birthday-invitation"). */
    public function id(): string;

    /** Display name shown in the UI (e.g. "Invitación de Cumpleaños 🎂"). */
    public function name(): string;

    /** Short description shown under the template name. */
    public function description(): string;

    /** Emoji or icon key — anything stringy; the UI treats it as decorative. */
    public function emoji(): string;

    /**
     * Returns the static part of the template: canvases WITHOUT the QR.
     * The QR is added at instantiation time because it must encode the
     * freshly-generated page UUID.
     *
     * Each canvas MUST be shaped as:
     *   ['background' => string, 'width' => int, 'height' => int,
     *    'visible' => bool, 'elements' => ElementShape[]]
     */
    public function canvases(): array;

    /** Title given to a page instantiated from this template. */
    public function defaultTitle(): string;
}
```

---

## 3. Element shapes

The frontend renders any canvas via `Pages/Views/Show.vue` and the editor via
`CanvasElement.vue` / `EditorCanvas.vue`. Elements MUST follow one of these shapes:

### 3.1 `text`

```php
[
    'id' => 'unique-string',
    'type' => 'text',
    'x' => 50, 'y' => 100, 'width' => 700, 'height' => 80,   // bounding box, px
    'content' => 'Hello world',
    'fontSize' => 32,
    'fontWeight' => 'normal' | 'bold',
    'fontStyle' => 'normal' | 'italic',
    'textDecoration' => 'none' | 'underline',
    'textAlign' => 'left' | 'center' | 'right',
    'fontFamily' => 'Georgia, serif',                          // any CSS font-family
    'color' => '#1f2937',                                      // hex / rgb / hsl
]
```

### 3.2 `shape`

```php
[
    'id' => 'unique-string',
    'type' => 'shape',
    'shape' => 'heart' | 'star' | 'circle' | 'moon' | 'diamond'
              | 'triangle' | 'hexagon' | 'cloud'
              | 'arrow-right' | 'arrow-left' | 'arrow-up' | 'arrow-down'
              | 'cross' | 'plus' | 'check' | 'lightning',
    'x' => ..., 'y' => ..., 'width' => ..., 'height' => ...,
    'color' => '#ef4444',
]
```

The SVG path for each shape is defined in
`resources/js/Pages/Pages/Composables/useCanvas.js` (and duplicated in
`resources/js/Pages/Pages/Views/Show.vue`). If you add a new shape you must
add its `viewBox`+`path` in **both** places.

### 3.3 `image`

```php
[
    'id' => 'unique-string',
    'type' => 'image',
    'x' => ..., 'y' => ..., 'width' => ..., 'height' => ...,
    'content' => 'https://example.com/photo.jpg',   // any URL the browser can load
]
```

### 3.4 `qr`

The QR element is special: it has **two** URL fields and is generated at
instantiation time. Its static shape (before the registry fills in the URL) is:

```php
[
    'id' => 'unique-string',
    'type' => 'qr',
    'x' => ..., 'y' => ..., 'width' => 200, 'height' => 200,
    'content' => 'placeholder',                       // registry fills with real URL
    'qrImageUrl' => 'placeholder',                    // registry fills with quickchart URL
    'foregroundColor' => '#be185d',
    'backgroundColor' => '#ffffff',
    'errorCorrectionLevel' => 'low'|'medium'|'quartile'|'high',
]
```

The registry rewrites `content` and `qrImageUrl` after the page is saved and
its UUID is known.

---

## 4. The `TemplateRegistry`

```php
namespace Src\Pages\Domain\Templates;

class TemplateRegistry
{
    /** @return PageTemplate[] */
    public function all(): array;

    public function find(string $id): ?PageTemplate;
}
```

`all()` scans `__DIR__/Presets` for classes implementing `PageTemplate` and
returns them sorted by `id()`. `find()` returns a single template or `null`.

---

## 5. `CreatePageFromTemplateAction`

This action does the two-step "create then patch QR" dance that the original
script did inline. It is registered as a singleton in the DI container and
exposed via `POST /pages/from-template`.

```php
namespace Src\Pages\Application\Actions;

class CreatePageFromTemplateAction
{
    public function execute(string $templateId, int $userId): Page
    {
        $template = $this->registry->find($templateId);
        // ... validates ownership, builds canvases, generates UUID,
        // injects the QR URL pointing at the new page, calls SavePageAction.
    }
}
```

---

## 6. HTTP routes

```php
// src/Pages/Infrastructure/Routes/pages.php
Route::middleware('auth')->group(function () {
    Route::get('/templates', [TemplateController::class, 'index'])
        ->name('templates.index');

    Route::post('/pages/from-template/{template}', [TemplateController::class, 'create'])
        ->name('pages.from-template');
});
```

- `GET /templates` returns a JSON list of templates (id, name, description, emoji).
- `POST /pages/from-template/{template}` creates a new page owned by `auth()->user()`
  and returns the same `buildPageResponse()` payload as `POST /pages`.

---

## 7. UI integration

`resources/js/Pages/Home.vue` now renders two sections:

1. **Templates** (top): a grid of cards fetched from `/templates`. Each card
   shows emoji + name + description + a "Use template" button that POSTs to
   `/pages/from-template/{id}` and then navigates to the editor.

2. **Your pages** (bottom): unchanged.

The templates section is identical for every user — these are global presets.

---

## 8. Adding a new template

1. Create a file in `src/Pages/Domain/Templates/Presets/`, e.g.
   `WeddingAnniversaryTemplate.php`.
2. Implement the four methods: `id()`, `name()`, `description()`, `emoji()`,
   `defaultTitle()`, `canvases()`.
3. Make sure the **first canvas** has `visible => false` and dimensions sized
   for a small business card (e.g. `400 x 600`). Leave its `elements` empty —
   the registry will add the QR.
4. Done — the template appears on the home page automatically.

Example minimal template:

```php
<?php

namespace Src\Pages\Domain\Templates\Presets;

use Src\Pages\Domain\Templates\PageTemplate;

class MyCustomTemplate implements PageTemplate
{
    public function id(): string { return 'my-custom'; }
    public function name(): string { return 'My Custom Page ✨'; }
    public function description(): string { return 'A quick starter template.'; }
    public function emoji(): string { return '✨'; }
    public function defaultTitle(): string { return 'My Custom Page'; }

    public function canvases(): array
    {
        return [
            // Card 1 — HIDDEN business card with QR (injected by registry)
            [
                'background' => '#ffffff',
                'width'  => 400,
                'height' => 600,
                'visible' => false,
                'elements' => [],
            ],
            // Card 2 — public title card
            [
                'background' => '#0f172a',
                'width'  => 800,
                'height' => 600,
                'visible' => true,
                'elements' => [
                    [
                        'id' => 't1',
                        'type' => 'text',
                        'x' => 50, 'y' => 200, 'width' => 700, 'height' => 200,
                        'content' => 'Hello!',
                        'fontSize' => 96, 'fontWeight' => 'bold',
                        'fontStyle' => 'normal', 'textDecoration' => 'none',
                        'textAlign' => 'center', 'fontFamily' => 'Georgia, serif',
                        'color' => '#f8fafc',
                    ],
                ],
            ],
        ];
    }
}
```

---

## 9. Manual / one-off creation (legacy script style)

You can still drop a PHP script in `database/seeders/` and run it once with
`php <script>.php` — this is the workflow used before the registry existed:

```php
<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Src\Pages\Application\Actions\SavePageAction;
use Src\Pages\Application\DTO\SavePageDTO;

// 1. Save with placeholder QR
$dto = new SavePageDTO(/* ... */);
$page = app(SavePageAction::class)->execute($dto);
$uuid = $page->getUuid();

// 2. Patch the QR with the real URL
$canvases[0]['elements'][0] = [
    /* ...same shape, but content and qrImageUrl point at the new uuid... */
];

// 3. Save again with $dto->id = $page->getId() to UPDATE, not INSERT
$dto2 = new SavePageDTO(/* ..., id: $page->getId() */);
app(SavePageAction::class)->execute($dto2);
```

`SavePageDTO::id` is the magic switch: set it → update, leave null → insert.

---

## 10. Why the two-step dance?

The QR code on card 1 must encode the URL of the page itself
(`http://localhost:8000/page?uuid=<uuid>`). But the UUID is generated by the
database **on save**, so we cannot know it until after the first `SavePageAction`.
Hence: save → read UUID → patch QR → save again.

The `CreatePageFromTemplateAction` wraps this pattern so the rest of the
codebase never has to think about it.
