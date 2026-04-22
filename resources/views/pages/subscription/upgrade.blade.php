@extends('layouts.app')

@section('page-content')

    <!-- Pricing Section -->
    <section class="p-5 bg-light">

        <div class="text-end">
            <button onclick="window.history.back()" class="btn btn-dark btn-sm">Back</button>
        </div>

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Pricing Plans</h2>
            <p class="text-muted">Choose the plan that best fits your business needs.</p>
        </div>

        <div class="row g-4">
            @foreach($plans as $plan)
            @php 
                $features = json_decode($plan->features, true) ?? [];
            @endphp

            @if ($plan->name != 'FREE TRIAL')
            <div class="col-md-4 col-lg-4">
                <div class="pricing-card card shadow-sm pb-3 text-center position-relative {{ $current_plan->id == $plan->id ? 'border border-success' : '' }}">

                    <!-- Highlight Popular Plan -->
                    @if($plan->name == 'PRO' ?? false)
                        <span class="badge bg-warning text-dark position-absolute top-0 start-50 translate-middle rounded-pill px-3 py-2 shadow-sm">
                            ⭐ Popular
                        </span>
                    @endif

                    <div class="card-body p-4">
                        <h5 class="fw-bold">{{ $plan->name }}</h5>

                        <p class="fs-5 fw-bold mt-2 mb-0 text-primary">
                            {{ $currency->symbol }} {{ number_format($plan->display_price, 2) }}/month
                        </p>

                        <p class="text-muted small mb-4">Billed quarterly</p>

                        <ul class="list-unstyled text-start mx-auto" style="max-width: 260px;">

                            {{-- LIMITS --}}
                            @if(isset($features['limits']))
                                <li class="fw-bold mt-2 mb-1 text-primary">Limits</li>
                                @foreach($features['limits'] as $key => $value)
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                                    </li>
                                @endforeach
                            @endif

                            {{-- MODULES --}}
                            @if(isset($features['modules']))
                                <li class="fw-bold mt-3 mb-1 text-primary">Modules</li>
                                @foreach($features['modules'] as $key => $value)
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-{{ $value ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} me-2"></i>
                                        <span>{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value ? 'Enabled' : 'Disabled' }}</span>
                                    </li>
                                @endforeach
                            @endif

                            {{-- FEATURES --}}
                            @if(isset($features['features']))
                                <li class="fw-bold mt-3 mb-1 text-primary">Features</li>
                                @foreach($features['features'] as $key => $value)
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-{{ $value ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} me-2"></i>
                                        
                                        @if(is_bool($value))
                                            <span>{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value ? 'Yes' : 'No' }}</span>
                                        @else
                                            <span>{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                                        @endif
                                        
                                    </li>
                                @endforeach
                            @endif

                        </ul>

                        @php
                          $isCurrentPlan = $current_plan->id == $plan->id;
                          $isExpired     = $subscription && \Carbon\Carbon::parse($subscription->end_date)->isPast();
                        @endphp

                        @if ($isCurrentPlan && ! $isExpired)
                            <a href="javascript:void(0)" class="btn btn-success btn-sm mt-3">
                                Current Plan
                            </a>

                        @elseif($isCurrentPlan && $isExpired)
                            <button
                                class="btn btn-info btn-sm mt-3 pay-button"
                                data-plan-id="{{ $plan->id }}"
                                data-plan-name="{{ $plan->name }}"
                                data-subscription-id="{{ $subscription->id }}"
                                data-plan-price="{{ $plan->display_price }}">
                                Renew
                            </button>

                        @else
                            <button
                                class="btn btn-primary btn-sm mt-3 pay-button"
                                data-plan-id="{{ $plan->id }}"
                                data-plan-name="{{ $plan->name }}"
                                data-subscription-id="{{ $subscription->id }}"
                                data-plan-price="{{ $plan->display_price }}">
                                Upgrade
                            </button>
                        @endif
                    </div>

                </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>

    @push('page-styles')

    <style>
        /* Modern Card Hover Effect */
        .pricing-card {
            transition: transform .3s ease, box-shadow .3s ease;
            border-radius: 15px;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
        }
    </style>

    @endpush

    @push('page-scripts')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

        <script>
            $(document).on("click", ".pay-button", function () {

                let planId          = $(this).data("plan-id");
                let planName        = $(this).data("plan-name");
                let amount          = $(this).data("plan-price") * 3 * 100;
                let subscription    = $(this).data('subscription-id');
                amount = amount == 0 ? 1 * 100 : amount;

                $.post("{{ route('razorpay.order.create') }}", {
                    _token: "{{ csrf_token() }}",
                    amount: amount
                }, function (order) {

                    var options = {
                        "key": "{{ env('RAZORPAY_API_KEY_TEST') }}",
                        "amount": order.amount,
                        "currency": order.currency,
                        "name": "Renown CRM",
                        "description": "Upgrade to " + planName + " Plan",
                        "order_id": order.id,
                        "handler": function (response) {

                            $.post("{{ route('razorpay.payment.store') }}", {
                                _token: "{{ csrf_token() }}",
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                                razorpay_payment_amount: amount,
                                subscription_id: subscription,
                                plan_id: planId,
                            }, function (res) {
                                console.log(res);

                                if (res.success) {
                                    alert(res.message ?? 'Payment Successfull');
                                    location.reload();
                                }else {
                                    alert(res.message ?? 'Something went wrong!');
                                }
                            });
                        },
                        "prefill": {
                            "name": "{{ auth()->user()->fullname }}",
                            "email": "{{ auth()->user()->email }}"
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();

                });
            });
        </script>

    @endpush

@endsection