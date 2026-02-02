<?php

namespace App\Http\Controllers\Leaves;
use Illuminate\Http\Request;
use App\Http\Controllers\Leaves\LeaveIndexController;
use App\Http\Controllers\Controller;
use App\Models\{LeaveRequest, LeaveType};
use Illuminate\Support\Facades\Gate;
use App\Services\SecureFileViewService;
use App\Services\FileEncryptionService;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class LeaveViewController extends Controller
{
    public function show(LeaveRequest $leave)
    {
        $this->authorize('view', $leave);
        $summaryUser = $leave->user;

        return view('pages.leaves.show', [
            'leave' => $leave,
            'leaveSummary' => $summaryUser->isEmployee()
                ? app(LeaveIndexController::class)->employeeLeaveSummary($summaryUser)
                : [],
        ]);
    }


     public function viewDocument(Request $request, LeaveRequest $leave)
    {
        abort_unless($request->hasValidSignature(), 403, 'Link expired');
        abort_if(! $leave->document_path, 404);

        // Use service to decrypt
        $binary = SecureFileViewService::decryptFromPrivateDisk($leave->document_path);

        $user       = auth()->user();
        $tenantName = $user->tenant->name ?? 'Tenant';
        $leaveId    = $leave->id;
        $timestamp  = now()->format('d-m-Y H:i');
        $watermark  = "$tenantName | $timestamp";
        $mime       = $leave->document_mime;

        $creator   = $user->name ?? 'Unknown User';
        $author    = $user->email ?? 'unknown';
        $subject   = $leave->document_name ?? "Document";
        $keywords  = implode(', ', [
            'tenant=' . $tenantName,
            'generated_at=' . now()->format('Y-m-d H:i:s'),
            'mime=' . $mime,
        ]);

        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCompression(true);
        $pdf->setJPEGQuality(85);
        $pdf->SetAutoPageBreak(false);

        $pdf->SetCreator($creator);
        $pdf->SetAuthor($author);
        $pdf->SetSubject($subject);
        $pdf->SetKeywords($keywords);

        // ---------- PDF ----------
        if ($mime === 'application/pdf') {
            $pageCount = $pdf->setSourceFile(StreamReader::createByString($binary));

            for ($page = 1; $page <= $pageCount; $page++) {
                $tpl = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($tpl);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
                $this->applyTiledWatermark($pdf, $watermark, $size);

                // Invisible forensic watermark
                $pdf->SetAlpha(0.01);
                $pdf->SetFont('courier', '', 6);
                $pdf->Text(1, $size['height'] - 2, hash('sha256', $watermark));
                $pdf->SetAlpha(1);
            }

            return $this->streamPdf($pdf, $leaveId);
        }

        // ---------- IMAGE ----------
        if (str_starts_with($mime, 'image/')) {
            $tmp = tempnam(sys_get_temp_dir(), 'img');
            file_put_contents($tmp, $binary);
            [$imgWidthPx, $imgHeightPx] = getimagesize($tmp);

            $mmWidth  = 210;
            $mmHeight = $imgHeightPx * ($mmWidth / $imgWidthPx);

            $pdf->AddPage('P', [$mmWidth, $mmHeight]);
            $pdf->Image($tmp, 0, 0, $mmWidth, $mmHeight, '', '', '', false, 300);
            unlink($tmp);

            $size = ['width' => $mmWidth, 'height' => $mmHeight];
            $this->applyTiledWatermark($pdf, $watermark, $size);

            $pdf->SetAlpha(0.01);
            $pdf->SetFont('courier', '', 6);
            $pdf->Text(1, $size['height'] - 2, hash('sha256', $watermark));
            $pdf->SetAlpha(1);

            return $this->streamPdf($pdf, $leaveId);
        }

        abort(415, 'Unsupported document type');
    }

    protected function applyTiledWatermark($pdf, string $text, array $size): void
{
    $pdf->SetAlpha(0.10);
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(160, 160, 160);

    $pageWidth  = $size['width'];
    $pageHeight = $size['height'];

    $angle = 35;

    // Dynamic spacing based on page size
    $xSpacing = $pageWidth / 2.2;
    $ySpacing = $pageHeight / 3;

    for ($x = -$pageWidth; $x < $pageWidth * 2; $x += $xSpacing) {
        for ($y = 0; $y < $pageHeight * 1.5; $y += $ySpacing) {

            $pdf->StartTransform();

            // Rotate around text center (IMPORTANT)
            $pdf->Rotate($angle, $x, $y);

            $pdf->Text($x, $y, $text);

            $pdf->StopTransform();
        }
    }

    // Reset alpha (VERY IMPORTANT)
    $pdf->SetAlpha(1);
}

    protected function streamPdf($pdf, int $leaveId)
    {
        return response($pdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="leave-'.$leaveId.'.pdf"',
            'Cache-Control' => 'no-store',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; frame-ancestors 'self'",
        ]);
    }
}

    

