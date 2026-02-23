<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\AttendanceTimestamp;
use Livewire\Attributes\On;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Services\TenantService;

class EmployeeAttendance extends Component
{
    public bool $clockedIn = false;
    public ?string $timeId = null;
    public ?Carbon $timeStarted = null;

    // Minutes ONLY (INT)
    public int $totalMinutes = 0;
    public int $minutesToday = 0;
    public int $minutesWeek = 0;
    public int $minutesMonth = 0;

    public array $attendances = [];
    public array $todayActivity = [];

    public bool $forProject = false;
    public $project = null;

    /* ================= CLOCK IN ================= */
    public function clockin()
    {
        $user = auth()->user();
        $agent = new Agent();

        if ($this->forProject) {
            $this->validate(['project' => 'required']);
        }

        // ONE attendance per user per day (UTC)
        $attendance = Attendance::firstOrCreate(
            [
                'user_id'   => $user->id,
                'startDate' => Carbon::today('UTC')->toDateString(),
            ],
            [
                'tenant_id' => $user->tenant_id,
                'location'  => $user->employeeDetail->department->location ?? null,
                'platform'  => $agent->platform(),
            ]
        );

        AttendanceTimestamp::create([
            'tenant_id'     => $user->tenant_id,
            'user_id'       => $user->id,
            'attendance_id' => $attendance->id,
            'project_id'    => $this->project,
            'startTime'     => now('UTC'),
            'location'      => $attendance->location,
            'billable'      => false,
            'ip'            => request()->ip(),
        ]);

        $this->dispatch('IsClockedIn');
        $this->dispatch('refreshAttendance');
        $this->dispatch('fetchStatistics');
        
        $this->dispatch('Notification',__('You have clockin successfully'));
        // $this->js("bootstrap.Modal.getInstance(document.getElementById('clockin_modal')).hide()");
        $this->js("$('#clockin_modal').modal('hide');");
    }

    /* ================= CLOCK OUT ================= */
    public function clockout()
    {
        $active = AttendanceTimestamp::where('user_id', auth()->id())
            ->whereNull('endTime')
            ->latest('startTime')
            ->first();

        if (!$active) return;

        $active->update([
            'endTime' => now('UTC'),
        ]);

        $this->dispatch('IsClockedIn');
        $this->dispatch('refreshAttendance');
        $this->dispatch('fetchStatistics');
    }

    /* ================= ATTENDANCE LIST ================= */
    #[On('refreshAttendance')]
    public function getAttendance()
    {
        $tz = TenantService::timezone(auth()->user()->tenant_id);
        $activeId = AttendanceTimestamp::where('user_id', auth()->id())
            ->whereNull('endTime')
            ->latest('startTime')
            ->value('id');
    
        $records = AttendanceTimestamp::where('user_id', auth()->id())
            ->with('attendance.user.employeeShifts.shift')
            ->orderByDesc('startTime')
            ->get()
            ->map(function ($r) use ($tz, $activeId) {
    
                $start = $r->startTime?->copy()->timezone($tz);
                $end   = $r->endTime?->copy()->timezone($tz);
                $isRunning = $r->id === $activeId;
    
                $shiftAssignment = $r->attendance->user->employeeShifts()
                    ->where('created_at', '<=', $start)
                    ->latest('created_at')
                    ->with('shift')
                    ->first();
    
                $shift = $shiftAssignment?->shift;
    
                if ($shift) {
                    $shiftStart = Carbon::parse($shift->start_time, $tz);
                    $shiftEnd   = Carbon::parse($shift->end_time, $tz);
    
                    if ($shiftEnd->lte($shiftStart)) { // overnight shift
                        $shiftEnd->addDay();
                    }
    
                    // Use shift start date for grouping
                    $groupDate = $start->toDateString();
                    if ($start->lt($shiftStart)) {
                        $groupDate = $shiftStart->toDateString();
                    }
                } else {
                    $groupDate = $start->toDateString();
                }
    
                return [
                    'start' => $start,
                    'end' => $end,
                    'groupDate' => $groupDate,
                    'isRunning'  => $isRunning,
                    'shift_id' => $shift->id ?? null,
                    'shift_name' => $shift->name ?? null,
                    'date' => $start->toDateString(),
                    'auto_clocked' => $r->was_auto_clocked ?? false,
                ];
            });
    
        // Group by shift-aware date
        $this->attendances = $records
            ->groupBy('date')
            ->sortKeysDesc()
            ->map(fn($g) => $g->values())
            ->toArray();
    
        // Today activity: include timestamps that overlap today
        $todayStart = now($tz)->startOfDay();
        $todayEnd   = now($tz)->endOfDay();
    
        $this->todayActivity = $records
        ->sortByDesc('start')
        ->filter(function ($r) use ($todayStart, $todayEnd) {
            $start = $r['start'];
            $end   = $r['end']; // may be null (running)

            return $start->lt($todayEnd)
                && ($end === null || $end->gt($todayStart));
        })
        ->values()
        ->toArray();

    }
    
    

    /* ================= STATISTICS ================= */
    #[On('fetchStatistics')]
    public function statistics()
    {
        $tz = TenantService::timezone(auth()->user()->tenant_id);
    
        $records = AttendanceTimestamp::where('user_id', auth()->id())->get();
    
        $now = now($tz);
    
        $this->minutesToday = $this->sumMinutes(
            $records,
            $now->copy()->startOfDay(),
            $now->copy()->endOfDay()
        );
    
        $this->minutesWeek = $this->sumMinutes(
            $records,
            $now->copy()->startOfWeek(),
            $now->copy()->endOfWeek()
        );
    
        $this->minutesMonth = $this->sumMinutes(
            $records,
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth()
        );
    }
    

    /* ================= CLOCKED IN STATE ================= */
    #[On('IsClockedIn')]
    public function getClockInData()
    {
        $tz = TenantService::timezone(auth()->user()->tenant_id);
    
        $active = AttendanceTimestamp::where('user_id', auth()->id())
            ->whereNull('endTime')
            ->latest('startTime')
            ->first();
    
        if (!$active) {
            $this->clockedIn = false;
            $this->timeStarted = null;
            $this->timeId = null;
            $this->totalMinutes = 0;
            return;
        }
    
        $start = $active->startTime->copy()->timezone($tz);
    
        $this->clockedIn = true;
        $this->timeId = Crypt::encrypt($active->id);
        $this->timeStarted = $start;
        $this->totalMinutes = $start->diffInMinutes(now($tz));
    }
    

    

    private function sumMinutes($records, Carbon $from, Carbon $to): int
    {
        $tz = app('tenant_timezone');
    
        return $records->sum(function ($r) use ($from, $to, $tz) {
            $start = $r->startTime?->copy()->timezone($tz);
            if (!$start) return 0;
    
            $end = ($r->endTime ?? now('UTC'))->copy()->timezone($tz);
    
            // clamp
            $start = $start->lt($from) ? $from : $start;
            $end   = $end->gt($to) ? $to : $end;
    
            if ($end->lte($start)) return 0;
    
            return $start->diffInMinutes($end);
        });
    }
    

   

    public function render()
    {
        return view('livewire.employee-attendance');
    }
}
