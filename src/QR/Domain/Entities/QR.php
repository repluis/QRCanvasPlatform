<?php

namespace Src\QR\Domain\Entities;

use Src\QR\Domain\ValueObjects\QRText;
use Src\Shared\Domain\Entity;

class QR extends Entity
{
    public function __construct(
        private readonly string $imageUrl,
        private readonly QRText $qrText,
    ) {
        parent::__construct();
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getText(): QRText
    {
        return $this->qrText;
    }
}
