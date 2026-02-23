<?php

namespace App\Http\Controllers\Leaves;
use App\Http\Controllers\Leaves\LeaveIndexController;
use App\Http\Controllers\Controller;
use App\Models\{LeaveRequest, LeaveType};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\Snappy\Facades\SnappyPdf;
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

    public function edit(LeaveRequest $leave)
    {
        $canAct = Gate::any(['edit', 'approve'], $leave);
        $user = auth()->user();

        $l2RoleIds = collect($leave->leaveType->l2_roles ?? [])
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $userRoleIds = $user->roles->pluck('id')->toArray();

        $isL1 = $leave->approval_stage === 'L1'
            && $leave->user->reporting_manager === $user->id;

        $isL2 = $leave->approval_stage === 'L2'
            && !empty(array_intersect($userRoleIds, $l2RoleIds));

        $canAct = auth()->user()->can('edit', $leave)
        || auth()->user()->can('approve', $leave);
        $canEditFields = auth()->user()->can('edit', $leave);

        return view('pages.leaves.edit', [
            'leave'        => $leave,
            'leaveTypes'   => LeaveType::where('is_active', 1)->get(),
            'canUpdate'    => auth()->user()->can('edit', $leave),
            'termDetails'  => is_string($leave->term_details)
                ? json_decode($leave->term_details, true) ?? []
                : ($leave->term_details ?? []),
            'isL1' => $isL1,
            'isL2' => $isL2,
            'canAct' => $canAct,
            'canEditFields' => $canEditFields,
        ]);
    }



 public function viewDocument(Request $request, LeaveRequest $leave)
    {
        abort_unless($request->hasValidSignature(), 403, 'Link expired');
        abort_if(! $leave->document_path, 404);

        // 🔹 Use service to decrypt
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
        $pdf->SetAlpha(0.12);
        $pdf->SetFont('helvetica', 'B', 22);
        $pdf->SetTextColor(150, 150, 150);

        $xSpacing = 120;
        $ySpacing = 100;

        for ($x = -50; $x < $size['width']; $x += $xSpacing) {
            for ($y = 0; $y < $size['height']; $y += $ySpacing) {
                $pdf->StartTransform();
                $pdf->Rotate(35, $x, $y);
                $pdf->Text($x, $y, $text);
                $pdf->StopTransform();
            }
        }
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



// public function viewDocument(Request $request, LeaveRequest $leave)
// {
//     $this->authorize('view', $leave);
//     abort_unless($request->hasValidSignature(), 403);
//     abort_if(! $leave->document_path, 404);

//     $encrypted = Storage::disk('private')->get($leave->document_path);
//     $contents  = FileEncryptionService::decrypt($encrypted);

//      return response($contents, 200, [
//         'Content-Type'        => $leave->document_mime,
//         'Content-Disposition'=> 'inline',
//         'Content-Length'     => strlen($contents),
//         'Accept-Ranges'      => 'bytes',
//         'Cache-Control'      => 'private, no-store, no-cache, must-revalidate',
//         'Pragma'             => 'no-cache',
//         'Expires'            => '0',
//     ]);
// }




//    public function viewDocument(LeaveRequest $leave)
// {
//     $this->authorize('view', $leave);

//     abort_if(!$leave->document_path, 404);

//     $encrypted = Storage::disk('private')->get($leave->document_path);

//     $contents = FileEncryptionService::decrypt($encrypted);

//     return response($contents, 200, [
//         'Content-Type'        => $leave->document_mime,
//         'Content-Disposition'=> 'inline',
//         'Content-Length'     => strlen($contents),
//         'Accept-Ranges'      => 'bytes',
//         'Cache-Control'      => 'private, no-store, no-cache, must-revalidate',
//         'Pragma'             => 'no-cache',
//         'Expires'            => '0',
//     ]);
// }



    

