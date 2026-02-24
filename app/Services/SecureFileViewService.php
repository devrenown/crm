<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class SecureFileViewService
{
    public function streamDocument(
        string $path,
        string $mime,
        string $filename,
        ?string $originalName,
        $user
    ) {
        $binary = $this->decryptFromPrivateDisk($path);

        $tenantName = $user->tenant->name ?? 'Tenant';
        $timestamp  = now()->format('d-m-Y H:i');
        $watermark  = "$tenantName | $timestamp";

        $pdf = $this->initializePdf($user, $originalName, $mime, $tenantName);

        if ($mime === 'application/pdf') {
            $this->handlePdf($pdf, $binary, $watermark);
        } elseif (str_starts_with($mime, 'image/')) {
            $this->handleImage($pdf, $binary, $watermark);
        } else {
            abort(415, 'Unsupported document type');
        }

        return $this->streamResponse($pdf, $filename);
    }

    private function decryptFromPrivateDisk(string $path): string
    {
        $disk = Storage::disk('private');

        if (!$disk->exists($path)) {
            abort(404, 'File not found');
        }

        $content = $disk->get($path);

        // If encrypted file (new system)
        if (str_ends_with($path, '.enc')) {
            return FileEncryptionService::decrypt($content);
        }

        // Old non-encrypted file
        return $content;
    }

    private function initializePdf($user, $subject, $mime, $tenant)
    {
        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCompression(true);
        $pdf->SetAutoPageBreak(false);

        $pdf->SetCreator($user->name ?? 'System');
        $pdf->SetAuthor($user->email ?? 'unknown');
        $pdf->SetSubject($subject ?? 'Document');
        $pdf->SetKeywords("tenant=$tenant, mime=$mime");

        return $pdf;
    }

    private function handlePdf($pdf, string $binary, string $watermark)
    {
        $pageCount = $pdf->setSourceFile(StreamReader::createByString($binary));

        for ($page = 1; $page <= $pageCount; $page++) {
            $tpl  = $pdf->importPage($page);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);

            $this->applyWatermark($pdf, $watermark, $size);
        }
    }

    private function handleImage($pdf, string $binary, string $watermark)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmp, $binary);

        [$imgWidth, $imgHeight] = getimagesize($tmp);

        $mmWidth  = 210;
        $mmHeight = $imgHeight * ($mmWidth / $imgWidth);

        $pdf->AddPage('P', [$mmWidth, $mmHeight]);
        $pdf->Image($tmp, 0, 0, $mmWidth, $mmHeight);

        unlink($tmp);

        $size = ['width' => $mmWidth, 'height' => $mmHeight];
        $this->applyWatermark($pdf, $watermark, $size);
    }

    private function applyWatermark($pdf, string $text, array $size)
    {
        $pdf->SetAlpha(0.10);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(160, 160, 160);

        $pageWidth  = $size['width'];
        $pageHeight = $size['height'];

        $xSpacing = $pageWidth / 2.2;
        $ySpacing = $pageHeight / 3;

        for ($x = -$pageWidth; $x < $pageWidth * 2; $x += $xSpacing) {
            for ($y = 0; $y < $pageHeight * 1.5; $y += $ySpacing) {
                $pdf->StartTransform();
                $pdf->Rotate(35, $x, $y);
                $pdf->Text($x, $y, $text);
                $pdf->StopTransform();
            }
        }

        // Invisible forensic hash
        $pdf->SetAlpha(0.01);
        $pdf->SetFont('courier', '', 6);
        $pdf->Text(1, $pageHeight - 2, hash('sha256', $text));
        $pdf->SetAlpha(1);
    }

    private function streamResponse($pdf, string $filename)
    {
        return response($pdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Cache-Control' => 'no-store',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; frame-ancestors 'self'",
        ]);
    }
}
