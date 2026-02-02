@extends('layouts.app')

@section('page-content')
@php $user = auth()->user(); @endphp

    <div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb class="col">
        <x-slot name="title">{{ __('Leave Management') }}</x-slot>

        <x-slot name="right">
            <div class="col-auto float-end ms-auto">

                {{-- CREATE LEAVE --}}
                @activeCan('create-leave')
                    <a href="{{ route('leaves.create') }}" class="btn add-btn">
                        <i class="fa-solid fa-plus"></i> {{ __('New Leave Request') }}
                    </a>
                @endactiveCan

                {{-- MANAGE LEAVE TYPES --}}
                @activeAnyCan(['view-leave-type','edit-leave-type','delete-leave-type'])
                    <a href="{{ route('leave-type.index') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-cogs"></i> {{ __('Manage Leave Types') }}
                    </a>
                @endactiveAnyCan

            </div>
        </x-slot>

        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('Leave Requests') }}</li>
        </ul>
    </x-breadcrumb>

    <!-- Dashboard Summary -->

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

    <div class="row g-3 mb-3">

        {{-- EMPLOYEE SUMMARY --}}
        @activeCan('view-leave-summary')

            <!--
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="premium-card">
                    <div class="icon-box grad-purple"><i class="bi bi-wallet-fill"></i></div>
                    <p class="label-text">{{ __('Available Balance') }}</p>
                    <h3 class="value-text text-primary">
                        {{ $user->leaveBalance() ?? 0 }} <span class="fs-6">{{ __('days') }}</span>
                    </h3>
                </div>
            </div>
            -->

        
           {{-- Dynamic Summary Cards --}}
        @if($user->isEmployee())
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
        @endif

        @endactiveCan


        {{-- Except Employee --}}
        @activeCan('view-leave-request-summary')

            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-orange"><i class="bi bi-hourglass-split"></i></div>
                    <p class="label-text">{{ __('Pending Requests') }}</p>
                    <h3 class="value-text text-warning">{{ $pendingCount ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-green"><i class="bi bi-check2-circle"></i></div>
                    <p class="label-text">{{ __('Approved Requests') }}</p>
                    <h3 class="value-text text-success">{{ $approvedCount ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-purple"><i class="bi bi-x-octagon"></i></div>
                    <p class="label-text">{{ __('Rejected Requests') }}</p>
                    <h3 class="value-text text-danger">{{ $rejectedCount ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-red"><i class="bi bi-x-circle"></i></div>
                    <p class="label-text">{{ __('Cancelled Requests') }}</p>
                    <h3 class="value-text text-danger">{{ $cancelledCount ?? 0 }}</h3>
                </div>
            </div>

        @endactiveCan
    </div>


    <!-- /Dashboard Summary -->

    <!-- Filters  -->
    @if(!$user->isEmployee())
        <div class="card card-default mb-4">
            <div class="card-header">{{ __('Search & Filter') }}</div>
            <div class="card-body">
                <form method="GET" action="{{ route('leaves.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                            class="form-control"
                            placeholder="Search by reason or employee...">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                    {{ __('Pending') }}
                                </option>
                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>
                                    {{ __('Approved') }}
                                </option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>
                                    {{ __('Rejected') }}
                                </option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                    {{ __('Cancelled') }}
                                </option>

                                <option value="Pending_level_1" {{ request('status') == 'Pending_level_1' ? 'selected' : '' }}>
                                    {{ __('Pending L1') }}
                                </option>
                                <option value="Pending_level_2" {{ request('status') == 'Pending_level_2' ? 'selected' : '' }}>
                                    {{ __('Pending L2') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="leave_type" class="form-select">
                                <option value="">{{ __('All Types') }}</option>
                                @foreach($leaveTypes ?? [] as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fa-solid fa-search"></i> {{ __('Filter') }}
                                </button>
                                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <!-- /Filters -->

    <!-- Leave Requests Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped custom-table w-100']) !!}
            </div>
        </div>
    </div>

</div>
@endsection

@push('page-scripts')
@vite(["resources/js/datatables.js"])
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@endpush
