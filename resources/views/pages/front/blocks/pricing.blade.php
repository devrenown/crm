<!-- Pricing Section -->
{{-- <section class="py-5 px-3 px-lg-5 bg-light" id="pricing">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">Pricing Plans</h2>
        <p class="text-muted">Choose the plan that best fits your business needs.</p>

        <form method="POST" class="row justify-content-center" action="/currency-switch">
            @csrf

            @php
              $currencies = config('country_currency');
            @endphp

            <div class="currency_switcher">
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

        <div class="col-md-4 col-lg-4 m-0" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            
            @if($plan->name == 'BRONZE' ?? false)
                <div class="popular-badge text-center">Most Popular</div>
            @endif

            @if($plan->name == 'SILVER' ?? false)
                <div class="popular-badge text-center">Recommended</div>
            @endif
            <div class="pricing-card card border-0 shadow-sm text-center position-relative">

                <div class="card-body p-2">

                    <div class="bg-light py-3 px-5">
                        <h5 class="fw-bold text-uppercase">{{ $plan->name == 'FREE TRIAL' ? '15 DAYS ' . $plan->name : $plan->name }}</h5>

                        @if ($plan->name == 'FREE TRIAL' ?? false)
                        <p class="small text-muted mb-1">No Credit Card Required</p>
                        @endif

                        <h2 class="fw-bold my-2">
                            {{ $currency->symbol }} {{ number_format($plan->display_price, 2) }}
                            <small class="fs-6 text-muted">/month</small>
                        </h2>

                        <p class="text-muted small">Billed quarterly</p>

                        <a href="{{ route('organization.signup', encrypt($plan->name)) }}" class="btn btn-dark w-100 fw-semibold">Choose Plan
                        </a>
                    </div>

                    <div class="d-flex justify-content-between position-relative">
                        <div class="text-start px-3 mt-3">
                            <h6 class="fw-bold text-primary mb-2">Limits</h6>

                            @foreach($features['limits'] ?? [] as $key => $value)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <a href="javascript:void(0)"
                           class="read-more mt-2 d-inline-block"
                           data-target="details-{{ $loop->index }}">
                            Read More →
                        </a>
                        
                    </div>
                    
                    <div class="plan-details text-start mt-2 px-3" id="details-{{ $loop->index }}">
                        
                        @if(isset($features['modules']))
                            <h6 class="fw-bold text-primary mt-3">Modules</h6>
                            @foreach($features['modules'] as $key => $value)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-{{ $value ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} me-2"></i>
                                    <span>{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                </div>
                            @endforeach
                        @endif
                        
                        @if(isset($features['features']))
                            <h6 class="fw-bold text-primary mt-3">Features</h6>
                            @foreach($features['features'] as $key => $value)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>

        </div>
        @endforeach
    </div>
</section> --}}

<section class="pricing-section bg-white py-5" id="pricing">
    <div class="container-fluid px-5">
        <div class="text-center">
            <div class="mb-4">
                <span class="section-title-badge">Pricing Plans</span>
            </div>

            <h2 class="section-title mb-3 mt-4">Simple, Transparent Pricing</h2>
            <p class="fs-5 text-secondary lh-base mt-3">Choose the plan that fits your business. All plans include a 15-day
                free trial.</p>
        </div>

        <div class="cards mt-5 row">
            
            @foreach($plans as $plan)
            @php 
                $features = json_decode($plan->features, true) ?? [];
            @endphp
                @if ($plan->id != 1)
                <div class="col-md-3 mb-5 mb-lg-3 mb-md-3">
                    <div class="card {{ $plan->is_popular ? 'bg-gradient-tri' : 'bg-white' }} shadow border-0 rounded">
                        @if ($plan->is_popular)
                        <div class="position-relative">
                            <span
                                class="popular-badge shadow-sm position-absolute py-2 px-3 rounded-pill fs-5 bg-gradient-yellow">MOST
                                POPULAR
                            </span>
                        </div>
                        @endif

                        <div class="card-header text-center m-2 {{ $plan->is_popular ? 'mt-4' : '' }} p-3 bg-light-gradient rounded">
                            <h5 class="fw-bold text-uppercase">{{ $plan->name == 'FREE TRIAL' ? '15 DAYS ' . $plan->name : $plan->name }}</h5>
                            
                            @if ($plan->name == 'FREE TRIAL' ?? false)
                                <small>No Credit Card Required</small>
                            @endif
    
                            <div class="text-blue">
                                <strong class="fs-2 fw-bold">{{ $currency->symbol }} {{ number_format($plan->display_price, 2) }}</strong>/month
                            </div>
                            <p class="mb-0">Billed Quarterly</p>
                        </div>
    
                        <div class="card-list {{ $plan->is_popular ? 'text-white' : '' }} p-3">
                            <h6 class="fw-bold {{ $plan->is_popular ? 'text-white' : 'text-blue' }} mb-2">Limits</h6>
                            
                            @foreach($features['limits'] ?? [] as $key => $value)
                                <div class="mb-2">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <strong class="{{ $plan->is_popular ? 'text-white' : 'text-secondary' }}">{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value }}</strong>
                                </div>
                            @endforeach
                            
                            <div class="mb-3">
                                <a href="javascript:void(0)" class="read-more {{ $plan->is_popular ? 'text-white' : 'text-blue' }}" data-target="details-{{ $loop->index }}">Read More <i class="fa-solid fa-arrow-right"></i> </a>
                            </div>
                            
                            <div class="plan-details text-start" id="details-{{ $loop->index }}">
                                
                                @if(isset($features['modules']))
                                    <h6 class="fw-bold {{ $plan->is_popular ? 'text-white' : 'text-blue' }}  mt-3">Modules</h6>
                                    @foreach($features['modules'] as $key => $value)
                                    <div class="mb-2">
                                        <i class="fa-solid fa-circle-{{ $value ? 'check' : 'xmark text-danger' }}"></i>
                                        <strong class="{{ $plan->is_popular ? 'text-white' : 'text-secondary' }}">{{ ucwords(str_replace('_', ' ', $key)) }}</strong>
                                    </div>
                                    @endforeach
                                @endif
                                
                                @if(isset($features['features']))
                                    <h6 class="fw-bold {{ $plan->is_popular ? 'text-white' : 'text-blue' }} mt-3">Features</h6>
                                    @foreach($features['features'] as $key => $value)
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa-solid fa-circle-check me-2"></i>
                                            <span>{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                                
                            <a href="{{ $plan->name == 'INTERPRISE' 
                                        ? '#' 
                                        : route('organization.signup', encrypt($plan->name)) }}"
                               class="btn {{ $plan->is_popular ? 'bg-white text-blue' : 'btn-primary' }} btn-lg border-3 border-top-0 border-white shadow-lg w-100">
                               
                               {{ $plan->name == 'FREE TRIAL' 
                                    ? 'Start Free Trial' 
                                    : ($plan->name == 'INTERPRISE' 
                                        ? 'Contact Us' 
                                        : 'Choose Plan') }}
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
    
                        </div>
                    </div>
                </div>
                @endif
                
            @endforeach

            {{-- <div class="col-md-3 mb-3">
                <div class="card bg-gradient position-relative shadow border-0 rounded">
                    <div class="position-relative">
                        <span
                            class="popular-badge shadow-sm position-absolute py-2 px-3 rounded-pill fs-5 bg-warning">MOST
                            POPULAR</span>
                    </div>

                    <div class="card-header text-center mt-4 m-2 p-3 bg-light-gradient rounded">
                        <h5 class="fw-bold">STARTER </h5>
                        <small>No Credit Card Required</small>

                        <div class="text-blue">
                            <strong class="fs-1 fw-bold">Rs 2,100</strong>/month
                        </div>
                        <p class="mb-0">Billed Quarterly</p>
                    </div>

                    <div class="card-list p-3">
                        <div class="mb-2 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>Employee: 20</strong>
                        </div>

                        <div class="mb-2 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>Clients: 2</strong>
                        </div>
                        <div class="mb-2 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>Project: 5</strong>
                        </div>
                        <div class="mb-2 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>Payroll Management</strong>
                        </div>
                        <div class="mb-2 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>Priority Support</strong>
                        </div>
                        <div class="mb-2 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>50GB Storage</strong>
                        </div>

                        <div class="mb-2 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>Customer Reports</strong>
                        </div>

                        <div class="mb-3 text-white">
                            <i class="fa-solid fa-circle-check"></i>
                            <strong>API Access</strong>
                        </div>

                        <div class="mb-3 text-white">
                            <a href="#" class="text-white">Read More <i class="fa-solid fa-arrow-right"></i> </a>
                        </div>

                        <button
                            class="btn bg-white text-blue btn-lg border-3 border-top-0 border-white shadow-lg w-100 fw-bold">Start
                            Choose Plan</button>

                    </div>
                </div>
            </div> --}}

            <div class="text-center mt-4">
                <p class="fs-5 text-secondary">All plans include 15-day free trial • No credit card required •
                    Cancel anytime</p>
            </div>

        </div>
    </div>
</section>


@push('styles')

<style>
    /* Modern Card Hover Effect */
    .pricing-card {
        border-radius: 16px;
        transition: all .3s ease;
        background: #fff;
    }

    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,.12);
    }

    .currency_switcher {
        max-width: 10rem;
    }

    /* Popular badge */
    .popular-badge {
        position: absolute;
        top: -22px;
        left: 50%;
        transform: translateX(-50%);
        background: #6f7cff;
        color: #fff;
        padding: 2px 30px;
        width: 65%;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    /* Read more */
    .read-more {
        /*position: absolute;*/
        /*bottom: 10px;*/
        /*right: 20px;*/
        /*color: #0d6efd;*/
        /*font-weight: 500;*/
        cursor: pointer;
    }

    /* Hidden details */
    .plan-details {
        display: none;
        animation: slideDown .3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

</style>

@endpush

@push('scripts')

<script> 
    document.querySelectorAll('.read-more').forEach(btn => {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);

            if (target.style.display === 'block') {
                target.style.display = 'none';
                this.innerText = 'Read More →';
            } else {
                target.style.display = 'block';
                this.innerText = 'Read Less ←';
            }
        });
    });

</script>

@endpush