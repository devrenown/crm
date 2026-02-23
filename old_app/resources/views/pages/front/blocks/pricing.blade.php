<!-- Pricing Section -->
<section class="p-5 bg-light" id="pricing">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">Pricing Plans</h2>
        <p class="text-muted">Choose the plan that best fits your business needs.</p>

        <form method="POST" class="row justify-content-center" action="/currency-switch">
            @csrf

            @php
              $currencies = config('country_currency');
            @endphp

            <div class="col-1">
                <select name="currency" class="form-control form-select" onchange="this.form.submit()">
                    @foreach($currencies as $cur)
                        <option value="{{ $cur }}" {{ $currency->code == $cur ? 'selected' : '' }}>
                            {{ $cur }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($plans as $plan)
        @php 
            $features = json_decode($plan->features, true) ?? [];
        @endphp

        <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="pricing-card card border-0 shadow-sm pb-2 text-center position-relative">

                <!-- Highlight Popular Plan -->
                @if($plan->name == 'PRO' ?? false)
                    <span class="badge bg-warning text-dark position-absolute top-0 start-50 translate-middle rounded-pill px-3 py-2 shadow-sm">
                        ⭐ Popular
                    </span>
                @endif

                <div class="card-body p-4">
                    <h5 class="fw-bold">{{ $plan->name }}</h5>

                    <p class="fs-4 fw-bold mt-2 mb-0 text-primary">
                        {{ $currency->symbol }} {{ number_format($plan->display_price, 2) }}/month
                    </p>

                    <p class="text-muted small mb-4">Billed quarterly</p>

                    @if ($plan->name == 'FREE TRIAL')
                     <strong>No Credit Card Required</strong>
                    @endif

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


                    <a href="{{ route('organization.signup', encrypt($plan->name)) }}" class="btn btn-primary mt-3 px-4 py-2 fw-semibold">Choose Plan</a>
                </div>

            </div>
        </div>
        @endforeach
    </div>
</section>

@push('styles')

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