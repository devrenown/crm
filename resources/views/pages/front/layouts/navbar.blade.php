<nav class="navbar navbar-expand-lg fixed-top custom-navbar pt-0">
    <div class="container-fluid mx-lg-5 shadow-sm rounded-pill mt-lg-3 bg-white">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('front') }}">
            <img src="{{ asset('images/front/logo-home.png') }}" alt="RenownCRM" class="m-0 p-0"
                    style="height:50px;">
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link position-relative fs-19 me-2 pb-0 active" href="{{ url('/#home') }}">Home</a>
                </li>
                <li class="nav-item"><a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#features') }}">Features</a>
                </li>
                <li class="nav-item"><a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#pricing') }}">Pricing</a></li>
                <li class="nav-item"><a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#how-it-works') }}">How It Works</a>
                </li>
                <li class="nav-item"><a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#testimonials') }}">Testimonials</a>
                </li>
                <li class="nav-item"><a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#contact') }}">Contact</a></li>
            </ul>
            
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
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('login') }}" class="nav-link fs-19 me-2"><i class="fa-regular fa-user"></i> Login</a>
                
                <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-primary border-white rounded-pill px-2 px-lg-4 fs-19 shadow">
                    Request Demo <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @endif
            
        </div>
        
    </div>
</nav>