@php

  use Carbon\Carbon;

  $tenant           = app('tenant');
  $subscription     = $tenant->currentSubscription;
  $tenantNow        = \Carbon\Carbon::now(app('tenant_timezone'));
  $subscriptionEnd  = \Carbon\Carbon::parse($subscription->end_date)->timezone(app('tenant_timezone'));
  $remainingDays    = max(0, $tenantNow->diffInDays($subscriptionEnd, false));
  $isExpired        = $remainingDays === 0 ? true : false;
  $today            = Carbon::today()->format('m-d');

@endphp

<style>

    .user-img .status {
        border: 2px solid #ffffff;
        height: 10px;
        width: 10px;
        margin: 0;
        position: absolute;
        left: 5px;
        bottom: 12px;
        border-radius: 50%;
        display: inline-block;
    }

    .card {
        margin-bottom: 0;
    }

    .card-dashboard {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .welcome-card {
        color: #ffffff;
        border-radius: 15px;
    }

    .badge-soft-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .badge-soft-primary {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-soft-success {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-soft-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-soft-warning {
        background: #fef9c3;
        color: #ca8a04;
    }


    .small-muted {
        font-size: 12px;
        color: #6b7280;
    }

    .employee-card {
        border-radius: 18px;
        border: none;
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        max-height: 20rem;
    }

    /* Top icon */
    .icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #a855f7, #ec4899);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
    }

    .card-icon {
        height: 50px;
        width: 50px;
    }

    .row>div:nth-child(1) .card-dashboard .card-icon {
        background: #ad46ff;
    }
    .row>div:nth-child(2) .card-dashboard .card-icon {
        background: #fdc700;
    }
    .row>div:nth-child(3) .card-dashboard .card-icon {
        background: #e60076;
    }
    .row>div:nth-child(4) .card-dashboard .card-icon {
        background: #2b7fff;
    }
    .row>div:nth-child(5) .card-dashboard .card-icon {
        background: #22c55e;
        /* Green */
    }
    .row>div:nth-child(6) .card-dashboard .card-icon {
        background: #f97316;
        /* Orange */
    }
    .row>div:nth-child(7) .card-dashboard .card-icon {
        background: #14b8a6;
        /* Teal */
    }
    .row>div:nth-child(8) .card-dashboard .card-icon {
        background: #6366f1;
        /* Indigo */
    }
    /* Center text inside donut */
    .donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .donut-center h2 {
       font-size: 3.5rem;
    }
    /* Floating badges */
    .present-badge {
        position: absolute;
        top: 35%;
        right: 0;
        background: #ffffff;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 13px;
    }
    .absent-badge {
        position: absolute;
        bottom: 30%;
        left: 0;
        background: #ffffff;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 13px;
    }
    .avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    .attendance-table-wrapper {
        max-height: 310px;
        min-height: 310px;
        overflow-y: auto;
    }
    .invoice-table-wrapper {
       max-height: 295px;
       min-height: 295px;
       overflow-y: auto; 
    }
    /* Make thead sticky */
    .attendance-table-wrapper thead th, .invoice-table-wrapper thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        /* same as table-light */
        z-index: 2;
    }
    
    /* Gradients */
    .bg-gradient-purple {
        background: linear-gradient(135deg, #6366f1, #a855f7);
    }
    .bg-gradient-pink {
        background: linear-gradient(135deg, #f43f5e, #ec4899);
    }
    .bg-gradient-blue {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
    }
</style>



@if (number_format($remainingDays) <= 7)

    <marquee>
        @if($isExpired)
        <div class="text-danger">
            <strong>Your subscription has expired.</strong>
            Access to this workspace is currently restricted for all users under this organization.
            To avoid service interruption, data access limitations, or user lockouts, please
            renew or upgrade your subscription as soon as possible. Once renewed, all features,
            user access, and integrations will be restored immediately.
        </div>
        @else
        <div class="text-primary">
            <strong>Subscription expiry notice:</strong>
            This organization’s subscription will expire in

            <strong>
                {{ number_format($remainingDays, 1) }}
                day{{ number_format($remainingDays) > 1 ? 's' : '' }}
            </strong>.

            To ensure uninterrupted access for all users, continued data availability,
            and smooth operation across modules, we recommend renewing or upgrading your plan
            before the expiration date.
        </div>
        @endif
    </marquee>
@endif

<!-- Attendance chart & count cards -->
<div class="row gx-3 mb-3">
    <!-- LEFT : Employee Chart -->
    <div class="col-lg-4 mb-3">
        <div class="card employee-card p-4 position-relative">

            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="m-0 fw-bold fs-5">Employee</h5>
                    <p class="text-muted small mb-0">Attendance distribution</p>
                </div>
                <div class="icon-box shadow p-2">
                    <img src="{{ asset('images/icons/present-absent') }}.svg" alt="">
                </div>
            </div>

            <div
                class="position-relative text-center flex-grow-1 d-flex align-items-center justify-content-center">
                <canvas id="employeeChart" style="height: 235px;"></canvas>

                <div class="donut-center">
                    <h2 class="fw-bold mb-0">{{ !empty($employees) ? $employees->count(): 0 }}</h2>
                    <div class="text-muted small">Total</div>
                    <div class="fw-semibold small">Employee</div>
                </div>

                <div class="present-badge shadow-sm">
                    <strong>{{ $presentCount }}</strong> present
                </div>

                <div class="absent-badge shadow-sm">
                    <strong>{{ $absentCount }}</strong> Absent
                </div>
            </div>

        </div>
    </div>

    <!-- RIGHT : Cards Wrapper -->
    <div class="col-lg-8 mb-3">
        <div class="row g-3">

            @activeCan('view-clients')
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/clients.svg') }}" class="w-100" alt="Clients">
                    </div>
                    <p class="fs-6 m-0 mb-2">Clients</p>
                    <p class="fw-bold fs-1 m-0">{{ !empty($clients) ? $clients->count() : 0 }}</p>
                </div>
            </div>
            @endactiveCan

            @activeCan('view-employees')
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/employees.svg') }}" class="w-100" alt="Employees">
                    </div>
                    <p class="fs-6 m-0 mb-2">Total Employee</p>
                    <p class="fw-bold fs-1 m-0">{{ !empty($employees) ? $employees->count(): 0 }}</p>
                </div>
            </div>
            @endactiveCan

            @activeCan('view-employees')
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/users.svg') }}" class="w-100" alt="Active users">
                    </div>
                    <p class="fs-6 m-0 mb-2">Active users</p>
                    <p class="fw-bold fs-1 m-0">{{ $totalActiveUser }}</p>
                </div>
            </div>
            @endactiveCan

            @if (activeRole() === \App\Enums\UserType::ADMIN->value)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/users.svg') }}" class="w-100" alt="Users">
                    </div>
                    <p class="fs-6 m-0 mb-2">Total Users</p>
                    <p class="fw-bold fs-1 m-0">{{ $allUsersCount }}</p>
                </div>
            </div>
            @endif

            @activeCan('view-projects')
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/projects.svg') }}" class="w-100">
                    </div>
                    <p class="fs-6 m-0 mb-2">Projects</p>
                    <p class="fw-bold fs-1 m-0">{{ !empty($projects) ? $projects->count(): 0 }}</p>
                </div>
            </div>
            @endactiveCan

            @activeCan('view-invoices')
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/projects.svg') }}" class="w-100" alt="Invoices">
                    </div>
                    <p class="fs-6 m-0 mb-2">Invoices</p>
                    <p class="fw-bold fs-1 m-0">{{ $allInvoiceCount }}</p>
                </div>
            </div>
            @endactiveCan

            @activeCan('view-assets')
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/clients.svg') }}" class="w-100" alt="Assets">
                    </div>
                    <p class="fs-6 m-0 mb-2">Assets</p>
                    <p class="fw-bold fs-1 m-0">{{ $allAssetCount }}</p>
                </div>
            </div>
            @endactiveCan

            @activeCan('view-tickets')
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card card-dashboard p-3">
                    <div class="card-icon p-2 rounded-4 shadow mb-3">
                        <img src="{{ asset('images/icons/employees.svg') }}" class="w-100" alt="Tickets">
                    </div>
                    <p class="fs-6 m-0 mb-2">Tickets</p>
                    <p class="fw-bold fs-1 m-0">{{ !empty($tickets) ? $tickets->count(): 0 }}</p>
                </div>
            </div>
            @endactiveCan
        </div>
    </div>
</div>

<!-- Attendance + Celebrations -->
<div class="row gx-3 mb-3">

    <!-- Celebrations -->
    <div class="col-md-4 mb-3 mb-md-0 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-4 p-3 pt-2">

            <!-- Header -->
            <div class="d-flex align-items-center mb-3">
                <img src="{{ asset('images/icons/celebrations.svg') }}" class="me-2" alt="Celebrations">
                <p class="m-0 fw-bold fs-5">Celebrations</p>
            </div>

            <!-- Scrollable Area -->
            <div class="overflow-auto" style="max-height: 310px; min-height: 310px;">

                @if ($upcomingBirthdays->isEmpty() && $upcomingWorkAnniversaries->isEmpty() && $upcomingProbationCompleted->isEmpty())

                    <div class="d-flex flex-column justify-content-center align-items-center text-center mt-5">
                        <img src="{{ asset('images/icons/celebrations.svg') }}" style="width:60px; opacity:.5;" class="mb-2">
                        <p class="text-muted mb-0 fw-semibold">No celebrations coming up</p>
                        <small class="text-muted">There are no birthdays, anniversaries, or milestones in the next 7 days </small>
                    </div>
                @else

                <!-- Item -->

                    @if ($upcomingBirthdays->count() > 0)
                        @foreach ($upcomingBirthdays as $index => $user)
                        @php
                            $years = Carbon::parse($user->dob)->diffInYears(Carbon::today());
                            $birthday = Carbon::parse($user->dob)->format('m-d');
                        @endphp

                        <div
                            class="d-flex justify-content-between align-items-center shadow-sm rounded-4 p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/user.jpg') }}">
                                </div>
                                <div>
                                    <small class="text-muted fw-semibold">BIRTHDAY</small>
                                    <div class="fw-semibold">{{ $user->fullname }}</div>
                                    <small class="text-muted">{{ @$user->employeeDetail->designation->name ?? 'Employee' }}</small>
                                </div>
                            </div>
                            <span class="badge rounded-pill {{ $birthday == $today ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                {{
                                    $birthday == $today ? 'Today 🥳'
                                    : ($birthday == now()->addDay()->format('m-d') ? 'Tomorrow'
                                    : Carbon::parse($user->dob)->format('d M'))
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
                            $anniversary = date('m-d', strtotime($anni->date_joined));
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
                                    <small class="text-muted">{{ number_format($years) }} Years</small>
                                </div>
                            </div>
                            <span class="badge rounded-pill {{ $anniversary == $today ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                {{ 
                                    $anniversary == $today ? 'Today 🥳'  
                                    : ($anniversary == now()->addDay()->format('m-d') ? 'Tomorrow'
                                    : date('d M', strtotime($anni->date_joined)))
                                }}
                            </span>
                        </div>
                        @endforeach
                    @endif

                    @if ($upcomingProbationCompleted->count() > 0)

                        @foreach ($upcomingProbationCompleted as $index => $prob)
                        @php
                            $end   = Carbon::parse($prob->probation_end_date)->format('m-d');
                        @endphp

                        <div
                            class="d-flex justify-content-between align-items-center shadow-sm rounded-4 p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img src="{{ $prob->avatar ? asset('storage/' . $prob->avatar) : asset('images/user.jpg') }}">
                                </div>
                                <div>
                                    <small class="text-muted fw-semibold">PROBATION PERIOD</small>
                                    <div class="fw-semibold">{{ $prob->fullname }}</div>
                                    <small class="text-muted">{{ @$prob->employeeDetail->designation->name ?? 'Employee' }}</small>
                                </div>
                            </div>
                            <span class="badge rounded-pill {{ $end == $today ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                {{ 
                                    $end == $today ? 'Today 🥳' 
                                    : ($end == now()->addDay()->format('m-d') ? 'Tomorrow'
                                    : Carbon::parse($prob->probation_end_date)->format('d M'))
                                }}
                            </span>
                        </div>
                        @endforeach
                    @endif

                @endif

            </div>

            <!-- Button -->
            <button class="btn w-100 mt-2 text-white fw-semibold rounded-3 btn-primary">
                View All Celebrations →
            </button>

        </div>
    </div>

    <!-- Attendance -->
    <div class="col-md-8">
        <div class="card card-dashboard p-3" id="attendance-card">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <p class="m-0 fw-bold fs-5">Today's Attendance</p>
                    <p class="text-muted text-sm">Punch In & Punch Out Records</p>
                </div>
                <div>
                    <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>

            <div class="table-responsive attendance-table-wrapper">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>Platform</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employeesAttendance as $record)
                        <tr>
                            <td>
                                <img src="{{ $record->avatar ? asset('storage/' . $record->avatar) : asset('images/user.jpg') }}" alt="" class="border rounded-circle"
                                    style="height: 35px; width: 35px;">
                                {{ $record->fullname }}
                            </td>
                            <td>{{ $record->employeeDetail?->department?->name ?? '---' }}</td>

                            @if (!empty($record->firstAttendanceToday))
                            
                            <td>{{ $record->firstAttendanceToday?->created_at ? tz($record->firstAttendanceToday->created_at, 'h:i A') : '--' }}</td>
                            <td>{{ $record->lastAttendanceToday?->endDate ? tz($record->lastAttendanceToday->endDate, 'h:i A') : '--' }}</td>
                            <td class="text-primary fw-semibold">{{ $record->firstAttendanceToday?->platform ?? '---' }}</td>
                            <td><span class="badge badge-soft-success">Present</span></td>
                            @else
                            <td class="text-danger">---</td>
                            <td class="text-danger">---</td>
                            <td class="text-danger">---</td>
                            <td class="text-danger"><span class="badge badge-soft-danger">Absent</span></td>
                            @endif
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Statistics, Widget, Tasks -->
<div class="row gx-3 mb-3">

    <div class="col-md-4 mb-3 mb-md-0 mb-lg-0">

        <div class="card card-dashboard flex-fill dash-statistics">

            <div class="card-body">

                <p class="mb-2 fs-5 fw-bold">{{ __('Statistics') }}</p>

                <div class="stats-list" style="min-height: 315px; max-height: 315px; overflow-y: auto;">

                    @if(
                        (isset($invoices) && $invoices->count() > 0) ||
                        (isset($tickets) && $tickets->count() > 0)
                    )

                        @activeCan('view-invoices')

                        @if (!empty($invoices) && $invoices->count() > 0)

                        <div class="stats-info">

                            <p>{{ __('Declined Invoices') }} <strong>{{ $invoices->where('status', '4')->count() }} <small>/ {{ $invoices->count() }}</small></strong></p>

                            <div class="progress">

                                <div class="progress-bar bg-danger w-31" role="progressbar" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>

                            </div>

                        </div>

                        <div class="stats-info">

                            <p>{{ __('Partially Paid Invoices') }} <strong>{{ $invoices->where('status', '3')->count() }} <small>/ {{ $invoices->count() }}</small></strong></p>

                            <div class="progress">

                                <div class="progress-bar bg-info w-31" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>

                            </div>

                        </div>

                        <div class="stats-info">

                            <p>{{ __('Paid Invoices') }} <strong>{{ $invoices->where('status', '2')->count() }} <small>/ {{ $invoices->count() }}</small></strong></p>

                            <div class="progress">

                                <div class="progress-bar bg-success w-31" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>

                            </div>

                        </div>

                        

                        <div class="stats-info">

                            <p>{{ __('Sent Invoices') }} <strong>{{ $invoices->where('status', '1')->count() }} <small>/ {{ $invoices->count() }}</small></strong></p>

                            <div class="progress">

                                <div class="progress-bar bg-primary w-31" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>

                            </div>

                        </div>                                

                        @endif

                        @endactiveCan



                        @activeCan('view-tickets')

                        @if (!empty($tickets) && $tickets->count() > 0)

                        <div class="stats-info">

                            <p>{{ __('Open Tickets') }} <strong>{{ $tickets->where('status', \App\Enums\TicketStatus::NEW)->count() }} <small>/ {{ $tickets->count() }}</small></strong></p>

                            <div class="progress">

                                <div class="progress-bar bg-danger w-62" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>

                            </div>

                        </div>

                        <div class="stats-info">

                            <p>{{ __('Closed Tickets') }} <strong>{{ $tickets->where('status', \App\Enums\TicketStatus::CLOSED)->count() }} <small>/ {{ $tickets->count() }}</small></strong></p>

                            <div class="progress">

                                <div class="progress-bar bg-info w-22" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>

                            </div>

                        </div>

                        @endif

                        @endactiveCan

                    @else

                    {{-- EMPTY STATE --}}
                    <div class="d-flex flex-column justify-content-center align-items-center text-center mt-5">
                        <div class="mb-3">
                            <i class="bi bi-graph-up-arrow fs-1 text-muted"></i>
                        </div>
                        <p class="text-muted fw-semibold mb-1">No statistics available</p>
                        <small class="text-muted">Invoice and ticket statistics will appear here.</small>
                    </div>

                    @endif
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-4 mb-3 mb-md-0 mb-lg-0">
        <div class="row g-2">

            <!-- New Employees -->
            @activeCan('view-employees')
            <div class="col-6">
                <div class="card card-dashboard p-3">
                    <div class="d-flex">

                        <canvas id="empChart" width="60" height="60"></canvas>

                    </div>

                    <p class="text-muted mt-2 mb-1 small">New Employees</p>
                    <h3 class="fw-bold">{{ $thisMonthTotalEmployees }}</h3>

                    <p class="text-muted small mb-0">
                        Previous Month <strong>{{ $prevMonthTotalEmployees }}</strong>
                    </p>
                </div>
            </div>
            @endactiveCan

            <!-- Expenses -->
            @activeCan('view-expenses')
            <div class="col-6">
                <div class="card card-dashboard p-3">
                    <div class="d-flex justify-content-between align-items-center">

                        <canvas id="expChart" width="60" height="60"></canvas>

                    </div>

                    <p class="text-muted mt-2 mb-1 small">Expenses</p>
                    <h3 class="fw-bold">{{ LocaleSettings('currency_symbol').' '.$thisMonthExpenses }}</h3>

                    <p class="text-muted small mb-0">
                        Previous Month {{ LocaleSettings('currency_symbol') }} <strong>{{ $prevMonthExpenses }}</strong>
                    </p>
                </div>
            </div>
            @endactiveCan

            <!-- Estimates -->
            @activeCan('view-estimates')
            <div class="col-6">
                <div class="card card-dashboard p-3">
                    <div class="d-flex justify-content-between align-items-center">

                        <canvas id="estChart" width="60" height="60"></canvas>

                    </div>

                    <p class="text-muted mt-2 mb-1 small">Estimates</p>
                    <h3 class="fw-bold">{{ LocaleSettings('currency_symbol').' '.$thisMonthEstimates }}</h3>

                    <p class="text-muted small mb-0">
                        Previous Month {{ LocaleSettings('currency_symbol') }} <strong>{{ $prevMonthEstimates }}</strong>
                    </p>
                </div>
            </div>
            @endactiveCan


            <!-- Invoices -->
            @activeCan('view-invoices')
            <div class="col-6">
                <div class="card card-dashboard p-3">
                    <div class="d-flex justify-content-between align-items-center">

                        <canvas id="invChart" width="60" height="60"></canvas>

                    </div>

                    <p class="text-muted mt-2 mb-1 small">Invoices</p>
                    <h3 class="fw-bold">{{ LocaleSettings('currency_symbol').' '.$thisMonthInvoices }}</h3>

                    <p class="text-muted small mb-0">
                        Previous Month {{ LocaleSettings('currency_symbol') }} <strong>{{ $prevMonthInvoices }}</strong>
                    </p>
                </div>
            </div>
            @endactiveCan

        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-dashboard p-3">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="m-0 p-0 fw-bold fs-5">Active Tasks</p>

                {{-- <a href="{{ route('project.taskboard',['id' => \Crypt::encrypt($project->id)]) }}" class="btn btn-sm btn-primary rounded-3">View All</a> --}}
            </div>

            <div id="task-container" style="max-height: 310px; min-height: 310px; overflow-y: auto;">

                <!-- Task -->
                @forelse($tasks as $task)

                @php
                    $statusMap = [
                        1 => ['label' => 'Pending', 'class' => 'badge-soft-secondary'],
                        2 => ['label' => 'In Progress', 'class' => 'badge-soft-primary'],
                        3 => ['label' => 'On Hold', 'class' => 'badge-soft-warning'],
                        4 => ['label' => 'Completed', 'class' => 'badge-soft-success'],
                        5 => ['label' => 'Cancelled', 'class' => 'badge-soft-danger'],
                    ];

                    $priorityMap = [
                        1 => ['label' => 'High', 'class' => 'badge-soft-danger'],
                        2 => ['label' => 'Medium', 'class' => 'badge-soft-warning'],
                        3 => ['label' => 'Low', 'class' => 'badge-soft-primary'],
                    ];

                    $status     = $statusMap[$task->status] ?? ['label' => 'Unknown', 'class' => 'badge-soft-dark'];
                    $priority   = $priorityMap[$task->priority] ?? ['label' => 'Unknown', 'class' => 'badge-soft-dark'];
                @endphp

                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong class="text-capitalize">{{ $task->name }}</strong>

                            @if (!empty($task->followers) && $task->followers->count() > 0)
                                <div class="project-members m-b-15">
                                    <ul class="team-members">
                                        @foreach ($task->followers as $member)
                                            <li>
                                                <a href="@activeCan('show-Employeeprofile') {{ route('employees.show', ['employee' => \Crypt::encrypt($member->user->id)]) }} @else # @endactiveCan"
                                                    data-bs-toggle="tooltip" title="{{ $member->user->fullname }}">
                                                    <img src="{{ !empty($member->user->avatar) ? uploadedAsset($member->user->avatar, 'users') : asset('images/user.jpg') }}"
                                                        alt="{{ __('Avatar') }}">
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <span class="badge {{ $priority['class'] }}">
                            {{ $priority['label'] }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted mt-2">
                            {{ date('d M Y', strtotime($task->startDate)) }} - {{ date('d M Y', strtotime($task->endDate)) }}
                        </div>
                        
                        <div>

                            <span class="badge {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <img src="{{ asset('images/icons/no-task.svg') }}" style="width:40px; opacity:0.6;">
                    <p class="mt-3 mb-1 fw-semibold text-muted">No tasks found</p>
                    <small class="text-muted">Tasks will appear here once they are created.</small>
                </div>
                @endforelse
            </div>

        </div>
    </div>
    
</div>
<!-- /Statistics Widget, Tasks -->

<!-- Expenses & invoices -->
<div class="row gx-3">
    <div class="col-md-6">
        <div class="card card-dashboard p-3 mb-3">
            <p class="m-0 fs-5 fw-bold">Expenses</p>
            <canvas id="expensesChart" style="max-height: 320px;"></canvas>
        </div>
    </div>

    @activeCan('view-invoices')
    <div class="col-md-6 mb-3 mb-md-0 mb-lg-0">
        <div class="card card-dashboard p-3">
            <div class="card-header d-flex justify-content-between">
                <p class="m-0 fs-5 fw-bold">Current Month Invoices</p>
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-primary">View all Invoices</a>
            </div>

            <div class="table-responsive invoice-table-wrapper">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @forelse ($thisMonthInvoiceList as $invoice)
                        <tr>
                            <td><a @activeCan('show-invoice') target="_blank" href="{{ route('invoices.show', ['invoice' => Crypt::encrypt($invoice->id)]) }}" @else href="#" @endactiveCan>{{ $invoice->inv_id }}</a></td>
                            <td>{{ $invoice->client->user->fullname ?? '' }}</td>
                            <td>{{ tz($invoice->expiryDate, 'd M Y') ?? '' }}</td>
                            <td>{{ LocaleSettings('currency_symbol') }} {{ $invoice->grand_total }}</td>
                            <td><span class="badge badge-soft-{{ $invoice->statusName['color'] ?? 'primary' }}">{{ $invoice->statusName['name'] ?? '' }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-3">
                                        <i class="bi bi-receipt fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">No Invoices Found</h6>
                                    <p class="text-secondary small mb-0">
                                        There are no invoices available for this month.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endactiveCan
</div>
<!-- /Expenses & invoices -->

<!-- Clients & Projects -->
<div class="row gx-3">
    @activeCan('view-clients')
    <div class="col-lg-6 mb-3 mb-md-0 mb-lg-0">
        <div class="card card-dashboard p-3" style="min-height: 350px; max-height: 350px;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="m-0 fs-5 fw-bold">Clients</p>
                <a href="{{ route('clients.index') }}" class="btn btn-sm btn-primary">View all Clients</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            @activeAnyCan(['edit-client','delete-client'])
                            <th></th>
                            @endactiveAnyCan
                        </tr>
                    </thead>

                    <tbody>
                        
                        @forelse ($thisMonthClients as $client)

                        @php

                            $img = !empty($client->avatar) ? asset('storage/'.$client->avatar): asset('images/user.jpg');

                            $link = (auth()->user()->can('show-ClientProfile')) ? route('clients.show', ['client' => Crypt::encrypt($client->id)]): '#';
                        @endphp

                        <tr>
                            <td>
                                {!! \Spatie\Menu\Laravel\Html::userAvatar($client->fullname, $img, $link) !!}
                            </td>
                            <td class="text-muted small">
                                {{ $client->email }}
                            </td>
                            <td>
                                <span class="badge badge-soft-{{ $client->is_active ? 'success' : 'danger' }}">{{ $client->is_active ? 'Active' : 'Deactive' }}</span>
                            </td>
                            @activeAnyCan(['edit-client','delete-client'])
                            <td align="center">
                                <div class="dropdown dropdown-action position-static">

                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

                                    <div class="dropdown-menu dropdown-menu-end">

                                        @activeCan('edit-client')

                                        <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('clients.edit', ['client' => \Crypt::encrypt($client->id)]) }}" data-ajax-modal="true"

                                            data-title="Edit Client" data-size="lg"><i class="fa-solid fa-pencil m-r-5"></i>

                                            {{ __('Edit') }}

                                        </a>

                                        @endactiveCan

                                        @activeCan('delete-client')

                                        <a class="dropdown-item deleteBtn" data-route="{{ route('clients.destroy', $client->id) }}" data-title="{{ __('Delete Client') }}"

                                            data-question="Are you sure you want to delete?" href="javascript:void(0)">

                                            <i class="fa-regular fa-trash-can m-r-5"></i>

                                            {{ __('Delete') }}

                                        </a>

                                        @endactiveCan

                                    </div>

                                </div>
                            </td>
                            @endactiveAnyCan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-3">
                                        <i class="bi bi-person-lines-fill fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">No Clients Found</h6>
                                    <p class="text-secondary small mb-0">
                                        There are no clients available for this month.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>
    @endactiveCan

    @activeCan('view-projects')
    <div class="col-lg-6">
        <div class="card card-dashboard p-3" style="min-height: 350px; max-height: 350px;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="m-0 fw-bold fs-5">Recent Projects</h6>
                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary">View all Projects</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Project Name</th>
                            <th>Date</th>
                            <th>Priority</th>
                            @activeAnyCan(['edit-project', 'delete-project'])
                            <th></th>
                            @endactiveAnyCan
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($recentProjects as $project)
                        <tr>
                            <td>
                                @activeCan('show-project')
                                <strong><a target="_blank" href="{{ route('projects.show', ['project' => \Crypt::encrypt($project->id)]) }}">{{ $project->name }}</a></strong>
                                @else
                                <strong>{{ $project->name }}</strong>
                                @endactiveCan

                                <div class="small text-muted">
                                    <span class="text-xs">{{ $project->tasks->count() ?? 0 }}</span> <span class="text-muted">{{ __('Opened Tasks') }}</span>

                                    <span class="text-xs">{{ $project->tasks->count() ?? 0 }}</span> <span class="text-muted">{{ __('Tasks Completed') }}</span>
                                </div>
                            </td>

                            <td class="small text-muted">
                                {{ tz($project->startDate, 'd M Y') }} - {{ tz($project->endDate, 'd M Y') }}
                            </td>

                            <td>
                                @php
                                $projectPriorityMap = [
                                  'High'    => ['class' => 'badge-soft-danger'],
                                  'Medium'  => ['class' => 'badge-soft-warning'],
                                  'Low'     => ['class' => 'badge-soft-primary']
                                ];

                                $priorityBadgeClass = $projectPriorityMap[$project->priority];
                                @endphp
                                <span class="badge {{ $priorityBadgeClass['class'] }}">{{ $project->priority }}</span>
                            </td>
                            @activeAnyCan(['edit-project', 'delete-project'])
                            <td>
                                <div class="dropdown dropdown-action position-static">

                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

                                    <div class="dropdown-menu dropdown-menu-end">

                                        @activeCan('edit-project')

                                        <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('projects.edit', ['project' => ($project->id)]) }}" data-ajax-modal="true"

                                            data-title="Edit Project" data-size="lg">

                                            <i class="fa-solid fa-pencil m-r-5"></i>

                                            {{ __('Edit') }}

                                        </a>

                                        @endactiveCan

                                        @activeCan('delete-project')

                                        <a class="dropdown-item deleteBtn" data-route="{{ route('projects.destroy', $project->id) }}" data-title="Delete Project"

                                            data-question="Are you sure you want to delete project?" href="javascript:void(0)">

                                            <i class="fa-regular fa-trash-can m-r-5"></i>

                                            {{ __('Delete') }}

                                        </a>

                                        @endactiveCan

                                    </div>

                                </div>
                            </td>
                            @endactiveAnyCan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-3">
                                        <i class="bi bi-kanban fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">No Projects Found</h6>
                                    <p class="text-secondary small mb-0">
                                        There are no projects available for this month.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>
    </div>
    @endactiveCan
