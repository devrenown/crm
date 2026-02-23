<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use Livewire\Attributes\Js;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\AttendanceTimestamp;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;

class EmployeeAttendance extends Component
{
    public $forProject,$project, $clockedIn, $timeStarted;
    public $totalHours = 0;
    public $timeId = null;
    public $attendances, $todayActivity;
    
    public $totalHoursToday;
    public $totalHoursThisMonth;
    public $totalHoursThisWeek;

    public $timestampId, $title, $status, $description;

    public function clockin()
    {
        try{

            $user  = auth()->user();
            $agent = new Agent();
            
            if($this->forProject){
                $this->validate([
                    'project' => 'required',
                ]);
            }
            $todayAttendance = Attendance::where('user_id', $user->id)
                    ->whereDate('created_at', Carbon::today())->first();
            if(!empty($todayAttendance)){
                $attendance = $todayAttendance;
            }else{
                $attendance = Attendance::create([
                    'user_id'   => $user->id,
                    'startDate' => now(),
                    'location'  => $user->employeeDetail->department->location ?? null,
                    'platform'  => $agent->platform(),
                    'endDate'   => null,
                ]);
            }
            AttendanceTimestamp::create([
                'user_id' => $user->id,
                'attendance_id' => $attendance->id,
                'project_id' => $this->project,
                'startTime' => now(),
                'endTime' => null,
                'location' => $user->employeeDetail->department->location ?? null,
                'billable' => false,
                'ip' => request()->ip() ?? null,
            ]);
            $this->dispatch('IsClockedIn');
            $this->dispatch('refreshAttendance');
            $this->dispatch('Notification',__('You have clockin successfully'));
            // $this->js("bootstrap.Modal.getInstance(document.getElementById('clockin_modal')).hide()");
            $this->js("$('#clockin_modal').modal('hide');");
        }catch(\Exception $e){
            $this->dispatch('Notification',__('Something went wrong'));
        }
    }

    // <===== Not in used this function is located in EmployeeAttendanceController =====>
    public function clockout()
    {
        try{
            $timestamp = AttendanceTimestamp::find(Crypt::decrypt($this->timestampId));

            $this->validate([
                'title'         => 'required|string|max:255',
                'status'        => 'required|integer',
                'description'   => 'nullable|string',
            ]);

            $timestamp->attendance->update([
                'endDate'       => now(),
                'title'         => $this->title,
                'status'        => $this->status,
                'description'   => $this->description,
            ]);

            $timestamp->update([
                'endTime' => now(),
            ]);

            $this->dispatch('IsClockedIn');
            $this->dispatch('refreshAttendance');
            $this->dispatch('Notification',__('You have clockout successfully'));
        }catch(\Exception $e){
            $this->dispatch('Notification',__('Something went wrong'));
        }
    }

   
    // #[On('refreshAttendance')]
    // public function getAttendance()
    // {
    //     $userId = auth()->user()->id;
    //     $attendances = AttendanceTimestamp::where('user_id', $userId)
    //                 ->whereNotNull('attendance_id');

    //     $attendances->groupBy($item) {
    //         $item->created_at->format('Y-m-d');
    //     }
    //     $this->attendances = $attendances->get();
    //     $this->todayActivity = $attendances->whereDate('created_at', Carbon::today())->get();
        
    // }

    #[On('refreshAttendance')]
    public function getAttendance()
    {
        // 1. Get the base query. No execution yet.
        $userId = auth()->user()->id;
        $baseQuery = AttendanceTimestamp::where('user_id', $userId)
                                        ->whereNotNull('attendance_id')
                                        ->orderBy('created_at', 'desc');

        $this->attendances = $baseQuery->get()
        ->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })
        ->map(function ($dayRecords) {
            // Ensure each group is converted to an array of simple objects/arrays
            return $dayRecords->all(); 
        })
        ->all(); // Convert the main grouped Collection to a plain array
        
        // Using separate query (more performant for large tables):
        $this->todayActivity = AttendanceTimestamp::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[On('fetchStatistics')]
    public function statistics()
    {
        $userId = auth()->user()->id;
        $userAttendances = AttendanceTimestamp::where('user_id', $userId)
            ->whereNotNull('attendance_id');
        $this->totalHoursToday = $userAttendances->whereDate('created_at', Carbon::today())
            ->get()
            ->sum('totalHours');
        $this->totalHoursThisMonth = $userAttendances->whereMonth('created_at', Carbon::now())
            ->get()
            ->sum('totalHours');
        $this->totalHoursThisWeek = $userAttendances
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get()
            ->sum('totalHours');
    }

    #[On('IsClockedIn')]
    public function getClockInData()
    {
        $todayClockin = Attendance::where('user_id', auth()->user()->id)
                    ->whereDate('created_at', Carbon::today())
                    ->first();
        if(!empty($todayClockin)){
            $latestClockin = $todayClockin->timestamps()->latest()->whereNull('endTime')->first() ?? null;
            if(!empty($latestClockin)){
                $this->clockedIn = true;
                $this->timeId = Crypt::encrypt($latestClockin->id);
                $this->timeStarted = $latestClockin->startTime;

                $diffInMinutes = $latestClockin->startTime->diffInMinutes(Carbon::now());
                $hours = intdiv($diffInMinutes, 60);
                $minutes = $diffInMinutes % 60;
                $this->totalHours = sprintf('%02d:%02d', $hours, $minutes);
                // $this->totalHours = Carbon::now()->diff($latestClockin->startTime)->h;
            }
        }
    }
   
    public function render()
    {
        return view('livewire.employee-attendance');
    }
        
}
