<?php

namespace Src\QR\Infrastructure\Controllers;

use Illuminate\Http\Request;
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
        $url = route('qr.love');
        $qr = $this->qrService->generate($url, 250);

        return Inertia::render('QR/Views/LoveDeclaration', [
            'qrImageUrl' => $qr->getImageUrl(),
            'pageUrl' => $url,
        ]);
    }

    public function generate(Request $request)
    {
        $dto = new GenerateQRDTO(
            text: $request->input('text'),
            size: $request->integer('size', 200),
        );

        $qr = $this->qrService->generate($dto->text, $dto->size);

        return response()->json([
            'image_url' => $qr->getImageUrl(),
            'text' => $qr->getText()->value(),
        ]);
    }
}
