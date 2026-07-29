<?php

namespace Src\Pages\Application\DTO;

class SavePageDTO
{
    public function __construct(
        public readonly string $title,
        public readonly array $elements,
        public readonly string $slug,
        public readonly array $canvases = [],
        public readonly string $background = '#ffffff',
        public readonly ?int $id = null,
        public readonly ?int $userId = null,
    ) {}
}
