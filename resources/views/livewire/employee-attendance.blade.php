@php
  use Carbon\Carbon;

  $user = auth()->user();
  $isClockedIn = !empty($clockedIn) && !empty($timeId);

  $lateMinutes = 0;
  $expectedTime = '--:--';

  if ($isClockedIn) {
      $punchIn = tz($user?->firstAttendanceToday?->created_at);
      
      //dd($user);

      $shiftStart = Carbon::parse($user->shift?->shift?->start_time)
        ->setDateFrom($punchIn);

      $shiftEnd = Carbon::parse($user->shift?->shift?->end_time)
        ->setDateFrom($punchIn);

      $lateMinutes = $shiftStart->diffInMinutes($punchIn);
      $expectedPunchOut = $shiftEnd->copy()->addMinutes($lateMinutes);

      $expectedTime = $expectedPunchOut->format('h:i A');
  }

  //dd($user->leaveBalance());
@endphp

@push('page-styles')

<style>
    .bg-inverse-blue {
        background: #0d83fd36;
        color: #0d83fd;
    }

    .clock {
      background:-webkit-linear-gradient(top, #f9f9f9,#f9f9f9);
      background:-moz-linear-gradient(top, #f9f9f9,#f9f9f9);
      background:linear-gradient(to bottom, #f9f9f9,#f9f9f9);
      width: 13rem;
      height: 13rem;
      margin: 1rem auto;
      border-radius: 50%;
      border: 6px solid #ffffff;
      position: relative;
      box-shadow: 0 1vw 3vw -1vw rgba(0,0,0,0.8);
    }

    /* center pin */
    .dot {
      width: 13px;
      height: 13px;
      background: #CCCCCC;
      border-radius: 50%;
      position: absolute;
      inset: 0;
      margin: auto;
      z-index: 20;
      box-shadow: 0 2px 4px -1px black;
    }

    /* =====================
       HANDS — TRUE CENTER
    ===================== */

    .hour-hand,
    .minute-hand,
    .second-hand {
      position: absolute;
      left: 50%;
      bottom: 50%;
      transform-origin: bottom center;
      will-change: transform;
    }

    .hour-hand {
      width: .3rem;
      height: 2.5rem;
      border-radius: 0 0 .9em .9em;
      background: #232425;
      box-shadow: #232425 0 0 2px;
      /*transform-origin: 0.1em 2.9em;*/
      z-index: 6;
    }

    .hour-hand:before {
        content: '';
        background: inherit;
        width: .8em;
        height: .5em;
        border-radius: 0 0 .8em .8em;
        box-shadow: #232425 0 0 1px;
        position: absolute;
        top: -.3em;
        left: -.25em;
        z-index: 6;
    }

    .hour-hand:after {
        content: '';
        width: 0;
        height: 0;
        border: .9em solid #232425;
        border-width: 0 .4em 1em .5em;
        border-left-color: transparent;
        border-right-color: transparent;
        position: absolute;
        top: -1.2em;
        left: -.3em;
        z-index: 6;
    }

    .minute-hand {
        width: .35em;
        height: 4.79em;
        border-radius: .5em;
        background: var(--bs-primary);
        box-shadow: #343536 0 0 2px;
        /*transform-origin: 0.1em 4.6em;*/
        z-index: 7;
    }

    .second-hand {
      width: .2em;
      height: 7.5em;
      border-radius: .1em .1em 0 0 / 10em 10em 0 0;
      background: #c00;
      /*background: var(--bs-primary);*/
      margin: 0 0 -2em -.1em;
      box-shadow: rgba(0, 0, 0, .8) 0 0 .2em;
      transform-origin: 0.1em 5.5em;
      z-index: 8;
    }

    .second-hand:after {
        content: '';
        width: .4em;
        height: .4em;
        border-radius: .7em;
        background: inherit;
        position: absolute;
        left: -.15em;
        bottom: 1.7em;
    }

    .second-hand:before {
        content: '';
        width: .5em;
        height: 1.1em;
        border-radius: .2em .2em .4em .4em/.2em .2em 2em 2em;
        box-shadow: rgba(0, 0, 0, .8) 0 0 .2em;
        background: inherit;
        position: absolute;
        left: -.15em;
        bottom: -1em;
    }

    /* =====================
       NUMBERS — PERFECT RADIAL
    ===================== */

    .clock .numbers {
      position: absolute;
      inset: 0;
      font-family: emoji;
    }

    .clock .numbers span {
      position: absolute;
      left: 50%;
      top: 50%;
      font-size: 16px;
      font-weight: 600;
      color: #222;
      transform:
        translate(-50%, -50%)
        rotate(calc(var(--i) * 30deg))
        translateY(-5.3rem)
        rotate(calc(var(--i) * -30deg));
      transform-origin: center;
    }

    .clock .numbers span:nth-child(n+10) {
      font-size: 16px;
    }

    /* =====================
       DATE & DAY — SAFE ZONE
    ===================== */

    .clock .info {
      position: absolute;
      width: 80px;
      height: 22px;
      background: #e3e3e3;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 600;
      text-align: center;
      line-height: 22px;
      color: #111;
      left: 50%;
      transform: translateX(-50%);
      z-index: 4;
    }

    .clock .date {
      top: 40px;
    }

    .clock .day {
      bottom: 40px;
    }

    .card {
        height: 23rem;
        max-height: 23rem;
    }

    .info-card {
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .working {
        background: #eef2ff;
    }

    .break {
        background: #fff4e6;
    }

    .big-time {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 15px;
    }

    .attendance-chart {
        position: relative;
        height: 254px;
        margin: auto;
    }

    .chart-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .chart-center h1 {
        font-size: 42px;
        font-weight: 700;
        color: #6b4cff;
        margin: 0;
    }

    .chart-center p {
        margin: 0;
        color: #666;
    }

    /* floating badges */

    .badge-box {
        position: absolute;
        background: white;
        padding: 6px 14px;
        border-radius: 20px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        font-size: 14px;
    }

    .present {
        left: -45px;
        top: 150px;
        color: #0aa76b;
    }

    .absent {
        right: -25px;
        top: 40px;
        color: #ef4444;
    }

    .late {
        right: -25px;
        top: 145px;
        color: #f59e0b;
    }

    .leave {
        right: -30px;
        top: 75px;
        color: #ef4444;
    }

    .attendance-header {
        background: #f3f5f9;
        border-radius: 12px;
        margin-bottom: 15px;
        padding-left: 15px;
        padding-right: 15px;
    }

    .attendance-row {
        background: #fff;
        border-radius: 14px;
        padding: 10px;
        margin-bottom: 7px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .tag {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .today {
        background: linear-gradient(90deg, #6c4cff, #ff2fb2);
        color: #fff;
    }

    .leave-box {
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 12px;
    }

    .leave-box .icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .shift-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f9fafb;
        padding: 10px 25px;
        border-radius: 12px;
    }

    .shift-box .icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
    }

    /* shift badge */
    .shift-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f3f4f6;
        padding: 8px 14px;
        border-radius: 20px;
        font-weight: 500;
    }

    .shift-badge .shift-dot {
        width: 8px;
        height: 8px;
        background: #22c55e;
        border-radius: 50%;
    }

    /* Tasks */
    .task-item {
        padding: 16px;
        border-radius: 12px;
        background: #f9fafb;
        margin-bottom: 14px;
    }

    .progress {
        height: 6px;
        background: #e5e7eb;
        border-radius: 10px;
    }

    .progress-bar {
        border-radius: 10px;
    }

    /* Completed */
    .task-item.completed {
        background: #f0fdf4;
    }

    /* Button */
    .add-btn {
        background: linear-gradient(90deg, #9333ea, #3b82f6);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 6px 14px;
    }

    /* Salary */
    .salary-box {
        display: flex;
        gap: 12px;
        background: #e6f7ef;
        padding: 14px;
        border-radius: 12px;
    }

    .salary-box .icon {
        width: 45px;
        height: 45px;
        background: #19b47b;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 18px;
    }

    /* Payslip Button */
    .payslip-btn {
        background: linear-gradient(90deg, #9333ea, #3b82f6);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 10px;
    }

    @media (max-width: 500px) {
        #attendanceChart {
            width: 220px;
        }
    }

</style>

@endpush

<div>

    <div class="row g-2">

        <!-- LEFT PANEL -->
        <div class="col-lg-7">
            <div class="bg-white shadow-sm rounded-4 p-4 border-0 mb-3" id="clockTimmer">

                <div class="row">

                    <!-- CLOCK -->
                    <div class="col-md-5 text-center">

                        <div class="date-line">
                            {{ today()->format('l, F d, Y') }}
                        </div>

                        <div class="punch-info">
                            <div class="clock">
                              <div>
                                <div class="info date"></div>
                                <div class="info day"></div>
                              </div>

                              <div class="dot"></div>

                              <div>
                                <div class="hour-hand"></div>
                                <div class="minute-hand"></div>
                                <div class="second-hand"></div>
                              </div>

                              <div class="numbers">

                                <span style="--i:1">1</span>
                                <span style="--i:2">2</span>
                                <span style="--i:3">3</span>
                                <span style="--i:4">4</span>
                                <span style="--i:5">5</span>
                                <span style="--i:6">6</span>
                                <span style="--i:7">7</span>
                                <span style="--i:8">8</span>
                                <span style="--i:9">9</span>
                                <span style="--i:10">10</span>
                                <span style="--i:11">11</span>
                                <span style="--i:12">12</span>
                              </div>

                              <!-- <div class="diallines"></div> -->
                            </div>
                        </div>

                        <div class="big-time" id="time">
                            00:00:00 PM
                        </div>

                        <!-- <div class="text-muted">
                            Not Punched In
                        </div> -->

                    </div>


                    <!-- WORK INFO -->
                    <div class="col-md-7">

                        <div class="info-card working mt-3 mt-lg-0 mt-md-0">
                            <i class="bi bi-stopwatch p-2 border rounded bg-primary text-white fs-5 me-3"></i>
                            <div>
                                <div class="small text-muted">Today's Working Hours</div>
                                <div class="fs-4 fw-bold" id="workingHours">
                                    00:00:00
                                </div>
                            </div>

                        </div>


                        <div class="info-card break">
                            <i class="bi bi-cup-hot p-2 border rounded bg-warning text-white fs-5 me-3"></i>
                            <div>
                                <div class="small text-muted">Break Time</div>
                                <div class="fs-4 fw-bold" id="breakTime">
                                    {{ $this->getBreakTimeToday() }}
                                </div>
                            </div>

                        </div>


                        <div class="row mt-3">

                            <div class="col-6">
                                <div class="mini-box">
                                    <div>Punch In</div>

                                    <b>{{ tz($user?->firstAttendanceToday?->created_at, 'h:i A') ?? '--:--' }}</b>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mini-box">
                                    <div>Expected Out</div>
                                    <b>{{ $expectedTime ?? '--:--'  }}</b>
                                </div>
                            </div>

                        </div>


                        {{-- <div class="mt-3 d-flex gap-3">

                            <button 
                                type="button" data-bs-toggle="modal" data-bs-target="#clockin_modal"
                                class="btn {{ $isClockedIn ? 'btn-light' : 'btn-primary' }} flex-fill"
                                {{ $isClockedIn ? 'disabled' : '' }}
                            >
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Punch In
                            </button>

                            <button 
                                type="button" data-url="{{ route('clockout-modal', ['timeId' => $timeId]) }}" data-ajax-modal="true" data-size="lg" data-title="{{ __('Add Work Report') }}" data-timeid="{{ $timeId }}"
                                class="btn {{ $isClockedIn ? 'btn-primary' : 'btn-light' }} flex-fill"
                                {{ !$isClockedIn ? 'disabled' : '' }}
                            >
                                <i class="fa-solid fa-right-from-bracket me-1"></i> Punch Out
                            </button>

                        </div> --}}

                        <div class="mt-3 d-flex gap-3">

                            @if(!$clockedIn)
                                <button 
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#clockin_modal"
                                    class="btn btn-primary flex-fill">
                                    <i class="fa-solid fa-right-to-bracket me-1"></i> Punch In
                                </button>
                            @endif

                            @if($clockedIn && !$onBreak)
                                <button 
                                    wire:click="breakOut"
                                    class="btn btn-warning flex-fill">
                                    <i class="bi bi-cup-hot me-1"></i> Break Out
                                </button>

                                <button 
                                    type="button"
                                    data-url="{{ route('clockout-modal', ['timeId' => $timeId]) }}" data-ajax-modal="true" data-size="lg" data-title="{{ __('Add Work Report') }}" data-timeid="{{ $timeId }}"
                                    class="btn btn-primary flex-fill">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Punch Out
                                </button>
                            @endif

                            @if($clockedIn && $onBreak)
                                <button 
                                    wire:click="breakIn"
                                    class="btn btn-success flex-fill">
                                    <i class="bi bi-play-fill me-1"></i> Break In
                                </button>

                                <button 
                                    type="button"
                                    data-url="{{ route('clockout-modal', ['timeId' => $timeId]) }}" data-ajax-modal="true" data-size="lg" data-title="{{ __('Add Work Report') }}" data-timeid="{{ $timeId }}"
                                    class="btn btn-primary flex-fill">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Punch Out
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right PANEL -->
        <div class="col-lg-5">
            <div class="card shadow-sm rounded-4 border-0 p-4 mb-3">

                @php
                  $daysInMonth = Carbon::now()->daysInMonth;
                  $totalPresentInCurrentMonth = $user->presentInCurrentMonthCount();

                  $presentPer = ($daysInMonth > 0)
                    ? ($totalPresentInCurrentMonth / $daysInMonth) * 100
                    : 0;
                @endphp

                <h4 class="fw-bold mb-1">Attendance Overview</h4>
                <p class="text-muted mb-4">{{ now()->format('F Y') }}</p>

                <div class="attendance-chart">
                    <canvas id="attendanceChart"></canvas>
                    <div class="chart-center">
                        <h1>{{ number_format($presentPer) }}%</h1>
                        <p>Present</p>
                    </div>

                    <!-- floating labels -->

                    <div class="badge-box present">
                        <b>{{ $totalPresentInCurrentMonth }}</b> present
                    </div>

                    <!-- <div class="badge-box absent">
                        <b>1</b> Absent
                    </div> -->

                    <div class="badge-box late">
                        <b>{{ $user->lateDaysCountInCurrentMonth() }}</b> Late
                    </div>

                    <div class="badge-box leave">
                        <b>{{ number_format($user->usedLeaveDaysInCurrentMonth(), 2) }}</b> Leave
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-4 shift-card py-2 px-4 mb-3 mt-0">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">My Shifts</h4>

                {{-- <button class="btn btn-primary">
                    Request Shift Change →
                </button> --}}
            </div>

            <!-- SHIFT CONTENT -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">

                <!-- SHIFT BADGE -->
                <div class="shift-badge">
                    <span class="shift-dot"></span>
                    {{ $user->shift?->shift?->name . ' Shift' ?? 'N/A' }}
                </div>

                <!-- START TIME -->
                <div class="shift-box">
                    <div class="icon bg-inverse-blue">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <div class="label">START TIME</div>
                        <div class="value">{{ $user->shift?->shift?->start_time ?? '--' }}</div>
                    </div>
                </div>

                <!-- END TIME -->
                <div class="shift-box">
                    <div class="icon bg-inverse-danger">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="label">END TIME</div>
                        <div class="value">{{ $user->shift?->shift?->end_time ?? '--' }}</div>
                    </div>
                </div>

                <!-- BREAK -->
                <div class="shift-box">
                    <div class="icon bg-inverse-warning">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <div>
                        <div class="label">BREAK TIME</div>
                        <div class="value">{{ $user->shift?->shift?->break_minutes . ' Mins' ?? '--' }}</div>
                    </div>
                </div>

                <!-- GRACE -->
                <div class="shift-box">
                    <div class="icon bg-inverse-success">
                        <i class="bi bi-stopwatch"></i>
                    </div>
                    <div>
                        <div class="label">GRACE TIME</div>
                        <div class="value">{{ $user->shift?->shift?->grace_minutes . ' Mins' ?? '--' }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="row g-2">

        <!-- LEFT PANEL -->
        <div class="col-lg-7">

            <div class="shadow-sm rounded-4 border-0 bg-white p-4 mb-3">

                <!-- HEADER -->

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h4 class="fw-bold mb-0">Recent Attendance</h4>
                        <p class="text-muted small mb-0">Last 30 days attendance records</p>
                    </div>

                    {{-- <div class="d-flex gap-2">

                        <button class="btn btn-light filter-btn">
                            <i class="bi bi-funnel"></i> Filter
                        </button>

                        <button class="btn view-btn">
                            View All →
                        </button>
                    </div> --}}

                </div>


                <!-- TABLE HEADER -->

                <div class="attendance-header row text-muted small fw-semibold py-2">

                    <div class="col-md-3">Date</div>
                    <div class="col-md-2">Punch In</div>
                    <div class="col-md-2">Punch Out</div>
                    <div class="col-md-2">Total Hours</div>
                    <div class="col-md-3 text-end">Status</div>

                </div>


                <!-- ROW -->

                @forelse ($attendances as $date => $records)

                 {{-- dd($records) --}}

                    @php
                        $tz = $this->tz;

                        $dayName = $date ? \Carbon\Carbon::parse($date)->format('l') : '';

                        $totalMinutes = $records->sum(function ($r) use ($tz) {

                            if (!$r->startTime instanceof \Carbon\Carbon ||
                                !$r->endTime instanceof \Carbon\Carbon) {
                                return 0;
                            }

                            $start = $r->startTime->copy()->timezone($tz);
                            $end   = $r->endTime->copy()->timezone($tz);
                            return $start->diffInMinutes($end);
                        });

                        $hours = intdiv($totalMinutes, 60);
                        $minutes = $totalMinutes % 60;
                        $rawPunchIn = $records->min('startTime');
                        $punchIn = $rawPunchIn instanceof \Carbon\Carbon
                            ? $rawPunchIn->copy()->timezone($tz)
                            : null;
                        $rawPunchOut = $records->whereNotNull('endTime')->max('endTime');
                        $punchOut = $rawPunchOut instanceof \Carbon\Carbon
                            ? $rawPunchOut->copy()->timezone($tz)
                            : null;

                        $recordDate = \Carbon\Carbon::parse($date, $tz);
                    @endphp

                    <div class="attendance-row row align-items-center">

                        <div class="col-md-3">
                            <b>{{ format_date($date) }}</b>
                            <div class="small text-muted">{{ $dayName }}</div>
                        </div>

                        <div class="col-md-2">{{ $punchIn ? $punchIn->format('h:i A') : '' }}</div>

                        <div class="col-md-2 text-muted">{{ $punchOut ? $punchOut->format('h:i A') : '--' }}</div>

                        <div class="col-md-2 fw-semibold">{{ sprintf('%02d:%02d', $hours, $minutes) }}</div>

                        <div class="col-md-3 text-end">
                            <span class="badge bg-inverse-success">Completed</span>
                        </div>

                    </div>

                @empty
                    <div class="attendance-row row align-items-center">
                        <div class="text-center text-muted">
                            No attendance records found
                        </div>
                    </div>
                @endforelse

                <div class="mt-3">
                    {{ $attendancePaginator->links() }}
                </div>
            </div>

            {{-- <div class="shadow-sm rounded-4 border-0 bg-white p-4 mb-3">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Recent Attendance</h4>
                        <p class="text-muted small mb-0">Last 30 days attendance records</p>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr class="text-muted small fw-semibold">
                                <th>Date</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                                <th>Total Hours</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($attendances as $date => $records)

                                @php
                                    $tz = $this->tz;

                                    $totalMinutes = $records->sum(function ($r) use ($tz) {
                                        if (!$r->startTime instanceof \Carbon\Carbon ||
                                            !$r->endTime instanceof \Carbon\Carbon) {
                                            return 0;
                                        }

                                        $start = $r->startTime->copy()->timezone($tz);
                                        $end   = $r->endTime->copy()->timezone($tz);
                                        return $start->diffInMinutes($end);
                                    });

                                    $hours = intdiv($totalMinutes, 60);
                                    $minutes = $totalMinutes % 60;

                                    $rawPunchIn = $records->min('startTime');
                                    $punchIn = $rawPunchIn instanceof \Carbon\Carbon
                                        ? $rawPunchIn->copy()->timezone($tz)
                                        : null;

                                    $rawPunchOut = $records->whereNotNull('endTime')->max('endTime');
                                    $punchOut = $rawPunchOut instanceof \Carbon\Carbon
                                        ? $rawPunchOut->copy()->timezone($tz)
                                        : null;

                                    $dayName = \Carbon\Carbon::parse($date)->format('l');
                                @endphp

                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ format_date($date) }}</div>
                                        <small class="text-muted">{{ $dayName }}</small>
                                    </td>

                                    <td>
                                        {{ $punchIn ? $punchIn->format('h:i A') : '--' }}
                                    </td>

                                    <td class="text-muted">
                                        {{ $punchOut ? $punchOut->format('h:i A') : '--' }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ sprintf('%02d:%02d', $hours, $minutes) }}
                                    </td>

                                    <td class="text-end">
                                        <span class="badge bg-inverse-success">Completed</span>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No attendance records found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="mt-3">
                    {{ $attendancePaginator->links() }}
                </div>

            </div> --}}

        </div>

        <!-- Right PANEL -->
        <div class="col-lg-5">

            <div class="bg-white border-0 shadow-sm rounded-4 p-4 mb-3">

                <!-- Header -->
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-megaphone me-2 fs-5 text-primary"></i>
                    <h4 class="fw-bold mb-0">Announcements</h4>
                </div>

                <!-- Scrollable Area -->
                <div class="overflow-auto" style="max-height: 310px; min-height: 310px;">

                    @if ($upcomingBirthdays->isEmpty() && $upcomingWorkAnniversaries->isEmpty())

                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 py-5">
        
                        <div class="mb-3">
                            <i class="bi bi-megaphone text-secondary" style="font-size: 40px;"></i>
                        </div>

                        <h6 class="fw-semibold mb-1">No Announcements</h6>
                        <p class="text-muted small mb-0">
                            There are no birthdays or work anniversaries coming up.
                        </p>

                    </div>
                    @else

                        @if ($upcomingBirthdays->count() > 0)
                            @foreach ($upcomingBirthdays as $index => $bUser)
                            @php
                                $years = Carbon::parse($bUser->dob)->diffInYears(Carbon::today());
                                $birthday = tz($bUser->dob, 'm-d');
                            @endphp
                            <div
                                class="d-flex justify-content-between align-items-center shadow-sm rounded-4 p-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <img src="{{ $bUser->avatar ? asset('storage/' . $bUser->avatar) : asset('images/user.jpg') }}">
                                    </div>
                                    <div>
                                        <small class="text-muted fw-semibold">BIRTHDAY</small>
                                        <div class="fw-semibold">{{ $bUser->fullname }}</div>
                                        <small class="text-muted">{{ $bUser->designation?->name }}</small>
                                    </div>
                                </div>
                                <span class="tag {{ $birthday == $today ? 'today' : 'bg-light' }}">
                                    {{
                                        $birthday == $today ? 'Today 🥳'
                                        : ($birthday == now()->addDay()->format('m-d') ? 'Tomorrow'
                                        : tz($bUser->dob, 'd M'))
                                    }}
                                </span>
                            </div>
                            @endforeach
                        @endif 

                        <!-- Work anniversary -->
                        @if ($upcomingWorkAnniversaries->count() > 0)

                            @foreach ($upcomingWorkAnniversaries as $index => $anni)

                            @php
                                $years = Carbon::parse($anni->date_joined)->diffInYears(Carbon::today());
                                $anniversary = tz($anni->date_joined, 'm-d');
                            @endphp

                            <div
                                class="d-flex justify-content-between align-items-center shadow-sm rounded-4 p-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <img src="{{ $anni->avatar ? asset('storage/' . $anni->avatar) : asset('images/user.jpg') }}">
                                    </div>
                                    <div>
                                        <small class="text-muted fw-semibold">WORK ANNIVERSARY</small>
                                        <div class="fw-semibold">{{ $anni->fullname }}</div>
                                        <small class="text-muted">{{ $anni->designation?->name }}</small>
                                    </div>
                                </div>
                                <span class="tag {{ $anniversary == $today ? 'today' : 'bg-light' }}">
                                    {{
                                        $anniversary == $today ? 'Today 🥳'
                                        : ($anniversary == now()->addDay()->format('m-d') ? 'Tomorrow'
                                        : tz($anni->date_joined, 'd M'))
                                    }}
                                </span>
                            </div> 

                            @endforeach
                        @endif
                    @endif
                </div>

                <!-- Button -->
                {{-- <button class="btn w-100 mt-2 text-white fw-semibold rounded-3 btn-primary">
                    View All Announcements →
                </button> --}}
            </div>

            <div class="bg-white border-0 shadow-sm rounded-4 p-4 mb-3">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="fw-bold mb-0">My Tasks</h4>
                        <small class="text-muted">Track your daily activities</small>
                    </div>
                    {{-- <button class="btn add-btn">Add Task +</button> --}}
                </div>

                <!-- Task Item -->

                @forelse ($myTasks as $task)

                @php

                    $statusMap = [
                        1 => ['label' => 'Pending',      'class' => 'bg-secondary', 'percent' => 0],
                        2 => ['label' => 'In Progress',  'class' => 'bg-primary',   'percent' => 50],
                        3 => ['label' => 'On Hold',      'class' => 'bg-warning',   'percent' => 30],
                        4 => ['label' => 'Completed',    'class' => 'bg-success',   'percent' => 100],
                        5 => ['label' => 'Cancelled',    'class' => 'bg-danger',    'percent' => 0],
                    ];

                    $priorityMap = [
                            1 => ['label' => 'High', 'class' => 'bg-danger'],
                            2 => ['label' => 'Medium', 'class' => 'bg-warning'],
                            3 => ['label' => 'Low', 'class' => 'bg-primary'],
                        ];

                    $priority   = $priorityMap[$task->priority] ?? ['label' => 'Unknown', 'class' => 'badge-soft-dark'];
                    $status     = $statusMap[$task->status] ?? ['label' => 'Unknown', 'class' => 'badge-soft-dark'];
                @endphp

                <div class="task-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>{{ $task->name }}</h6>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge {{ $priority['class'] }}">{{ $priority['label'] ?? '' }}</span>
                                <small class="text-muted">{{ date('d M Y', strtotime($task->startDate)) }} - {{ date('d M Y', strtotime($task->endDate)) }}</small>
                            </div>
                        </div>
                        <div>
                            <div class="text-end">
                                <b>{{ $status['percent'] }}%</b>
                            </div>
                            <span class="badge {{ $status['class'] }}">{{ $status['label'] ?? '' }}</span>
                        </div>
                        
                    </div>
                    <div class="progress mt-3">
                        <div class="progress-bar {{ $status['class'] }}" 
                             style="width: {{ $status['percent'] }}%;">
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <div class="empty-state-icon mb-2">
                        <i class="bi bi-inbox text-secondary" style="font-size: 40px;"></i>
                    </div>

                    <p class="mb-1 fw-medium">Nothing here yet</p>
                    <small class="text-muted">No tasks have been assigned to you.</small>
                </div>
                @endforelse

            </div>

            {{-- <div class="bg-white border-0 shadow-sm rounded-4 p-4 position-relative mb-3">

                <div class="mb-3">
                    <h4 class="fw-bold mb-0">Leave Summary</h4>
                    <small class="text-muted">2026 Balance</small>
                </div>

                <!-- Available -->
                <div class="leave-box d-flex align-items-center gap-3 bg-inverse-success">
                    <div class="icon bg-success"><i class="bi bi-calendar"></i></div>
                    <div>
                        <small class="text-dark">Available Leaves</small>
                        <h5 class="mb-0 fs-3 text-dark">{{ $user->leaveBalance() }}</h5>
                    </div>
                </div>

                <!-- Used -->
                <div class="leave-box d-flex align-items-center gap-3 bg-inverse-warning">
                    <div class="icon bg-warning"><i class="bi bi-clock"></i></div>
                    <div>
                        <small class="text-dark">Used Leaves</small>
                        <h5 class="mb-0 fs-3 text-dark">{{ $user->usedLeaveDays() }}</h5>
                    </div>
                </div>

                <!-- Pending -->
                <div class="leave-box d-flex align-items-center gap-3 bg-inverse-blue">
                    <div class="icon" style="background: #0d83fd;"><i class="bi bi-check-circle"></i></div>
                    <div>
                        <small class="text-dark">Pending Approval</small>
                        <h5 class="mb-0 fs-3 text-dark">2</h5>
                    </div>
                </div>

                <!-- Button -->
                <button class="btn btn-primary w-100 mt-3">
                    + Apply for Leave
                </button>

                <!-- Leave Types -->
                <div class="mt-4">
                    <small class="text-muted">Leave Types</small>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Casual Leave</span>
                        <b>6/10</b>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Sick Leave</span>
                        <b>2/7</b>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Earned Leave</span>
                        <b>0/5</b>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <h4 class="fw-bold mb-0">Salary & Payslip</h4>
                <small class="text-muted">February 2026</small>

                <!-- Salary Box -->
                <div class="salary-box mt-3">
                    <div class="icon">$</div>
                    <div>
                        <small>Latest Credited</small>
                        <h4 class="mb-0">₹ 45,000</h4>
                        <small class="text-success">✔ Credited on Feb 1, 2026</small>
                    </div>
                </div>

                <!-- Details -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between">
                        <span>Payroll Status</span>
                        <span class="badge bg-success-subtle text-success">Processed</span>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Gross Salary</span>
                        <b>₹ 55,000</b>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Deductions</span>
                        <b class="text-danger">- ₹ 10,000</b>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold">Net Salary</span>
                        <b>₹ 45,000</b>
                    </div>
                </div>

                <!-- Button -->
                <button class="btn payslip-btn w-100 mt-3">
                    ⬇ Download Payslip
                </button>

            </div> --}}

            <div class="bg-white border-0 shadow-sm rounded-4 overflow-hidden mb-3">

                <!-- Header -->
                <div class="p-3 text-white fw-semibold welcome-card">
                    Reporting Managers
                </div>

                <div class="row g-0">

                    <!-- Manager -->
                    <div class="col-md-6 p-4 border-end">
                        <p class="text-primary small fw-semibold">Reporting Manager</p>

                        <div class="d-flex align-items-center mb-3">
                            <img class="avatar" src="{{ asset('storage/'. $user->reportingManager?->avatar) }}">
                            <div class="ms-3">
                                <h6 class="mb-0 fw-semibold">{{ $user->reportingManager?->fullname }}</h6>
                                <small class="text-muted">{{ $user->reportingManager?->designation?->name }}</small>
                            </div>
                        </div>

                        <div class="info-item d-flex gap-3 mb-2 align-items-center">
                            <i class="bi bi-envelope bg-inverse-warning px-2 rounded-2 pt-1"></i>
                            <div>
                                <small>Email</small>
                                <div>{{ $user->reportingManager?->email }}</div>
                            </div>
                        </div>

                        <div class="info-item d-flex gap-3 mb-2 align-items-center">
                            <i class="bi bi-telephone bg-inverse-warning px-2 rounded-2 pt-1"></i>
                            <div>
                                <small>Phone</small>
                                <div>{{ $user->reportingManager?->phone }}</div>
                            </div>
                        </div>

                        <div class="info-item d-flex gap-3 mb-2 align-items-center">
                            <i class="bi bi-building bg-inverse-warning px-2 rounded-2 pt-1"></i>
                            <div>
                                <small>Department</small>
                                <div>{{ $user->reportingManager?->department?->name }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sub Manager -->
                    <div class="col-md-6 p-4">
                        <p class="text-primary small fw-semibold">Sub Reporting Manager</p>

                        <div class="d-flex align-items-center mb-3">
                            <img class="avatar" src="{{ asset('storage/'. $user->subReportingManager?->avatar) }}">
                            <div class="ms-3">
                                <h6 class="mb-0 fw-semibold">{{ $user->subReportingManager?->fullname }}</h6>
                                <small class="text-muted">{{ $user->subReportingManager?->designation?->name }}</small>
                            </div>
                        </div>

                        <div class="info-item d-flex gap-3 mb-2 align-items-center">
                            <i class="bi bi-envelope bg-inverse-warning px-2 rounded-2 pt-1"></i>
                            <div>
                                <small>Email</small>
                                <div>{{ $user->subReportingManager?->email }}</div>
                            </div>
                        </div>

                        <div class="info-item d-flex gap-3 mb-2 align-items-center">
                            <i class="bi bi-telephone bg-inverse-warning px-2 rounded-2 pt-1"></i>
                            <div>
                                <small>Phone</small>
                                <div>{{ $user->subReportingManager?->phone }}</div>
                            </div>
                        </div>

                        <div class="info-item d-flex gap-3 mb-2 align-items-center">
                            <i class="bi bi-building bg-inverse-warning px-2 rounded-2 pt-1"></i>
                            <div>
                                <small>Department</small>
                                <div>{{ $user->subReportingManager?->department?->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div wire:ignore.self class="modal custom-modal fade" id="clockin_modal" role="dialog">

        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">

              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close">

                <span aria-hidden="true">&times;</span>
              </button>

            </div>

            <div class="modal-body">

              <form wire:submit.prevent="clockin" method="post" enctype="multipart/form-data">

                @csrf

                <div x-data="{forProject: false}">

                    <x-form.input-block>

                    <div class="status-toggle">

                        <x-form.label>{{ __('For Project ?') }}</x-form.label>

                        <x-form.input type="checkbox" id="forProject" class="check" @click="forProject =! forProject" name="forProject" wire:model="forProject" />

                        <label for="forProject" class="checktoggle">checkbox</label>
                    </div>

                    </x-form.input-block>

                    <div x-show="forProject">

                        <x-form.input-block>

                            <x-form.label required>{{ __('Project') }}</x-form.label>

                            <select class="form-control" name="project" wire:model="project">

                                <option value="">{{ __('Select Project') }}</option>

                                @foreach ($projects as $project)

                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </x-form.input-block>
                    </div>



                    {{-- <x-form.input-block>

                        <x-form.label required>{{ __('Shift') }}</x-form.label>

                        <select class="form-control" name="shift" id="shift" required>

                            <option value="">{{ __('Select Shift') }}</option>

                            @foreach (\App\Models\Shift::get() as $shift)

                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>

                            @endforeach
                        </select>
                    </x-form.input-block> --}}
                </div>

                <div class="submit-section mb-3">
                    <x-form.button type="submit" class="btn btn-primary submit-btn">{{ __('Start') }}</x-form.button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>


    @push('page-scripts')

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const timeStarted = @json($timeStarted);
            const tenantTimezone = @json($this->tz);

            const weekday = [
                "Sunday","Monday","Tuesday",
                "Wednesday","Thursday","Friday","Saturday"
            ];

            function clock() {

                const now = new Date(
                    new Date().toLocaleString("en-US", { timeZone: tenantTimezone })
                );

                const h  = now.getHours();
                const m  = now.getMinutes();
                const s  = now.getSeconds();
                const ms = now.getMilliseconds();

                const date  = now.getDate();
                let month   = now.getMonth() + 1;
                const year  = now.getFullYear();

                const sDeg = (s + ms / 1000) * 6;
                const mDeg = (m + s / 60) * 6;
                const hDeg = (h % 12 + m / 60) * 30;

                const hEl = document.querySelector('.hour-hand');
                const mEl = document.querySelector('.minute-hand');
                const sEl = document.querySelector('.second-hand');
                const dateEl = document.querySelector('.date');
                const dayEl = document.querySelector('.day');

                hEl.style.transform = `translateX(-50%) rotate(${hDeg}deg)`;
                mEl.style.transform = `translateX(-50%) rotate(${mDeg}deg)`;
                sEl.style.transform = `translateX(-50%) rotate(${sDeg}deg)`;

                if (month < 10) month = "0" + month;
                dateEl.innerHTML = `${date}/${month}/${year}`;
                dayEl.innerHTML = weekday[now.getDay()];
            }

            function timmer() {
                let now = new Date();
                let time = now.toLocaleTimeString();
                $("#time").text(time);
            }

            function updateWorkingHours() {

                if (!timeStarted) return;

                // Convert start time to user timezone
                const start = new Date(
                    new Date(timeStarted).toLocaleString("en-US", { timeZone: tenantTimezone })
                );

                const now = new Date(
                    new Date().toLocaleString("en-US", { timeZone: tenantTimezone })
                );

                let diff = Math.floor((now - start) / 1000);

                if (diff < 0) diff = 0;

                const hours   = Math.floor(diff / 3600);
                const minutes = Math.floor((diff % 3600) / 60);
                const seconds = diff % 60;

                const formatted =
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');

                document.getElementById("workingHours").innerText = formatted;
            }

            // Run every second
            setInterval(updateWorkingHours, 1000);

            // Run immediately on load
            updateWorkingHours();

            setInterval(timmer, 1000);
            setInterval(clock, 1000);

            // CHART
            const shadowPlugin = {
                id: 'shadowPlugin',
                beforeDatasetsDraw(chart) {
                    const { ctx } = chart;

                    ctx.save();
                    ctx.shadowColor = 'rgba(0,0,0,0.15)';
                    ctx.shadowBlur = 20;
                    ctx.shadowOffsetX = 0;
                    ctx.shadowOffsetY = 10;
                },
                afterDatasetsDraw(chart) {
                    chart.ctx.restore();
                }
            };

            new Chart(
                document.getElementById("attendanceChart"),
                {
                    type: 'doughnut',

                    data: {
                        labels: ["Present", "Leave", "Late"],

                        datasets: [{

                            data: ['{{ $user->presentInCurrentMonthCount() }}', '{{ number_format($user->usedLeaveDaysInCurrentMonth(), 2) }}', '{{ $user->lateDaysCountInCurrentMonth() }}'],

                            backgroundColor: [
                                "#19b47b",
                                "#ff4d4d",
                                "#f59e0b"
                            ],

                            borderWidth: 0
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: "75%",
                        rotation: 120,

                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    },
                    plugins: [shadowPlugin]

                }
            );

        </script>
    @endpush

    @script

        <script defer type="module">

            Livewire.on('Notification', (param) => {
                Toastify({
                    text: param,
                    className: "success",
                }).showToast()
            })
        </script>
    @endscript

</div>