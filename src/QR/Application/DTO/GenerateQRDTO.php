<?php

namespace Src\QR\Application\DTO;

class GenerateQRDTO
{
    public function __construct(
        public readonly string $text,
        public readonly int $size = 200,
    ) {}
}
