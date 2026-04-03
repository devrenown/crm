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
        $user,
        string $mode = 'watermark'
    ) {

        // Get decrypted file binary
        $binary = $this->decryptFromPrivateDisk($path);

        /*
        |--------------------------------------------------------------------------
        | CLEAN MODE (No watermark, direct streaming)
        |--------------------------------------------------------------------------
        */
        if ($mode === 'clean') {
            return response($binary, 200, [
                'Content-Type' => $mime,
                'Content-Disposition' => "inline; filename=\"$filename\"",
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | WATERMARK MODE
        |--------------------------------------------------------------------------
        */

        $tenantName = $user->tenant->name ?? 'Tenant';
        $timestamp  = now()->format('d-m-Y H:i');
        $watermark  = "$tenantName | $timestamp";

        $pdf = $this->initializePdf($user, $originalName, $mime, $tenantName);

        /*
        |--------------------------------------------------------------------------
        | HANDLE PDF
        |--------------------------------------------------------------------------
        */
        if ($mime === 'application/pdf') {

            try {
                // Try applying watermark using FPDI
                $this->handlePdf($pdf, $binary, $watermark);

            } catch (\Exception $e) {

                // FPDI unsupported PDF (compressed / object streams etc.)
                if ($e->getMessage() === 'FPDI_UNSUPPORTED') {

                    // \Log::warning('FPDI unsupported → serving clean PDF', [
                    //     'file' => $path
                    // ]);

                    // Fallback: return original PDF without watermark
                    return response($binary, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => "inline; filename=\"$filename\"",
                        'Cache-Control' => 'no-store',
                        'X-Content-Type-Options' => 'nosniff',
                    ]);
                }

                // Unknown error → rethrow
                throw $e;
            }

        /*
        |--------------------------------------------------------------------------
        | HANDLE IMAGE
        |--------------------------------------------------------------------------
        */
        } elseif (str_starts_with($mime, 'image/')) {

            $this->handleImage($pdf, $binary, $watermark);

        } else {
            abort(415, 'Unsupported document type');
        }

        // Return final generated PDF
        return $this->streamResponse($pdf, $filename);
    }

    /*
    |--------------------------------------------------------------------------
    | Decrypt file from private storage
    |--------------------------------------------------------------------------
    */
    private function decryptFromPrivateDisk(string $path): string
    {
        $disk = Storage::disk('private');

        if (!$disk->exists($path)) {
            abort(404, 'File not found');
        }

        $content = $disk->get($path);

        // If encrypted file
        if (str_ends_with($path, '.enc')) {
            return FileEncryptionService::decrypt($content);
        }

        return $content;
    }

    /*
    |--------------------------------------------------------------------------
    | Initialize FPDI instance
    |--------------------------------------------------------------------------
    */
    private function initializePdf($user, $subject, $mime, $tenant)
    {
        $pdf = new Fpdi();

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCompression(true);
        $pdf->SetAutoPageBreak(false);

        // Metadata
        $pdf->SetCreator($user->name ?? 'System');
        $pdf->SetAuthor($user->email ?? 'unknown');
        $pdf->SetSubject($subject ?? 'Document');
        $pdf->SetKeywords("tenant=$tenant, mime=$mime");

        return $pdf;
    }

    /*
    |--------------------------------------------------------------------------
    | Apply watermark on PDF
    |--------------------------------------------------------------------------
    */
    private function handlePdf($pdf, string $binary, string $watermark)
    {
        try {
            // Try parsing PDF
            $pageCount = $pdf->setSourceFile(
                StreamReader::createByString($binary)
            );

            for ($page = 1; $page <= $pageCount; $page++) {

                $tpl  = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($tpl);

                // Add page with same size
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);

                // Apply watermark
                $this->applyWatermark($pdf, $watermark, $size);
            }

        } catch (\Throwable $e) {

            // FPDI cannot read this PDF (compressed / newer format)
            // \Log::warning('FPDI parsing failed', [
            //     'error' => $e->getMessage()
            // ]);

            // Trigger fallback
            throw new \Exception('FPDI_UNSUPPORTED');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Handle image watermarking
    |--------------------------------------------------------------------------
    */
    private function handleImage($pdf, string $binary, string $watermark)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmp, $binary);

        $pdf->AddPage();

        // Draw image
        $pdf->Image($tmp, 10, 10, 190);

        unlink($tmp);

        $size = [
            'width' => $pdf->getPageWidth(),
            'height' => $pdf->getPageHeight()
        ];

        $this->applyWatermark($pdf, $watermark, $size);
    }

    /*
    |--------------------------------------------------------------------------
    | Watermark logic
    |--------------------------------------------------------------------------
    */
    private function applyWatermark($pdf, string $text, array $size)
    {
        $pdf->SetAlpha(0.10);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(160, 160, 160);

        $pageWidth  = $size['width'];
        $pageHeight = $size['height'];

        $xSpacing = $pageWidth / 2.2;
        $ySpacing = $pageHeight / 3;

        // Repeat watermark diagonally
        for ($x = -$pageWidth; $x < $pageWidth * 2; $x += $xSpacing) {
            for ($y = 0; $y < $pageHeight * 1.5; $y += $ySpacing) {

                $pdf->StartTransform();
                $pdf->Rotate(35, $x, $y);
                $pdf->Text($x, $y, $text);
                $pdf->StopTransform();
            }
        }

        //  Invisible forensic hash
        $pdf->SetAlpha(0.01);
        $pdf->SetFont('courier', '', 6);
        $pdf->Text(1, $pageHeight - 2, hash('sha256', $text));
        $pdf->SetAlpha(1);
    }

    /*
    |--------------------------------------------------------------------------
    | Final response stream
    |--------------------------------------------------------------------------
    */
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

    // private function handlePdf($pdf, string $binary, string $watermark)
    // {
    //     $pageCount = $pdf->setSourceFile(StreamReader::createByString($binary));

    //     for ($page = 1; $page <= $pageCount; $page++) {
    //         $tpl  = $pdf->importPage($page);
    //         $size = $pdf->getTemplateSize($tpl);

    //         $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    //         $pdf->useTemplate($tpl);

    //         $this->applyWatermark($pdf, $watermark, $size);
    //     }
    // }

    // private function handleImage($pdf, string $binary, string $watermark)
    // {
    //     $tmp = tempnam(sys_get_temp_dir(), 'img');
    //     file_put_contents($tmp, $binary);

    //     [$imgWidth, $imgHeight] = getimagesize($tmp);

    //     $mmWidth  = 210;
    //     $mmHeight = $imgHeight * ($mmWidth / $imgWidth);

    //     $pdf->AddPage('P', [$mmWidth, $mmHeight]);
    //     //$pdf->Image($tmp, 0, 0, $mmWidth, $mmHeight);
    //     $pdf->Image($tmp, 0, 0, '', '', '', '', '', false, 300);

    //     unlink($tmp);

    //     $size = ['width' => $mmWidth, 'height' => $mmHeight];
    //     $this->applyWatermark($pdf, $watermark, $size);
    // }

   