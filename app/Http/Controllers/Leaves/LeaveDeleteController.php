<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Storage;

class LeaveDeleteController extends Controller
{
    public function destroy(LeaveRequest $leave)
    {

        if (!auth()->user()->can('delete', $leave)) {
            return back()->with([
                'message'    => 'Leave cannot be deleted once approval has started.',
                'alert-type' => 'warning',
            ]);
        }
        
        if ($leave->document_path) {
            Storage::disk('public')->deleteDirectory(dirname($leave->document_path));
        }

        $leave->delete();

        return back()->with([
            'message'    => 'Leave deleted successfully.',
            'alert-type' => 'success',
        ]);
    }
}
