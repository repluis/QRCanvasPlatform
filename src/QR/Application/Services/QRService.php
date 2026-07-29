<?php

namespace Src\QR\Application\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;
use Src\QR\Application\DTO\GenerateQRDTO;
use Src\QR\Domain\Entities\QR;
use Src\QR\Domain\ValueObjects\QRText;

class QRService
{
    public function generateFromDTO(GenerateQRDTO $dto): QR
    {
        Log::info('[QRService] generateFromDTO iniciado', [
            'text' => $dto->text,
            'size' => $dto->size,
            'foregroundColor' => $dto->foregroundColor,
            'backgroundColor' => $dto->backgroundColor,
            'errorCorrectionLevel' => $dto->errorCorrectionLevel,
            'margin' => $dto->margin,
            'roundBlockSizeMode' => $dto->roundBlockSizeMode,
            'hasLogo' => !empty($dto->logoPath),
        ]);

        try {
            $foregroundColor = $dto->foregroundColor
                ? $this->hexToColor($dto->foregroundColor)
                : new Color(0, 0, 0);

            $backgroundColor = $dto->backgroundColor
                ? $this->hexToColor($dto->backgroundColor)
                : new Color(255, 255, 255);

            $result = (new Builder(
                writer: new PngWriter(),
                data: $dto->text,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::from($dto->errorCorrectionLevel),
                size: $dto->size,
                margin: $dto->margin,
                roundBlockSizeMode: RoundBlockSizeMode::from($dto->roundBlockSizeMode),
                foregroundColor: $foregroundColor,
                backgroundColor: $backgroundColor,
                logoPath: $dto->logoPath ?? '',
                logoResizeToWidth: $dto->logoResizeToWidth,
                validateResult: false,
            ))->build();

            $filename = 'qr_' . md5($dto->text . microtime()) . '.png';
            $relativePath = 'qr/' . $filename;
            $storagePath = storage_path('app/public/' . $relativePath);

            $result->saveToFile($storagePath);

            $url = asset('storage/' . $relativePath);

            Log::info('[QRService] QR generado exitosamente', [
                'url' => $url,
                'filename' => $filename,
            ]);

            return new QR($url, new QRText($dto->text));
        } catch (\Throwable $e) {
            Log::error('[QRService] Error generando QR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function hexToColor(string $hex): Color
    {
        $hex = ltrim($hex, '#');
        $rgb = sscanf($hex, '%02x%02x%02x');

        return new Color($rgb[0], $rgb[1], $rgb[2]);
    }
}
