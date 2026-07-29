<?php

namespace Src\QR\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Src\QR\Application\DTO\GenerateQRDTO;
use Src\QR\Application\Services\QRService;
use Src\Shared\Infrastructure\Controllers\BaseController;

class QRController extends BaseController
{
    public function __construct(
        private readonly QRService $qrService
    ) {}

    public function love()
    {
        Log::info('[QRController] love page requested');

        $url = route('qr.love');
        $dto = new GenerateQRDTO(
            text: $url,
            size: 250,
            foregroundColor: '#e91e63',
            backgroundColor: '#ffffff',
        );

        try {
            $qr = $this->qrService->generateFromDTO($dto);
            Log::info('[QRController] love page QR generado', ['qrImageUrl' => $qr->getImageUrl()]);
        } catch (\Throwable $e) {
            Log::error('[QRController] Error generando QR para love page', ['error' => $e->getMessage()]);
            throw $e;
        }

        return Inertia::render('QR/Views/LoveDeclaration', [
            'qrImageUrl' => $qr->getImageUrl(),
            'pageUrl' => $url,
        ]);
    }

    public function generate(Request $request)
    {
        Log::info('[QRController] generate QR solicitado', [
            'text' => $request->input('text'),
            'size' => $request->integer('size', 200),
            'foreground_color' => $request->input('foreground_color'),
            'background_color' => $request->input('background_color'),
            'error_correction_level' => $request->input('error_correction_level', 'medium'),
            'has_logo' => $request->hasFile('logo') || $request->input('logo_path'),
        ]);

        $dto = new GenerateQRDTO(
            text: $request->input('text'),
            size: $request->integer('size', 200),
            foregroundColor: $request->input('foreground_color'),
            backgroundColor: $request->input('background_color'),
            logoPath: $this->resolveLogoPath($request),
            logoResizeToWidth: $request->integer('logo_resize_to_width', 50),
            errorCorrectionLevel: $request->input('error_correction_level', 'medium'),
            margin: $request->integer('margin', 10),
            roundBlockSizeMode: $request->input('round_block_size_mode', 'margin'),
        );

        try {
            $qr = $this->qrService->generateFromDTO($dto);

            Log::info('[QRController] QR generado exitosamente', [
                'image_url' => $qr->getImageUrl(),
            ]);

            return response()->json([
                'image_url' => $qr->getImageUrl(),
                'text' => $qr->getText()->value(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[QRController] Error generando QR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Error al generar el código QR',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function resolveLogoPath(Request $request): ?string
    {
        if ($request->hasFile('logo')) {
            return $request->file('logo')->getRealPath();
        }

        return $request->input('logo_path');
    }
}
