<?php

namespace Src\QR\Application\DTO;

class GenerateQRDTO
{
    public function __construct(
        public readonly string $text,
        public readonly int $size = 200,
        public readonly ?string $foregroundColor = null,
        public readonly ?string $backgroundColor = null,
        public readonly ?string $logoPath = null,
        public readonly ?int $logoResizeToWidth = null,
        public readonly string $errorCorrectionLevel = 'medium',
        public readonly int $margin = 10,
        public readonly string $roundBlockSizeMode = 'margin',
    ) {}
}
