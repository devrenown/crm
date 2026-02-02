@extends('layouts.app')

@section('page-content')
@php
    $user = auth()->user();
@endphp

<div class="content container-fluid">

    {{-- ================= PAGE HEADER ================= --}}
    <x-breadcrumb class="col">
        <x-slot name="title">{{ __('Leave Details') }}</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('leaves.index') }}">{{ __('Leave Requests') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('View Leave') }}</li>
        </ul>
    </x-breadcrumb>

     <style>
        .premium-card {
            border: 0;
            border-radius: 18px;
            background: #ffffff;
            padding: 18px;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.09);
        }
        .premium-card .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 10px;
            color: #fff;
        }

        /* Luxury Gradients */
        .grad-blue { background: linear-gradient(135deg, #0052d4, #4364f7, #6fb1fc); }
        .grad-green { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .grad-orange { background: linear-gradient(135deg, #fc4a1a, #f7b733); }
        .grad-purple { background: linear-gradient(135deg, #8e2de2, #4a00e0); }
        .grad-red { background: linear-gradient(135deg, #ff416c, #ff4b2b); }

        .value-text {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .label-text {
            font-size: 14px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 6px;
        }
    </style>

    {{-- ================= LEAVE SUMMARY (ADMIN / HR) ================= --}}
    @activeCan('view-leave-summary')
    <div class="row g-3 mb-4">
        {{-- Dynamic Summary Cards --}}
        @foreach($leaveSummary ?? [] as $summary)
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="premium-card">
                    <div class="icon-box grad-blue">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div class="small">
                        <strong>{{ $summary['name'] }}</strong>

                        <div class="text-muted mb-1">
                            <strong>Mode:</strong> {{ $summary['mode'] }}
                        </div>

                        {{-- ================= EARNED / TOTAL ================= --}}
                        <div>
                            <strong>
                                @if($summary['mode'] === 'Monthly')
                                    Earned:
                                @elseif($summary['mode'] === 'Unlimited')
                                    Entitlement:
                                @elseif($summary['mode'] === 'Not Eligible Yet')
                                    Entitlement:
                                @else
                                    Total:
                                @endif
                            </strong>

                            @if($summary['mode'] === 'Monthly')
                                {{ $summary['earned'] }}

                            @elseif($summary['mode'] === 'Unlimited')
                                Unlimited

                            @elseif($summary['mode'] === 'Not Eligible Yet')
                                0

                            @else {{-- Yearly --}}
                                {{ $summary['earned'] ?? $summary['total'] ?? 0 }}
                            @endif
                        </div>

                        {{-- ================= USED ================= --}}
                        <div>
                            <strong>Used:</strong> {{ $summary['used'] ?? 0 }}
                        </div>

                        {{-- ================= REMAINING ================= --}}
                        <div>
                            <strong>Remaining:</strong>

                            @if($summary['mode'] === 'Unlimited')
                                Unlimited
                            @else
                                {{ $summary['remaining'] ?? 0 }}
                            @endif
                        </div>

                        {{-- ================= MONTHLY INFO ================= --}}
                        @if($summary['mode'] === 'Monthly' && isset($summary['earned_till_now']))
                            <small class="text-muted d-block">
                                ({{ $summary['earned_till_now'] }} earned till date)
                            </small>
                        @endif

                        {{-- ================= ELIGIBILITY INFO ================= --}}
                        @if($summary['mode'] === 'Not Eligible Yet' && isset($summary['eligible_on']))
                            <small class="text-warning d-block">
                                Eligible from {{ \Carbon\Carbon::parse($summary['eligible_on'])->format('d M Y') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

            </div>
        @endactiveCan


    {{-- ================= LEAVE DETAILS ================= --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Leave Request Information') }}</h4>
        </div>

        <div class="card-body">

            @php
                $termDetails = is_string($leave->term_details)
                    ? json_decode($leave->term_details, true)
                    : ($leave->term_details ?? []);
            @endphp

            {{-- BASIC INFO --}}
            @foreach([
                'Employee' => $leave->user->full_name ?? null,
                'Leave Type' => $leave->leaveType->name ?? null,
                'From Date' => optional($leave->start_date)?->format('d M Y'),
                'To Date' => optional($leave->end_date)?->format('d M Y'),
                'Applied Days' => $leave->days,
                'Approved Days' => $leave->approved_days,
                
            ] as $label => $value)
                @if($value)
                    <div class="row mb-3">
                        <label class="col-md-3 fw-bold">{{ $label }}</label>
                        <div class="col-md-9">{{ $value }}</div>
                    </div>
                @endif
            @endforeach

            @if(!empty($termDetails))
            <hr>
            <h5 class="fw-bold mb-3">Leave Term Details</h5>

            @foreach($termDetails as $index => $term)
                <div class="border rounded p-3 mb-3">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Date</div>
                        <div class="col-md-9">
                            {{ \Carbon\Carbon::parse($term['date'])->format('d M Y') }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Term</div>
                        <div class="col-md-9">{{ $term['term'] }}</div>
                    </div>

                    @if($term['term'] === 'Halfday')
                        <div class="row mb-2">
                            <div class="col-md-3 fw-bold">Half Day Type</div>
                            <div class="col-md-9">{{ $term['half_day_type'] }}</div>
                        </div>
                    @endif

                    @if(!empty($term['short_leave_hours']))
                        <div class="row mb-2">
                            <div class="col-md-3 fw-bold">Short Leave Hours</div>
                            <div class="col-md-9">{{ $term['short_leave_hours'] }}</div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

        
            {{-- STATUS --}}
            <div class="row mb-3">
                <label class="col-md-3 fw-bold">Final Status</label>
                <div class="col-md-9">
                    <span class="badge
                        bg-{{ match($leave->final_status) {
                            'Approved' => 'success',
                            'Partially Approved' => 'info',
                            'Rejected' => 'danger',
                            'Cancelled' => 'warning',
                            default => 'secondary'
                        } }}">
                        {{ $leave->final_status }}
                    </span>
                </div>
            </div>


            @if(!empty($leave->reason))
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Reason</label>
                    <div class="col-md-9">
                        {{ $leave->reason }}
                    </div>
                </div>
            @endif

            {{-- DOCUMENT --}}
            @if($leave->document_path)
            <div class="row mb-3">
                <label class="col-md-3 fw-bold">Document</label>
                <div class="col-md-9">
                    <button class="btn btn-outline-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#leaveDocumentModal">
                        <i class="bi bi-file-earmark-text"></i> View Document
                    </button>
                </div>
            </div>
            @endif


            {{-- ADMIN COMMENT --}}
            @if($leave->remarks)
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Admin Comment</label>
                    <div class="col-md-9">{{ $leave->remarks }}</div>
                </div>
            @endif

            {{-- ================= APPROVAL FLOW ================= --}}
            <hr>
            <h5 class="mb-3">Approval Flow</h5>

            {{-- LEVEL 1 --}}
            @if($leave->levelStatus('L1'))
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Level 1</label>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $leave->levelStatus('L1') === 'Approved' ? 'success' :
                            ($leave->levelStatus('L1') === 'Rejected' ? 'danger' : 'warning') }}">
                            {{ $leave->levelStatus('L1') }}
                        </span>

                        @if($leave->approved_level_1_on)
                            <div class="small mt-1">
                                By {{ optional($leave->level1Approver)->full_name }}
                                on {{ $leave->approved_level_1_on->format('d M Y H:i') }}
                            </div>
                        @endif

                        @if($leave->approved_level_1_remark)
                            <div class="text-muted mt-1">
                                <strong>Remark:</strong> {{ $leave->approved_level_1_remark }}
                            </div>
                        @endif

                        @if($leave->rejection_reason_l1)
                            <div class="text-danger mt-1">{{ $leave->rejection_reason_l1 }}</div>
                        @endif

                    </div>
                </div>
            @endif

            {{-- LEVEL 2 --}}
            @if($leave->levelStatus('L2'))
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Level 2</label>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $leave->levelStatus('L2') === 'Approved' ? 'success' :
                            ($leave->levelStatus('L2') === 'Rejected' ? 'danger' : 'warning') }}">
                            {{ $leave->levelStatus('L2') }}
                        </span>

                        @if($leave->approved_level_2_on)
                            <div class="small mt-1">
                                By {{ optional($leave->level2Approver)->full_name }}
                                on {{ $leave->approved_level_2_on->format('d M Y H:i') }}
                            </div>
                        @endif

                        @if($leave->approved_level_2_remark)
                            <div class="text-muted mt-1">
                                <strong>Remark:</strong> {{ $leave->approved_level_2_remark }}
                            </div>
                        @endif

                        @if($leave->rejection_reason_l2)
                            <div class="text-danger mt-1">{{ $leave->rejection_reason_l2 }}</div>
                        @endif

                    </div>
                </div>
            @endif

            {{-- ACTIONS --}}
            <div class="mt-4">
                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>

        </div>
    </div>
</div>
@php
$url = URL::temporarySignedRoute(
    'leaves.document.view',
    now()->addMinutes(2),
    ['leave' => $leave->id]
);
@endphp

@if($leave->document_path)
<div class="modal fade" id="leaveDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="height:80vh;">
                <iframe
                    src="{{ $url }}"
                    style="width:100%; height:100%; border:0;">
                    </iframe>
                <!--Disable Downloade , Print.. -->
                <!-- <iframe
                    src="{{ route('leaves.document.view', $leave) }}#toolbar=0&navpanes=0&scrollbar=0"
                    style="width:100%; height:100%; border:0;">
                </iframe> -->
            </div>
        </div>
    </div>
</div>
@endif


@endsection
