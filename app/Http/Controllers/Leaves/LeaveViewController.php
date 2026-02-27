<?php

namespace App\Http\Controllers\Leaves;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Leaves\LeaveIndexController;
use App\Models\LeaveRequest;
use App\Services\SecureFileViewService;

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

        return app(SecureFileViewService::class)->streamDocument(
            path: $leave->document_path,
            mime: $leave->document_mime,
            filename: "leave-{$leave->id}.pdf",
            originalName: $leave->document_name,
            user: auth()->user()
        );
    }
}
