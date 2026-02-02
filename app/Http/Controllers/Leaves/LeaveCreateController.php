<?php

namespace App\Http\Controllers\Leaves;
use App\Traits\SecureFileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{LeaveRequest, LeaveType};
use Carbon\Carbon;
use DB;

class LeaveCreateController extends Controller
{
    use SecureFileUpload;
    public function create()
    {
        return view('pages.leaves.create', [
            'leaveTypes' => LeaveType::where('is_active', 1)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'term_details' => 'required|array|min:1',
            'term_details.*.date' => 'required|date',
            'term_details.*.term' => 'required|in:Fullday,Halfday,Shortleave',
            'term_details.*.half_day_type' => 'nullable|in:First,Second',
            'term_details.*.short_leave_hours' => 'nullable|numeric|min:0.25|max:8',
            'reason' => 'required|string|max:500',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $user = auth()->user();
        $type = LeaveType::findOrFail($request->leave_type_id);

        // Gender rule
        if ($type->gender != 0 && $type->gender != $user->gender) {
            return back()->withInput()->with([
                'message' => 'Leave not allowed for your gender',
                'alert-type' => 'warning'
            ]);
        }

        // Notice period
        if ($type->min_days_notice > 0) {
            $firstDate = collect($request->term_details)->min('date');
            if (now()->diffInDays(Carbon::parse($firstDate), false) < $type->min_days_notice) {
                return back()->withInput()->with([
                    'message' => "Minimum {$type->min_days_notice} days notice required",
                    'alert-type' => 'warning'
                ]);
            }
        }

        // Prevent multiple leaves on same date
        $dates = collect($request->term_details)->pluck('date')->unique();

        $conflictingLeave = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($q) use ($dates) {
                foreach ($dates as $date) {
                    $q->orWhere(function ($sub) use ($date) {
                        $sub->whereDate('start_date', '<=', $date)
                            ->whereDate('end_date', '>=', $date);
                    });
                }
            })
            ->first();

        if ($conflictingLeave) {
            return back()->withInput()->with([
                'message' => 'You already have a leave request applied for selected dates. Please edit the existing leave request.',
                'alert-type' => 'warning'
            ]);
        }

        DB::transaction(function () use ($request, $user, $type) {
            // Calculate days, half-day, short-leave
            $days = 0;
            $shortLeaveHours = 0;
            $isHalfDay = 0;
            $term = 'Fullday'; // default
            foreach ($request->term_details as $td) {
                if ($td['term'] === 'Fullday') {
                    $days += 1;
                } elseif ($td['term'] === 'Halfday') {
                    $days += 0.5;
                    $isHalfDay = 1;
                } elseif ($td['term'] === 'Shortleave') {
                    $shortLeaveHours += $td['short_leave_hours'] ?? 0;
                    $days += ($td['short_leave_hours'] ?? 0) / 8;
                }
            }

            $leave = LeaveRequest::create([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'leave_type_id' => $type->id,
                'start_date' => collect($request->term_details)->min('date'),
                'end_date' => collect($request->term_details)->max('date'),
                'days' => $days,
                'approved_days' => null,
                'short_leave_hours' => $shortLeaveHours,
                'is_half_day' => $isHalfDay,
                'half_day_type' => $isHalfDay ? ($request->term_details[0]['half_day_type'] ?? null) : null,
                'reason' => $request->reason,
                'status' => 'Pending',
                'approval_stage' => 'L1',
                'is_balance_applied' => 0,
                'term' => $term,
                'term_details' => json_encode($request->term_details),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Document upload
            if ($request->hasFile('document')) {
                $path = $this->uploadEncrypted(
                    file: $request->file('document'),
                    directory: 'leaves',
                    tenantDomain: $user->tenant->domain,
                    userId: $user->id,
                    objectId: $leave->id
                );

                $leave->update([
                    'document_path' => $path,
                    'document_mime' => $request->file('document')->getMimeType(),
                ]);
            }
        });

        // Success toast
        return redirect()->route('leaves.index')->with([
            'message' => 'Leave request submitted successfully',
            'alert-type' => 'success'
        ]);
    }
}

/*
namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{LeaveRequest, LeaveType};
use Carbon\Carbon;
use DB;

class LeaveCreateController extends Controller
{
    public function create()
    {
        return view('pages.leaves.create', [
            'leaveTypes' => LeaveType::where('is_active', 1)->get()
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'leave_type_id' => 'required|exists:leave_types,id',
        'term_details' => 'required|array|min:1',
        'term_details.*.date' => 'required|date',
        'term_details.*.term' => 'required|in:Fullday,Halfday,Shortleave',
        'term_details.*.half_day_type' => 'nullable|in:First,Second',
        'term_details.*.short_leave_hours' => 'nullable|numeric|min:0.25|max:8',
        'reason' => 'required|string|max:500',
        'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
    ]);

    $user = auth()->user();
    $type = LeaveType::findOrFail($request->leave_type_id);

    // Gender rule
    if ($type->gender != 0 && $type->gender != $user->gender) {
        return back()->withErrors(['Leave not allowed for your gender']);
    }

    // Notice period
    if ($type->min_days_notice > 0) {
        $firstDate = collect($request->term_details)->min('date');
        if (now()->diffInDays(Carbon::parse($firstDate), false) < $type->min_days_notice) {
            return back()->withErrors([
                "Minimum {$type->min_days_notice} days notice required"
            ]);
        }
    }

    // Prevent multiple leaves on same date
    $dates = collect($request->term_details)->pluck('date')->unique();

    $conflictingLeave = LeaveRequest::where('user_id', $user->id)
        ->whereIn('status', ['Pending', 'Approved'])
        ->where(function ($q) use ($dates) {
            foreach ($dates as $date) {
                $q->orWhere(function ($sub) use ($date) {
                    $sub->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date);
                });
            }
        })
        ->first();

    if ($conflictingLeave) {
        return back()
            ->withInput()
            ->withErrors([
                'term_details' =>
                    'You already have a leave request applied for selected dates. '
                    .'Please edit the existing leave request.'
            ]);
    }

    DB::transaction(function () use ($request, $user, $type) {
        // Calculate days, half-day, short-leave
        $days = 0;
        $shortLeaveHours = 0;
        $isHalfDay = 0;
        $term = 'Fullday'; // default
        foreach ($request->term_details as $td) {
            if ($td['term'] === 'Fullday') {
                $days += 1;
            } elseif ($td['term'] === 'Halfday') {
                $days += 0.5;
                $isHalfDay = 1;
            } elseif ($td['term'] === 'Shortleave') {
                $shortLeaveHours += $td['short_leave_hours'] ?? 0;
                $days += ($td['short_leave_hours'] ?? 0) / 8;
            }
        }

        $leave = LeaveRequest::create([
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id,
            'leave_type_id' => $type->id,
            'start_date' => collect($request->term_details)->min('date'),
            'end_date' => collect($request->term_details)->max('date'),
            'days' => $days,
            'approved_days' => null,
            'short_leave_hours' => $shortLeaveHours,
            'is_half_day' => $isHalfDay,
            'half_day_type' => $isHalfDay ? ($request->term_details[0]['half_day_type'] ?? null) : null,
            'reason' => $request->reason,
            'status' => 'Pending',
            'approval_stage' => 'L1',
            'is_balance_applied' => 0,
            'term' => $term,
            'term_details' => json_encode($request->term_details),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Document upload
        if ($request->hasFile('document')) {
            $path = $request->file('document')->storeAs(
                "leaves/{$user->tenant_id}/{$user->id}/leave_{$leave->id}",
                $request->file('document')->getClientOriginalName(),
                'public'
            );
            $leave->update(['document_path' => $path]);
        }
    });

    return redirect()->route('leaves.index')
        ->with('success', 'Leave request submitted successfully');
}

}


*/