</div>
<!-- /Clients & Projects -->

<!-- Budget & Estimates -->
<div class="row gx-3 my-3">

    <div class="col-lg-6 mb-3 mb-md-0 mb-lg-0">
        <div class="card card-dashboard p-3">
            <p class="mb-1 fs-5 fw-bold">Budget</p>
            <canvas id="budgetChart" style="height:320px;"></canvas>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-dashboard p-3">
            <p class="mb-1 fs-5 fw-bold">Estimates & Invoices Overview</p>
            <canvas id="invoiceChart" style="height:320px;"></canvas>
        </div>
    </div>
</div>

{{-- dd($monthly_expense) --}}


@push('page-scripts')

    <!-- ChartJS -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script defer src="{{ asset('js/plugins/morris/morris.min.js') }}"></script> -->

    <script defer src="{{ asset('js/plugins/raphael/raphael.min.js') }}"></script>

    <script type="module" defer>

        @if (session('events'))

            @foreach (session('events') as $event)

                Livewire.dispatch('{{ $event }}');
            @endforeach
        @endif

    </script>

    <script>
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

        new Chart(document.getElementById('employeeChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: ['{{ $presentCount }}', '{{ $absentCount }}'],
                    backgroundColor: ['#FF9748', '#FF2C2F'],
                    borderWidth: 2,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                rotation: -90,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            },
            plugins: [shadowPlugin]
        });

        function createMiniChart(id, color, percent = 10) {

            const centerText = {
                id: 'centerText',
                beforeDraw(chart) {
                    const { width, height, ctx } = chart;

                    ctx.restore();
                    ctx.font = (height / 3.5) + "px sans-serif";
                    ctx.textBaseline = "middle";
                    ctx.textAlign = "center";
                    ctx.fillStyle = "#6c757d";

                    ctx.fillText(percent + "%", width / 2, height / 2);
                    ctx.save();
                }
            };

            new Chart(document.getElementById(id), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [percent, 100 - percent],
                        backgroundColor: [color, '#eee'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                },
                plugins: [centerText]
            });
        }

        new Chart(document.getElementById('expensesChart'), {
            type: 'line',
            data: {
                labels: [
                    @foreach ($monthly_expense as $key => $expense)
                        "{{ Carbon::create()->month($key+1)->format('M') }}",
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach ($monthly_expense as $key => $expense)
                            {{ $expense->sum('amount') }},
                        @endforeach
                    ],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        /* Budget Chart */
        new Chart(document.getElementById('budgetChart'), {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [
                    {
                        label: 'Expected Revenue',
                        data: [
                            {{ $budget_collection->get(0)->sum('total_revenue') }},
                            {{ $budget_collection->get(1)->sum('total_revenue') }},
                            {{ $budget_collection->get(2)->sum('total_revenue') }},
                            {{ $budget_collection->get(3)->sum('total_revenue') }},
                            {{ $budget_collection->get(4)->sum('total_revenue') }},
                            {{ $budget_collection->get(5)->sum('total_revenue') }},
                            {{ $budget_collection->get(6)->sum('total_revenue') }},
                            {{ $budget_collection->get(7)->sum('total_revenue') }},
                            {{ $budget_collection->get(8)->sum('total_revenue') }},
                            {{ $budget_collection->get(9)->sum('total_revenue') }},
                            {{ $budget_collection->get(10)->sum('total_revenue') }},
                            {{ $budget_collection->get(11)->sum('total_revenue') }}
                        ],
                        borderColor: '#ff4d4f',
                        backgroundColor: '#ff4d4f',
                        tension: 0.4,
                        pointRadius: 4,
                        fill: false
                    },
                    {
                        label: 'Expected Expenses',
                        data: [
                            {{ $budget_collection->get(0)->sum('total_expense') }},
                            {{ $budget_collection->get(1)->sum('total_expense') }},
                            {{ $budget_collection->get(2)->sum('total_expense') }},
                            {{ $budget_collection->get(3)->sum('total_expense') }},
                            {{ $budget_collection->get(4)->sum('total_expense') }},
                            {{ $budget_collection->get(5)->sum('total_expense') }},
                            {{ $budget_collection->get(6)->sum('total_expense') }},
                            {{ $budget_collection->get(7)->sum('total_expense') }},
                            {{ $budget_collection->get(8)->sum('total_expense') }},
                            {{ $budget_collection->get(9)->sum('total_expense') }},
                            {{ $budget_collection->get(10)->sum('total_expense') }},
                            {{ $budget_collection->get(11)->sum('total_expense') }}
                        ],
                        borderColor: '#20c997',
                        backgroundColor: '#20c997',
                        tension: 0.4,
                        pointRadius: 4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { }
                }
            }
        });

        /* Estimates & Invoices Chart */
        new Chart(document.getElementById('invoiceChart'), {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [
                    {
                        label: 'Invoices',
                        data: [
                            {{ $invoice_collection->get(0)->sum('grand_total') }},
                            {{ $invoice_collection->get(1)->sum('grand_total') }},
                            {{ $invoice_collection->get(2)->sum('grand_total') }},
                            {{ $invoice_collection->get(3)->sum('grand_total') }},
                            {{ $invoice_collection->get(4)->sum('grand_total') }},
                            {{ $invoice_collection->get(5)->sum('grand_total') }},
                            {{ $invoice_collection->get(6)->sum('grand_total') }},
                            {{ $invoice_collection->get(7)->sum('grand_total') }},
                            {{ $invoice_collection->get(8)->sum('grand_total') }},
                            {{ $invoice_collection->get(9)->sum('grand_total') }},
                            {{ $invoice_collection->get(10)->sum('grand_total') }},
                            {{ $invoice_collection->get(11)->sum('grand_total') }}
                        ],
                        borderColor: '#ff4d4f',
                        backgroundColor: '#ff4d4f',
                        tension: 0.4,
                        pointRadius: 4,
                        fill: false
                    },
                    {
                        label: 'Estimates',
                        data: [
                            {{ $estimates_collection->get(0)->sum('grand_total') }},
                            {{ $estimates_collection->get(1)->sum('grand_total') }},
                            {{ $estimates_collection->get(2)->sum('grand_total') }},
                            {{ $estimates_collection->get(3)->sum('grand_total') }},
                            {{ $estimates_collection->get(4)->sum('grand_total') }},
                            {{ $estimates_collection->get(5)->sum('grand_total') }},
                            {{ $estimates_collection->get(6)->sum('grand_total') }},
                            {{ $estimates_collection->get(7)->sum('grand_total') }},
                            {{ $estimates_collection->get(8)->sum('grand_total') }},
                            {{ $estimates_collection->get(9)->sum('grand_total') }},
                            {{ $estimates_collection->get(10)->sum('grand_total') }},
                            {{ $estimates_collection->get(11)->sum('grand_total') }}
                        ],
                        borderColor: '#ff4d4f',
                        borderDash: [6,6],
                        tension: 0.4,
                        pointRadius: 4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { }
                }
            }
        });

        createMiniChart('empChart', '#7c3aed', 10);  
        createMiniChart('expChart', '#22c55e', 10);   
        createMiniChart('estChart', '#2563eb', 10);   
        createMiniChart('invChart', '#f97316', 10);  
    </script>

@endpush