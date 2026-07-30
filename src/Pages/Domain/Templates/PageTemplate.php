<?php

namespace Src\Pages\Domain\Templates;

/**
 * Contract that every page template MUST implement.
 *
 * A template describes a multi-card composition that any authenticated user
 * can instantiate with a single click. The first canvas is conventionally a
 * hidden "business card" whose QR points to the freshly-created page; the
 * registry takes care of injecting that QR after the page's UUID is known.
 *
 * See TEMPLATES.md for the canonical documentation.
 */
interface PageTemplate
{
    /** Stable identifier used in URLs (e.g. "birthday-invitation"). */
    public function id(): string;

    /** Human-readable name shown in the UI. */
    public function name(): string;

    /** One-line description shown under the name. */
    public function description(): string;

    /** Emoji / icon glyph — purely decorative. */
    public function emoji(): string;

    /** Default title for a page instantiated from this template. */
    public function defaultTitle(): string;

    /**
     * Returns the canvases that compose this template.
     *
     * Convention:
     *   - First canvas: HIDDEN business card (visible=false, small size).
     *     Leave its `elements` empty; the registry adds the QR.
     *   - Subsequent canvases: VISIBLE public cards (the actual content).
     *
     * @return array<int, array{background: string, width: int, height: int, visible: bool, elements: array<int, array<string, mixed>>}>
     */
    public function canvases(): array;
}
