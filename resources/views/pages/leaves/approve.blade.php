@extends('layouts.app')

@section('page-content')
@php
    $isL1ApprovalStage = $leave->approval_stage === 'L1';
    $isL2ApprovalStage = $leave->approval_stage === 'L2';

    $canApprove = auth()->user()->can('approve', $leave);
@endphp

<div class="content container-fluid">

<x-breadcrumb>
    <x-slot name="title">Approve Leave</x-slot>
</x-breadcrumb>

<div class="card">
    <div class="card-body">

        {{-- Employee & Leave Info --}}
        <p><strong>Employee:</strong> {{ $leave->user->name }}</p>
        
        <p>
            <strong>Dates:</strong>
            {{ $leave->start_date->format('d M Y') }}
            →
            {{ $leave->end_date?->format('d M Y') }}
        </p>
        <p><strong>Days:</strong> {{ $leave->days }}</p>
        <p><strong>Leave Type:</strong> {{ $leave->leaveType?->name ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $leave->status }}</p>
        <p><strong>Reason:</strong> {{ $leave->reason }}</p>

        {{-- Approval Form --}}
        @if($canApprove)
            <form method="POST" action="{{ route('leaves.approve', $leave) }}">
                @csrf

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="Approved">Approve</option>
                        <option value="Rejected">Reject</option>
                    </select>
                </div>

                {{-- L1 Remark --}}
                @if($isL1ApprovalStage)
                    @if($leave->approved_level_1_id)
                        <div class="alert alert-info">
                            <strong>L1 Approved By:</strong> {{ $leave->approvedLevel1?->name ?? 'N/A' }}<br>
                            <strong>Remark:</strong> {{ $leave->approved_level_1_remark ?? '-' }}<br>
                            <strong>On:</strong> {{ $leave->approved_level_1_on }}
                        </div>
                    @else
                        <textarea name="approved_level_1_remark" class="form-control mb-3" placeholder="Remark"></textarea>
                    @endif
                @endif

                {{-- L2 Remark --}}
                @if($isL2ApprovalStage)
                    @if($leave->approved_level_2_id)
                        <div class="alert alert-info">
                            <strong>L2 Approved By:</strong> {{ $leave->approvedLevel2?->name ?? 'N/A' }}<br>
                            <strong>Remark:</strong> {{ $leave->approved_level_2_remark ?? '-' }}<br>
                            <strong>On:</strong> {{ $leave->approved_level_2_on }}
                        </div>
                    @else
                        <textarea name="approved_level_2_remark" class="form-control mb-3" placeholder="Remark"></textarea>
                    @endif
                @endif

                <button class="btn btn-success">Submit</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        @else
            <div class="alert alert-secondary">
                You do not have permission to approve this leave at this stage.
            </div>
        @endif

        {{-- Manual Adjustment --}}
        @if(session('allow_manual_adjust'))
            <hr>
            <div class="alert alert-warning">
                {{ session('message') }}

                <form method="POST" action="{{ route('leaves.approve', $leave) }}">
                    @csrf
                    <input type="hidden" name="status" value="Approved">

                    <label class="mt-2">Manual Adjustment</label>
                    <input type="number"
                           name="manual_adjustment"
                           min="{{ session('required_adjustment') }}"
                           class="form-control"
                           required>

                    <button class="btn btn-success mt-2">Confirm Approval</button>
                </form>
            </div>
        @endif

    </div>
</div>

</div>
@endsection
