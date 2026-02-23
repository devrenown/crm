@push('page-styles')
<style>
    .modern-analog-clock {
        width: 10rem;
        height: 10rem;
        border: 6px solid #ddd;
        border-radius: 50%;
        margin: 0 auto;
        position: relative;
        background: #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .numbers span {
        position: absolute;
        left: 46%;
        top: 45%;
        transform-origin: 0 0;
        font-size: 13px;
        font-weight: 600;
        color: #444;

        transform: rotate(calc(var(--i) * 30deg)) 
               translate(0, -60px) 
               rotate(calc(var(--i) * -30deg));
    }

    .hand {
        position: absolute;
        bottom: 50%;
        left: 50%;
        transform-origin: bottom center;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .hand.hour {
        width: 6px;
        height: 32px;
        background: #333;
    }

    .hand.minute {
        width: 4px;
        height: 45px;
        background: #007bff;
    }

    .hand.second {
        width: 2px;
        height: 50px;
        background: #e74c3c;
    }

    .center-dot {
        width: 10px;
        height: 10px;
        background: #333;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

</style>

@endpush

<div>
    <div class="row">
        <div class="col-md-5">
            <div class="card punch-status">
                <div class="card-body">
                    <h5 class="card-title">
                        <div class="row">
                            <div class="col-8">
                                {{ __('Timesheet') }} <small class="text-muted">{{ format_date(Date('Y-m-d')) }}</small>
                            </div>
                            <div class="col-4">
                                <span>{{ $totalHours }} {{ \Str::plural(__('Hour'), intval($totalHours)) }}</span> 
                            </div>
                        </div>
                    </h5>
                    
                    <div class="punch-info">

                        <div class="modern-analog-clock">
                            <div class="numbers">
                                <span style="--i:0">12</span>
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
                            </div>

                            <div class="hand hour"></div>
                            <div class="hand minute"></div>
                            <div class="hand second"></div>
                            <div class="center-dot"></div>
                        </div>
                    </div>
                    <div class="punch-btn-section">
                        @if (!empty($clockedIn) && !empty($timeId))
                        {{-- <button type="button" wire:click="clockout('{{ $timeId }}')" class="btn btn-primary punch-btn">{{ __('Clock Out') }}</button> --}}

                        <a href="javascript:void(0)" data-url="{{ route('clockout-modal', ['timeId' => $timeId]) }}" data-ajax-modal="true" data-size="lg" data-title="{{ __('Add Work Report') }}" data-timeid="{{ $timeId }}" class="btn btn-primary punch-btn">{{ __('Clock Out') }}</a> 
                        
                        @else
                        <button type="button" data-bs-toggle="modal" data-bs-target="#clockin_modal" class="btn btn-primary punch-btn">{{ __('Clock In') }}</button>
                        @endif
                    </div>
                    <div class="statistics">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card att-statistics">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Statistics') }}</h5>
                    <div class="stats-list">
                        <div class="stats-info">
                            <p>{{ __('Today') }} <strong><small> {{ $totalHoursToday }} {{ \Str::plural(__('Hour'),$totalHoursToday) }}</small></strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-primary w-{{ $totalHoursToday /100 }}" role="progressbar" aria-valuenow="{{ $totalHoursToday/100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="stats-info">
                            <p>{{ __('This Week ') }}<strong> <small> {{ $totalHoursThisWeek }} {{ \Str::plural(__('Hour'),$totalHoursThisWeek) }}</small></strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-warning w-{{ $totalHoursThisWeek/100 }}" role="progressbar" aria-valuenow="{{ $totalHoursThisWeek/100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="stats-info">
                            <p>{{ __('This Month') }} <strong> <small> {{ $totalHoursThisMonth }} {{ \Str::plural(__('Hour'),$totalHoursToday) }}</small></strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-success w-{{ $totalHoursThisMonth /100 }}" role="progressbar" aria-valuenow="{{ $totalHoursThisMonth /100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
                   ANALOG CLOCK
                ------------------------------*/
                function updateModernClock() {
                    let now = new Date();

                    let seconds = now.getSeconds();
                    let minutes = now.getMinutes();
                    let hours   = now.getHours();

                    document.querySelector(".hand.hour").style.transform =
                        `translateX(-50%) rotate(${hours * 30 + minutes / 2}deg)`;

                    document.querySelector(".hand.minute").style.transform =
                        `translateX(-50%) rotate(${minutes * 6}deg)`;

                    document.querySelector(".hand.second").style.transform =
                        `translateX(-50%) rotate(${seconds * 6}deg)`;
                }

                setInterval(updateModernClock, 1000);
                updateModernClock();


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
