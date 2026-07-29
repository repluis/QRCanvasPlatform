<?php

namespace Src\QR\Domain\ValueObjects;

use Src\Shared\Domain\ValueObject;

class QRText extends ValueObject
{
    public function __construct(
        private readonly string $text
    ) {
        if (empty($text)) {
            throw new \InvalidArgumentException('El texto del QR no puede estar vacío');
        }
    }

    public function value(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
