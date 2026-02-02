    <!-- Navbar -->
    <style>
        .nav-link {
            font-size: 18px !important;
            color: black !important;
        }
    </style>

    <nav class="navbar navbar-expand-lg fixed-top glass" style="z-index: 999;">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <!-- <a class="navbar-brand fw-bold" href="#">RenownCRM</a> -->
            <div>
                <a href="{{ route('front') }}" class="nav-link">
                    <img src="{{ asset('images/front/logo-home.png') }}" alt="RenownCRM" class="m-0 p-0"
                        style="height:50px;">
                    
                </a>
            </div>

            <a class="navbar-toggler p-0" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: none;">
                <span class="navbar-toggler-icon"></span>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('front') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#features') }}">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#pricing') }}">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#how-it-works') }}">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#testimonials') }}">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#contact') }}">Contact</a></li>

                    <!-- Divider (optional for spacing) -->
                    <li class="nav-item d-none d-lg-block mx-2">
                        <span class="text-muted mx-5">|</span>
                    </li>

                    @if (Auth::check())
                        <div>
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="btn btn-sm text-white"
                                    style="background: var(--gradient-blue);">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                        </div>

                    @else
                        <div class="d-flex">
                            <!-- Login Button -->
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm me-2 border-0" id="login-btn">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </li>
                            <!-- Sign Up Button -->

                            {{-- <li class="nav-item">
                                <a href="{{ route('signup') }}" class="btn btn-sm text-white"
                                    style="background: var(--gradient-blue);">
                                    <i class="bi bi-person-plus"></i> Sign Up
                                </a>
                            </li> --}}
                        </div>
                    @endif

                </ul>
            </div>
        </div>
    </nav>