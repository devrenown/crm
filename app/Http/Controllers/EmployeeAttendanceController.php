<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceTimestamp;
use Illuminate\Support\Facades\Crypt;
use App\Enums\TaskStatus;
use App\Models\WorkReport;

class EmployeeAttendanceController extends Controller
{
    
    public function clockin(){
        
    }

    public function clockoutModal ()
    {
        return view('pages.attendances.clockout-modal');
    }

    public function clockout(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $timestamp = AttendanceTimestamp::findOrFail(Crypt::decrypt($request->timestampId));
            if ($request->status) {
               $status    = TaskStatus::from($request->status); 
            }

            $timestamp->attendance->update([
                'endDate'     => now(),
            ]);

            $timestamp->update(['endTime' => now()]);

            logger()->info('User Clocked Out via Controller', [
                'user_id'      => auth()->id(),
                'tenant_id'    => auth()->user()->tenant_id,
                'attendance_id'=> $timestamp->attendance_id,
                'timestamp_id' => $timestamp->id,
                'end_time'     => $timestamp->endTime,
            ]);

            if (isset($request->title) && !empty($request->title)) {
                WorkReport::create([
                    'user_id'     => auth()->user()->id,
                    'project_id'  => $request->project ?? null,
                    'title'       => $request->title,
                    'status'      => $status ?? null,
                    'description' => $request->description ?? null,
                ]);
            }
            
            $notification = notify('You have clocked out successfully');
            return redirect('/dashboard')->with('events', ['refreshAttendance', 'IsClockedIn'])->with($notification);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

}
