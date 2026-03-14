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


class EmployeeAttendance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $forProject, $project, $clockedIn = false, $timeStarted;
    public $totalHours = '00:00:00';
    public $timeId = null;
    public $todayActivity;

    public $totalHoursToday = '00:00';
    public $totalHoursThisWeek = '00:00';
    public $totalHoursThisMonth = '00:00';
    public $projects = [];
    public $tz;

    public $timestampId, $title, $status, $description;

    /* =====================================================

        HELPER: TENANT TIMEZONE

    ===================================================== */

    // private function tz()

    // {

    //     return app('tenant_timezone');

    // }


    public function mount()
    {
        $this->tz = app('tenant_timezone') ?? 'Asia/Kolkata';
        $this->projects = \Modules\Project\Models\Project::where(
        'tenant_id',
        auth()->user()->tenant_id

         )->get();

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



            // $attendance = Attendance::where('user_id', $user->id)

            //     ->whereBetween('startDate', [$startOfDayUtc, $endOfDayUtc])

            //     ->first();

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

            $this->dispatch('fetchStatistics');

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



    #[On('fetchStatistics')]

public function statistics()

{

    $tz = $this->tz();

    $userId = auth()->id();

    $now = now($tz);



    // TODAY

    $todayRecords = AttendanceTimestamp::where('user_id', $userId)

        ->whereBetween('startTime', [

            $now->copy()->startOfDay()->utc(),

            $now->copy()->endOfDay()->utc()

        ])

        ->get();



    $this->totalHoursToday = $this->formatMinutes(

        $this->sumMinutes(

            $todayRecords,

            $now->copy()->startOfDay(),

            $now->copy()->endOfDay()

        )

    );



    // WEEK

    $weekRecords = AttendanceTimestamp::where('user_id', $userId)

        ->whereBetween('startTime', [

            $now->copy()->startOfWeek()->utc(),

            $now->copy()->endOfWeek()->utc()

        ])

        ->get();



    $this->totalHoursThisWeek = $this->formatMinutes(

        $this->sumMinutes(

            $weekRecords,

            $now->copy()->startOfWeek(),

            $now->copy()->endOfWeek()

        )

    );



    // MONTH

    $monthRecords = AttendanceTimestamp::where('user_id', $userId)

        ->whereBetween('startTime', [

            $now->copy()->startOfMonth()->utc(),

            $now->copy()->endOfMonth()->utc()

        ])

        ->get();



    $this->totalHoursThisMonth = $this->formatMinutes(

        $this->sumMinutes(

            $monthRecords,

            $now->copy()->startOfMonth(),

            $now->copy()->endOfMonth()

        )

    );

}



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

public function getClockInData()

{

    $latest = AttendanceTimestamp::where('user_id', auth()->id())

        ->whereNull('endTime')

        ->latest()

        ->first();



    if ($latest) {

        $this->clockedIn = true;

        $this->timeId = Crypt::encrypt($latest->id);

        $this->timeStarted = $latest->startTime;

        $this->updateLiveHours();

    } else {

        $this->clockedIn = false;

        $this->timeId = null;

        $this->timeStarted = null;

        $this->totalHours = '00:00:00';

    }

}





    public function updateLiveHours()

    {

        if (!$this->clockedIn || !$this->timeStarted) {

            $this->totalHours = '00:00:00';

            return;

        }



        $tz = $this->tz();



        $start = Carbon::parse($this->timeStarted)->timezone($tz);

        $now   = now($tz);



        $diff = $start->diffInSeconds($now);



        $this->totalHours = sprintf(

            '%02d:%02d:%02d',

            intdiv($diff, 3600),

            intdiv($diff % 3600, 60),

            $diff % 60

        );

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

        ->paginate(10);



    // Group only the current page

    $records = $paginator->getCollection()

        ->groupBy(function ($r) use ($tz) {

            return $r->startTime

                ->copy()

                ->timezone($tz)

                ->format('Y-m-d');

        });



    // Replace collection inside paginator

    $paginator->setCollection($records);



    return view('livewire.employee-attendance', [

        'attendances' => $records,

        'attendancePaginator' => $paginator,

    ]);

}

}