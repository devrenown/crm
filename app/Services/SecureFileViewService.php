<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class SecureFileViewService
{
    private function logDebug(string $stage, array $data = [])
    {
        // \Log::channel('daily')->debug('PDF_STREAM_DEBUG', array_merge([
        //     'stage' => $stage,
        //     'time' => now()->toDateTimeString(),
        // ], $data));
    }

    public function streamDocument(
        string $path,
        string $mime,
        string $filename,
        ?string $originalName,
        $user,
        string $mode = 'watermark'
    ) {

        $this->logDebug('ENTRY', compact('path','mime','filename','mode'));

        // Decrypt file
        $binary = $this->decryptFromPrivateDisk($path);

        if (!$binary) {
            abort(500, 'File decryption failed');
        }

        // Detect REAL mime
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $realMime = $finfo->buffer($binary) ?: 'application/octet-stream';

        $this->logDebug('MIME_DETECTED', [
            'real' => $realMime,
            'db' => $mime,
            'size' => strlen($binary),
        ]);

        /*
        | CLEAN MODE (no processing)
        */
        if ($mode === 'clean') {
            return response($binary, 200, [
                'Content-Type' => $realMime,
                'Content-Disposition' => "inline; filename=\"$filename\"",
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        $tenantName = $user->tenant->name ?? 'Tenant';
        $timestamp  = now()->format('d-m-Y H:i');
        $watermark  = "$tenantName | $timestamp";

        $pdf = $this->initializePdf($user, $originalName, $realMime, $tenantName);

        /*
        | TRY REAL MIME FIRST
        */
        try {

            if ($realMime === 'application/pdf') {

                // Safety check
                if (substr($binary, 0, 4) !== '%PDF') {
                    throw new \Exception('INVALID_PDF_HEADER');
                }

                $this->handlePdf($pdf, $binary, $watermark);

            } elseif (str_starts_with($realMime, 'image/')) {

                $this->handleImage($pdf, $binary, $watermark);

            } else {
                throw new \Exception('REAL_MIME_UNSUPPORTED');
            }

        } catch (\Throwable $e) {

            $this->logDebug('REAL_MIME_FAILED', [
                'error' => $e->getMessage()
            ]);

            /*
            | FALLBACK TO DB MIME
            */
            if ($mime !== $realMime) {

                try {

                    if ($mime === 'application/pdf') {

                        $this->handlePdf($pdf, $binary, $watermark);

                    } elseif (str_starts_with($mime, 'image/')) {

                        $this->handleImage($pdf, $binary, $watermark);

                    } else {
                        throw new \Exception('DB_MIME_UNSUPPORTED');
                    }

                } catch (\Throwable $e2) {

                    $this->logDebug('DB_MIME_FAILED', [
                        'error' => $e2->getMessage()
                    ]);

                    // FINAL FALLBACK → RAW
                    return $this->rawResponse($binary, $realMime, $filename);
                }

            } else {
                return $this->rawResponse($binary, $realMime, $filename);
            }
        }

        return $this->streamResponse($pdf, $filename);
    }

    /*
    | RAW FALLBACK RESPONSE
    */
    private function rawResponse(string $binary, string $mime, string $filename)
    {
        $this->logDebug('RAW_FALLBACK');

        return response($binary, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Cache-Control' => 'no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /*
    | DECRYPT
    */
    private function decryptFromPrivateDisk(string $path): string
    {
        $disk = Storage::disk('private');

        if (!$disk->exists($path)) {
            abort(404, 'File not found');
        }

        $content = $disk->get($path);

        if (str_ends_with($path, '.enc')) {

            $decrypted = FileEncryptionService::decrypt($content);

            if ($decrypted === false) {
                throw new \RuntimeException('Decryption failed (wrong key or corrupted file)');
            }

            return $decrypted;
        }

        return $content;
    }

    /*
    | INIT PDF
    */
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

    /*
    | HANDLE PDF
    */
    private function handlePdf($pdf, string $binary, string $watermark)
    {
        try {
            $stream = StreamReader::createByString($binary);
            $pageCount = $pdf->setSourceFile($stream);

            for ($page = 1; $page <= $pageCount; $page++) {

                $tpl  = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($tpl);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);

                $this->applyWatermark($pdf, $watermark, $size);
            }

        } catch (\Throwable $e) {
            throw new \Exception('FPDI_UNSUPPORTED');
        }
    }

    /*
    | HANDLE IMAGE
    */
    private function handleImage($pdf, string $binary, string $watermark)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmp, $binary);

        $pdf->AddPage();
        $pdf->Image($tmp, 10, 10, 190);

        unlink($tmp);

        $size = [
            'width' => $pdf->getPageWidth(),
            'height' => $pdf->getPageHeight()
        ];

        $this->applyWatermark($pdf, $watermark, $size);
    }

    /*
    | WATERMARK
    */
    private function applyWatermark($pdf, string $text, array $size)
    {
        $pdf->SetAlpha(0.10);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(160, 160, 160);

        $w = $size['width'];
        $h = $size['height'];

        for ($x = -$w; $x < $w * 2; $x += $w / 2.2) {
            for ($y = 0; $y < $h * 1.5; $y += $h / 3) {

                $pdf->StartTransform();
                $pdf->Rotate(35, $x, $y);
                $pdf->Text($x, $y, $text);
                $pdf->StopTransform();
            }
        }

        $pdf->SetAlpha(1);
    }

    /*
    | OUTPUT
    */
    private function streamResponse($pdf, string $filename)
    {
        $output = $pdf->Output('', 'S');

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Cache-Control' => 'no-store',
            'X-Content-Type-Options' => 'nosniff',
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

   