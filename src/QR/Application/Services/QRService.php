<?php

namespace Src\QR\Application\Services;

use Src\QR\Domain\Entities\QR;
use Src\QR\Domain\ValueObjects\QRText;

class QRService
{
    private const BASE_URL = 'https://quickchart.io/qr';

    public function generate(string $text, int $size = 200): QR
    {
        $qrText = new QRText($text);

        $url = self::BASE_URL . '?' . http_build_query([
            'text' => $qrText->value(),
            'size' => $size,
        ]);

        return new QR($url, $qrText);
    }
}
