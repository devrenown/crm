<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\AttendanceTimestamp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Jenssegers\Agent\Agent;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeAttendance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $page = 1;

    public $onBreak = false;

    public $forProject, $project, $clockedIn = false, $timeStarted;
    public $timeId = null;
    public $todayActivity;

    public $totalHoursToday = '00:00';
    public $totalHoursThisWeek = '00:00';
    public $totalHoursThisMonth = '00:00';
    public $projects = [];
    public $tz;
    public $today;

    public $timestampId, $title, $status, $description;

    public $upcomingBirthdays;
    public $upcomingWorkAnniversaries;
    public $myTasks;

    public function mount($upcomingBirthdays, $upcomingWorkAnniversaries, $myTasks)

    {
        $this->tz = app('tenant_timezone') ?? 'Asia/Kolkata';
        $this->projects = \Modules\Project\Models\Project::where(
        'tenant_id',
        auth()->user()->tenant_id)->get();

        $this->upcomingBirthdays = $upcomingBirthdays;
        $this->upcomingWorkAnniversaries = $upcomingWorkAnniversaries;
        $this->myTasks = $myTasks;
        $this->today = tz(Carbon::today(), 'm-d');

        $this->getClockInData();
        // $this->statistics();
        $this->getAttendance();
    }

    private function tz()
    {
        return $this->tz;
    }

    /* =====================================================
        CLOCK IN
    ===================================================== */

    public function clockin()

    {

        try {

            $user  = auth()->user();

            $alreadyOpen = AttendanceTimestamp::where('user_id', $user->id)
                ->whereNull('endTime')
                ->exists();

            if ($alreadyOpen) {
                $this->dispatch('Notification', __('Already clocked in'));
                return;
            }

            $agent = new Agent();
            $tz = $this->tz();

            if ($this->forProject) {
                $this->validate([
                    'project' => 'required',
                ]);
            }

            $startOfDayUtc = now($tz)->startOfDay()->utc();
            $endOfDayUtc   = now($tz)->endOfDay()->utc();

            $attendance = Attendance::where('user_id', $user->id)
                ->whereNull('endDate')
                ->latest()
                ->first();

            if (!$attendance) {

                $attendance = Attendance::create([
                    'user_id'   => $user->id,
                    'startDate' => now('UTC'),
                    'location'  => $user->employeeDetail->department->location ?? null,
                    'platform'  => $agent->platform(),
                    'endDate'   => null,
                ]);
            }

            $timestamp = AttendanceTimestamp::create([
                'user_id' => $user->id,
                'attendance_id' => $attendance->id,
                'project_id' => $this->project,
                'startTime' => now('UTC'),
                'endTime' => null,
                'location' => $user->employeeDetail->department->location ?? null,
                'billable' => false,
                'ip' => request()->ip(),
            ]);

            $this->dispatch('IsClockedIn');
            $this->dispatch('refreshAttendance');
            // $this->dispatch('fetchStatistics');
            $this->dispatch('Notification', __('You have clockin successfully'));
            $this->js("$('#clockin_modal').modal('hide');");

        } catch (\Exception $e) {
            $this->dispatch('Notification', __('Something went wrong'));

        }

    }


    /* =====================================================
        TODAY ACTIVITY
    ===================================================== */



    #[On('refreshAttendance')]

    public function getAttendance()
    {

        $tz = $this->tz();
        $startOfDayUtc = now($tz)->startOfDay()->utc();
        $endOfDayUtc   = now($tz)->endOfDay()->utc();

        $this->todayActivity = AttendanceTimestamp::where('user_id', auth()->id())
            //->whereBetween('created_at', [$startOfDayUtc, $endOfDayUtc])
            ->whereBetween('startTime', [$startOfDayUtc, $endOfDayUtc])
            ->orderByDesc('created_at')
            ->get();
    }



    /* =====================================================

        STATISTICS (TZ SAFE)

    ===================================================== */



    // #[On('fetchStatistics')]

    // public function statistics()

    // {

    //     $tz = $this->tz();

    //     $userId = auth()->id();

    //     $now = now($tz);



    //     // TODAY

    //     $todayRecords = AttendanceTimestamp::where('user_id', $userId)

    //         ->whereBetween('startTime', [

    //             $now->copy()->startOfDay()->utc(),

    //             $now->copy()->endOfDay()->utc()

    //         ])

    //         ->get();



    //     $this->totalHoursToday = $this->formatMinutes(

    //         $this->sumMinutes(

    //             $todayRecords,

    //             $now->copy()->startOfDay(),

    //             $now->copy()->endOfDay()

    //         )

    //     );



    //     // WEEK

    //     $weekRecords = AttendanceTimestamp::where('user_id', $userId)

    //         ->whereBetween('startTime', [

    //             $now->copy()->startOfWeek()->utc(),

    //             $now->copy()->endOfWeek()->utc()

    //         ])

    //         ->get();



    //     $this->totalHoursThisWeek = $this->formatMinutes(

    //         $this->sumMinutes(

    //             $weekRecords,

    //             $now->copy()->startOfWeek(),

    //             $now->copy()->endOfWeek()

    //         )

    //     );



    //     // MONTH

    //     $monthRecords = AttendanceTimestamp::where('user_id', $userId)

    //         ->whereBetween('startTime', [

    //             $now->copy()->startOfMonth()->utc(),

    //             $now->copy()->endOfMonth()->utc()

    //         ])

    //         ->get();



    //     $this->totalHoursThisMonth = $this->formatMinutes(

    //         $this->sumMinutes(

    //             $monthRecords,

    //             $now->copy()->startOfMonth(),

    //             $now->copy()->endOfMonth()

    //         )

    //     );

    // }


    private function sumMinutes($records, Carbon $from, Carbon $to): int

        {
            $tz = $this->tz();

            return $records->sum(function ($r) use ($from, $to, $tz) {

                if (!$r->startTime instanceof Carbon) {
                    return 0;
                }

                $start = $r->startTime->copy()->timezone($tz);

                $endRaw = $r->endTime ?? now('UTC');
                $end = $endRaw instanceof Carbon
                    ? $endRaw->copy()->timezone($tz)
                    : Carbon::parse($endRaw)->timezone($tz);


                $start = $start->lt($from) ? $from : $start;
                $end = $end->gt($to) ? $to : $end;

                if ($end->lte($start)) return 0;

                return $start->diffInMinutes($end);
            });
        }


    /* =====================================================
        LIVE CLOCKED-IN STATUS
    ===================================================== */


    #[On('IsClockedIn')]

    // public function getClockInData()
    // {
    //     $latest = AttendanceTimestamp::where('user_id', auth()->id())
    //         ->whereNull('endTime')
    //         ->latest()
    //         ->first();

    //     if ($latest) {
    //         $this->clockedIn = true;
    //         $this->timeId = Crypt::encrypt($latest->id);
    //         $this->timeStarted = $latest->startTime;
    //     } else {
    //         $this->clockedIn = false;
    //         $this->timeId = null;
    //         $this->timeStarted = null;
    //     }
    // }

    public function getClockInData()
    {
        $latest = AttendanceTimestamp::where('user_id', auth()->id())
            ->latest()
            ->first();

        if ($latest && is_null($latest->endTime)) {
            // actively working
            $this->clockedIn = true;
            $this->onBreak = false;
            $this->timeId = Crypt::encrypt($latest->id);
            $this->timeStarted = $latest->startTime;

        } elseif ($latest && !is_null($latest->endTime)) {
            // last session closed → user is on break
            $attendanceOpen = Attendance::where('user_id', auth()->id())
                ->whereNull('endDate')
                ->exists();

            $this->clockedIn = $attendanceOpen;
            $this->onBreak = $attendanceOpen;
            $this->timeStarted = null;
        } else {
            $this->clockedIn = false;
            $this->onBreak = false;
            $this->timeStarted = null;
        }
    }

    public function breakOut()
    {
        $timestamp = AttendanceTimestamp::where('user_id', auth()->id())
            ->whereNull('endTime')
            ->latest()
            ->first();

        if (!$timestamp) {
            $this->dispatch('Notification', 'No active session');
            return;
        }

        $timestamp->update([
            'endTime' => now('UTC')
        ]);

        $this->onBreak = true;

        $this->dispatch('refreshAttendance');
        $this->dispatch('Notification', 'Break started');
    }

    public function breakIn()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereNull('endDate')
            ->latest()
            ->first();

        if (!$attendance) {
            $this->dispatch('Notification', 'No active attendance');
            return;
        }

        AttendanceTimestamp::create([
            'user_id' => auth()->id(),
            'attendance_id' => $attendance->id,
            'startTime' => now('UTC'),
            'location' => auth()->user()->employeeDetail->department->location ?? null,
            'ip' => request()->ip(),
        ]);

        $this->onBreak = false;

        $this->dispatch('IsClockedIn');
        $this->dispatch('refreshAttendance');
        $this->dispatch('Notification', 'Break ended');
    }

    public function getBreakTimeToday()
    {
        $records = AttendanceTimestamp::where('user_id', auth()->id())
            ->whereDate('startTime', today())
            ->orderBy('startTime')
            ->get();

        $breakSeconds = 0;

        for ($i = 0; $i < count($records) - 1; $i++) {
            $currentEnd = $records[$i]->endTime;
            $nextStart = $records[$i + 1]->startTime;

            if ($currentEnd && $nextStart) {
                $breakSeconds += Carbon::parse($currentEnd)
                    ->diffInSeconds(Carbon::parse($nextStart));
            }
        }

        return gmdate('H:i:s', $breakSeconds);
    }


    private function formatMinutes($minutes)
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }

    /* =====================================================
        RENDER
    ===================================================== */

    public function render()
    {
        $tz = $this->tz();

        $paginator = AttendanceTimestamp::where('user_id', auth()->id())
            ->whereNotNull('startTime')
            ->orderByDesc('startTime')
            ->paginate(10, ['*'], 'page', null, 30);

        // Group only the current page
        $records = $paginator->getCollection()
        ->groupBy(function ($r) use ($tz) {
            return $r->startTime
                ->copy()
                ->timezone($tz)
                ->format('Y-m-d');
        });

        $paginator->setCollection($records);

        return view('livewire.employee-attendance', [
            'attendances' => $records,
            'attendancePaginator' => $paginator,

        ]);
    }

}