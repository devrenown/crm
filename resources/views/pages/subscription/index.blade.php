@extends('layouts.app')

@section('page-content')

@php
    $features = json_decode($plan->features, true) ?? [];
    $daysRemaining = now()->diffInDays($subscription->end_date, false);
@endphp

<div class="container py-5">

    <!-- Page Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">Your Subscription</h2>
        <p class="text-muted">Manage your plan, billing, and upgrades.</p>
    </div>

    <div class="row justify-content-center">

        <!-- Current Plan Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <h4 class="fw-bold">{{ strtoupper($plan->name) }}</h4>

                <p class="fs-3 fw-bold text-primary mt-3">
                    @if($plan->price == 0) Free @else ₨ {{ number_format($plan->price, 2) }} /month @endif
                </p>

                <hr>

                <!-- Remaining Days -->
                <div class="row">
                	<div class="col-lg-4">
                		<p class="mb-2">
	                        <strong><i class="fa-solid fa-hourglass-start text-primary me-1 fs-5"></i> Remaining Days:</strong>
	                        @if($daysRemaining > 0)
	                            <span class="text-success">{{ number_format($daysRemaining) }} days left</span>
	                        @else
	                            <span class="text-danger">Expired</span>
	                        @endif
	                    </p>

	                    <!-- Subscription End Date -->
	                    <p class="mb-2">
	                        <strong><i class="fa-regular fa-calendar-days text-primary me-1 fs-5"></i> Renewal / Expiry Date:</strong> 
	                        {{ date('d-M-Y', strtotime($subscription->end_date)) }}
	                    </p>

	                    <!-- Status -->
	                    <p class="mb-2">
	                        <strong><i class="fa-solid fa-bell text-primary me-1 fs-5"></i> Status:</strong>
	                        @if($subscription->status == '1')
	                            <span class="badge bg-inverse-success">Active</span>
	                        @elseif($subscription->status =='2')
	                            <span class="badge bg-inverse-danger">Expired</span>
	                        @else
	                            <span class="badge bg-inverse-light text-dark">Canceled</span>
	                        @endif
	                    </p>
                	</div>

                	<div class="col-lg-4">
                		<!-- Limits -->
                        <strong class="fw-semibold">Limits</strong>
                        <ul class="list-unstyled ms-2">
                            @foreach($features['limits'] as $key => $value)
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                                    <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                </li>
                            @endforeach
                        </ul>
                	</div>

                	<div class="col-lg-4">
                		<!-- Extra Features -->
                        <strong class="fw-semibold">Extra Features</strong>
                        <ul class="list-unstyled ms-2">
                            @foreach($features['features'] as $key => $value)
                                <li class="mb-1">
                                    <i class="bi bi-star-fill text-warning me-2"></i>
                                    <strong>{{ ucwords(str_replace('_',' ', $key)) }}:</strong>

                                    @if(is_bool($value))
                                        {{ $value ? 'Yes' : 'No' }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                	</div>
                </div>

                <hr>

                <div class="mt-3">

                    <!-- Modules -->
                    <strong class="fw-semibold mt-3">Modules</strong>
                    <ul class="list-unstyled d-flex flex-wrap gap-2">
                        @foreach($features['modules'] as $key => $value)
                            <li class="mb-1 btn bg-inverse-light btn-sm">
                                @if($value)
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    <strong>{{ ucwords(str_replace('_',' ', $key)) }}</strong>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger me-1"></i>
                                    <strong>{{ ucwords(str_replace('_',' ', $key)) }}</strong>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                </div>

                <!-- Upgrade Button -->
                <div class="text-end mt-4">
                    <a href="{{ route('subscription.upgrade') }}"
                       class="btn btn-primary btn-sm">
                        Upgrade Plan
                    </a>
                </div>

            </div>
        </div>

    </div>


    <!-- Billing History (Optional) -->
    <div class="row mt-3 justify-content-center">
        <h4 class="fw-bold mb-3"><i class="fa-regular fa-credit-card"></i> Subscription History</h4>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if(count($subscriptions) == 0)
                    <p class="text-muted text-center">No invoices found.</p>
                @else
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Plan</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $key => $subscription)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $subscription->plan->name }}</td>
                                <td>{{ $subscription->plan->duration }} days</td>
                                <td>₨ {{ number_format($subscription->plan->price, 2) }}/30days</td>
                                <td>
                                    @if($subscription->status == '1')
                                        <span class="badge bg-inverse-success">Active</span>
                                    @else
                                        <span class="badge bg-inverse-danger">Expire</span>
                                    @endif
                                </td>
                                <td>{{ date('d M Y', strtotime($subscription->start_date)) }}</td>
                                <td>{{ date('d M Y', strtotime($subscription->end_date)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
