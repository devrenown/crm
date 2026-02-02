@push('page-styles')
<style>
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
      transform-origin: 0.1em 2.9em;
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
        background: #343536;
        box-shadow: #343536 0 0 2px;
        transform-origin: 0.1em 4.6em;
        z-index: 7;
    }

    .second-hand {
      width: .2em;
      height: 7.5em;
      border-radius: .1em .1em 0 0 / 10em 10em 0 0;
      /*background: #c00;*/
      background: var(--bs-primary);
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
        height: 22rem;
        max-height: 22rem;
    }

</style>

@endpush

<div>
    <div class="row">
        <div class="col-md-4">
            <div class="card punch-status">
                <div class="card-body">
                    <h5 class="card-title">
                        <div class="text-center">
                           
                            {{ __('Timesheet') }} <small class="text-muted">{{ format_date(Date('Y-m-d')) }}</small>
                        </div>
                    </h5>
                    
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
                    <div class="punch-btn-section d-flex align-items-center gap-2 justify-content-between">
                        @if (!empty($clockedIn) && !empty($timeId))
                        <a href="javascript:void(0)" data-url="{{ route('clockout-modal', ['timeId' => $timeId]) }}" data-ajax-modal="true" data-size="lg" data-title="{{ __('Add Work Report') }}" data-timeid="{{ $timeId }}" class="btn btn-primary punch-btn"><i class="fa-solid fa-right-from-bracket me-1"></i> {{ __('Clock Out') }}</a> 
                        
                        @else
                        <button type="button" data-bs-toggle="modal" data-bs-target="#clockin_modal" class="btn btn-primary punch-btn"><i class="fa-solid fa-right-to-bracket me-1"></i> {{ __('Clock In') }}</button>
                        @endif

                        <div class="text-center">
                            <span>{{ $totalHours }} {{ \Str::plural(__('Hour'), intval($totalHours)) }}</span> 
                        </div>
                    </div>

                    {{-- <div class="statistics">
                        <div class="row">
                            @if (!empty($clockedIn) && !empty($timeStarted))
                            <div class="col-md-12 text-center">
                                <div class="stats-box">
                                    <p>{{ __('Started At') }}</p>
                                    <h6>{{ format_date($timeStarted,'H:i:s A') }}</h6>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card att-statistics">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Statistics') }}</h5>

                    @php
                      $minHoursInDay = 9;
                      $minHoursInWeek = 54;
                      $minHoursInMonth = 270;
                    @endphp

                    <div class="stats-list">

                        <div class="stats-info">
                            <p>
                                {{ __('Today') }}
                                <strong>
                                    <small>
                                        {{ $totalHoursToday }}/{{ $minHoursInDay }} Hours
                                    </small>
                                </strong>
                            </p>

                            @php
                                $todayHoursOnly = (int) explode(':', $totalHoursToday)[0];
                                $dayPercent = min(($todayHoursOnly / $minHoursInDay) * 100, 100);
                            @endphp

                            <div class="progress">
                                <div class="progress-bar bg-primary"
                                     role="progressbar"
                                     aria-valuenow="{{ $todayHoursOnly }}"
                                     aria-valuemin="{{ $dayPercent }}"
                                     aria-valuemax="100" 
                                     style="width: {{ $dayPercent }}%;">
                                </div>
                            </div>
                        </div>

                        <div class="stats-info">
                            <p>
                                {{ __('This Week') }}
                                <strong>
                                    <small>
                                        {{ $totalHoursThisWeek }}/{{ $minHoursInWeek }} Hours
                                    </small>
                                </strong>
                            </p>

                            @php
                                $weekHoursOnly = (int) explode(':', $totalHoursThisWeek)[0];
                                $weekPercent = min(($weekHoursOnly / $minHoursInWeek) * 100, 100);
                            @endphp

                            <div class="progress">
                                <div class="progress-bar bg-warning"
                                     role="progressbar"
                                     aria-valuenow="{{ $weekHoursOnly }}"
                                     aria-valuemin="{{ $weekPercent }}"
                                     aria-valuemax="100" 
                                     style="width: {{ $weekPercent }}%;">
                                </div>
                            </div>
                        </div>

                        <div class="stats-info">
                            <p>
                                {{ __('This Month') }}
                                <strong>
                                    <small>
                                        {{ $totalHoursThisMonth }}/{{ $minHoursInMonth }} Hours
                                    </small>
                                </strong>
                            </p>

                            @php
                                $monthHoursOnly = (int) explode(':', $totalHoursThisMonth)[0];
                                $weekPercent = min(($monthHoursOnly / $minHoursInMonth) * 100, 100);
                            @endphp

                            <div class="progress">
                                <div class="progress-bar bg-success"
                                     role="progressbar"
                                     aria-valuenow="{{ $monthHoursOnly }}"
                                     aria-valuemin="{{ $weekPercent }}"
                                     aria-valuemax="100" 
                                     style="width: {{ $weekPercent }}%;">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="card recent-activity">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Today Activity') }}</h5>
                    <ul class="res-activity-list">
                        @if (!empty($todayActivity))
                            @foreach ($todayActivity as $item)
                            <li>
                                <p class="mb-0">{{ __('Punch In at') }}</p>
                                <p class="res-activity-time">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ !empty($item->startTime) ? $item->startTime->format('H:i A'): '' }}
                                </p>
                            </li>
                            @if (!empty($item->endTime))
                            <li>
                                <p class="mb-0">{{ __('Punch Out at') }}</p>
                                <p class="res-activity-time">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ !empty($item->endTime) ? $item->endTime->format('H:i A'): '' }}
                                </p>
                            </li>
                            <hr>
                            @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Date') }} </th>
                            <th>{{ __('Punch In') }}</th>
                            <th>{{ __('Punch Out') }}</th>
                            <th>{{ __('Total Hours') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @if (!empty($attendances))
                            @foreach ($attendances as $date => $records)

                                @php
                                    // 1. Calculate total minutes for the day
                                    $totalMinutes = 0;
                                    foreach ($records as $r) {
                                        $totalMinutes += ($r['totalHours'] * 60) + $r['totalMinutes'];
                                    }
                                    $hours = floor($totalMinutes / 60);
                                    $minutes = $totalMinutes % 60;

                                    $startTimes = array_column($records, 'created_at'); // Extract all 'created_at' values
                                    $endTimes   = array_column($records, 'endTime');     // Extract all 'endTime' values

                                    $punchIn  = !empty($startTimes) ? min($startTimes) : null;
                                    $punchOut = !empty($endTimes) ? max($endTimes) : null;

                                    $recordDate = \Carbon\Carbon::parse($date);
                                @endphp
        
                            <tr>
                                
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ format_date($date) }}</td>
                                {{-- Display the earliest punch in time --}}
                                <td>{{ $punchIn ? \Carbon\Carbon::parse($punchIn)->format('h:i A') : '' }}</td>
                                {{-- Display the latest punch out time --}}
                                <td>

                                    @if (empty($punchOut) && $recordDate->lt(now()->startOfDay()))
                                        <span class="text-danger">Miss Out</span>
                                    @else
                                        {{ $punchOut ? \Carbon\Carbon::parse($punchOut)->format('h:i A') : '' }}
                                    @endif
                                    
                                </td>
                                {{-- Display the calculated total time for the day --}}
                                <td>{{ sprintf('%02d:%02d', $hours, $minutes) }}</td>
                            </tr>
                        @endforeach
                        @endif

                        {{-- $attendances->links() --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal custom-modal fade" id="clockin_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
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
                                @foreach (\Modules\Project\Models\Project::get() as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </x-form.input-block>
                    </div>
                </div>
                <div class="submit-section mb-3">
                    <x-form.button type="submit" class="btn btn-primary submit-btn">{{ __('Start') }}</x-form.button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>

    <script>
        /* -----------------------------
           ANALOG CLOCK (FIXED)
        ------------------------------ */

        const clockEl = document.querySelector('.clock');

        const weekday = [
            "Sunday","Monday","Tuesday",
            "Wednesday","Thursday","Friday","Saturday"
        ];

        /* CLOCK FUNCTION */
        function clock() {

          const d   = new Date();
          const h   = d.getHours();
          const m   = d.getMinutes();
          const s   = d.getSeconds();
          const ms  = d.getMilliseconds();

          const date    = d.getDate();
          let month     = d.getMonth() + 1;
          const year    = d.getFullYear();

          /* CALCULATE DEGREES */
           const sDeg = (s + ms / 1000) * 6;          // smooth sweep
           const mDeg = (m + s / 60) * 6;
           const hDeg = (h % 12 + m / 60) * 30;

          /* ELEMENTS */
          const hEl = document.querySelector('.hour-hand');
          const mEl = document.querySelector('.minute-hand');
          const sEl = document.querySelector('.second-hand');
          const dateEl = document.querySelector('.date');
          const dayEl = document.querySelector('.day');

          /* APPLY TRANSFORMS (DO NOT OVERWRITE CSS) */
          hEl.style.transform = `translateX(-50%) rotate(${hDeg}deg)`;
          mEl.style.transform = `translateX(-50%) rotate(${mDeg}deg)`;
          sEl.style.transform = `translateX(-50%) rotate(${sDeg}deg)`;

          /* DATE & DAY */
          if (month < 10) month = "0" + month;
          dateEl.innerHTML = `${date}/${month}/${year}`;
          dayEl.innerHTML = weekday[d.getDay()];

          requestAnimationFrame(clock);
        }

        requestAnimationFrame(clock);
    </script>
      
    @script
        <script defer type="module">
            document.addEventListener('livewire:initialized', () => {
                Livewire.dispatch('refreshAttendance')
                Livewire.dispatch('fetchStatistics')
                Livewire.dispatch('IsClockedIn')
            })

            Livewire.on('Notification', (param) => {
                Toastify({
                    text: param,
                    className: "success",
                }).showToast()
            })

                /* -----------------------------
                   LIVE TOTAL HOURS COUNTER
                ------------------------------*/
                let startedAt = "{{ $timeStarted }}"; // From backend
                if (startedAt) {

                    function updateWorkedHours() {
                        let start = new Date(startedAt);
                        let now   = new Date();

                        let diffMs = now - start;
                        let diffMins = Math.floor(diffMs / 60000);
                        let diffHours = Math.floor(diffMins / 60);
                        let mins = diffMins % 60;

                        document.getElementById("totalHoursRunningLive").textContent =
                            `${diffHours}h : ${String(mins).padStart(2, "0")}m Worked`;
                    }

                    updateWorkedHours();
                    setInterval(updateWorkedHours, 60000);
                }
        </script>

    @endscript

</div>
