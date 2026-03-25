<?php

namespace App\Http\Controllers\Leaves;

use App\Traits\SecureFileUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Log;

class LeaveEditController extends Controller
{
    use SecureFileUpload;
    public function edit(LeaveRequest $leave)
    {
        try {
            $this->authorize('edit', $leave);

            // Log::info('LeaveEditController EDIT HIT', [
            //     'leave_id' => $leave->id,
            //     'user_id' => auth()->id(),
            //     'leave_status' => $leave->status,
            //     'approval_stage' => $leave->approval_stage,
            //     'leave_type_id' => $leave->leave_type_id,
            //     'term_details_exists' => isset($leave->term_details),
            // ]);

            return view('pages.leaves.employee_edit', [
                'leaveRequest' => $leave,
                'canEditFields' => true,
                'leaveTypes' => \App\Models\LeaveType::where('is_active', 1)->get(),
            ]);
        } catch (\Throwable $e) {
            // Log::error('LeaveEditController EDIT ERROR', [
            //     'leave_id' => $leave->id ?? null,
            //     'user_id' => auth()->id(),
            //     'message' => $e->getMessage(),
            //     'stack' => $e->getTraceAsString(),
            // ]);

            abort(500, 'Failed to load leave edit page.');
        }
    }

    public function update(Request $request, LeaveRequest $leave)
    {
        try {
            $this->authorize('edit', $leave);

            // Log::info('LeaveEditController UPDATE HIT', [
            //     'leave_id' => $leave->id,
            //     'user_id' => auth()->id(),
            //     'request_payload' => $request->all(),
            // ]);

            $validated = $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'term_details' => 'required|array',
                'reason' => 'required|string',
                'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            ]);

            //Log::info('Validated Data', $validated);

            $termDetails = collect($validated['term_details']);
            $terms = $termDetails->pluck('term')->unique();

            // Overall term
            if ($terms->count() === 1) {
                $term = $terms->first();
            } else {
                $term = 'Mixed';
            }

            // Recalculate days, short leave hours, half_day_type
            $days = 0;
            $shortLeaveHours = 0;
            $isHalfDay = 0;
            foreach ($termDetails as $td) {
                if ($td['term'] === 'Fullday') {
                    $days += 1;
                } elseif ($td['term'] === 'Halfday') {
                    $days += 0.5;
                    $isHalfDay = 1;
                } elseif ($td['term'] === 'Shortleave') {
                    $hours = $td['short_leave_hours'] ?? 0;
                    $shortLeaveHours += $hours;
                    $days += $hours / 8;
                }
            }

            $leave->update([
                'leave_type_id' => $validated['leave_type_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'term' => $term,
                'term_details' => $validated['term_details'], 
                'reason' => $validated['reason'],
                'short_leave_hours' => $shortLeaveHours, 
                'days' => $days,                         // save total days
                'is_half_day' => $isHalfDay, 
            ]);

            if ($request->hasFile('document')) {
                $upload = $this->uploadEncrypted(
                    $request->file('document'),
                    auth()->id() . '/leaves/' . $leave->id
                );

                if ($upload) {
                    $leave->update([
                        'document_path' => $upload['path'],
                        'document_mime' => $upload['mime'],
                    ]);
                }
            }

            //Log::info('Leave updated successfully', ['leave_id' => $leave->id]);

            return redirect()->route('leaves.index')->with([
                'message' => 'Leave updated successfully!',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            // Log::warning('LeaveEditController Validation Failed', [
            //     'errors' => $ve->errors(),
            //     'payload' => $request->all(),
            // ]);
            throw $ve;
        } catch (\Throwable $e) {
            // Log::error('LeaveEditController UPDATE ERROR', [
            //     'leave_id' => $leave->id ?? null,
            //     'user_id' => auth()->id(),
            //     'message' => $e->getMessage(),
            //     'stack' => $e->getTraceAsString(),
            // ]);

            abort(500, 'Failed to update leave.');
        }
    }
}
