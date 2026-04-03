<style>

    .navbar {
    overflow: visible !important;
}

/* Desktop hover only */
@media (min-width: 992px) {
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .nav-item.dropdown:hover #featureMenu i {
        transform: rotate(180deg);
        transition: all 0.25s ease;
    }

    .dropdown-menu {
        display: block;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.25s ease;
    }
}

/* Mobile fix */
@media (max-width: 991px) {
    .dropdown-menu {
        display: none !important; 
        opacity: 1;
        visibility: visible;
        transform: none;
    }

    .dropdown-menu.show {
        display: block !important;
    }
}

/* Megamenu styling */
.has-megamenu {
    position: static !important;
}

.megamenu {
    position: absolute;
    top: 120%;
    left: 0;
    width: 100%;
    z-index: 9999;
    background: #fff;
    border-radius: 15px;
}

/* Mobile megamenu behavior */
@media (max-width: 991px) {
    .megamenu {
        position: static;
        width: 100%;
        margin-top: 10px;
    }
}

.text-primary {
    color: #0059FF !important;
}
</style>

<nav class="navbar navbar-expand-lg fixed-top custom-navbar pt-0">
    <div class="container-fluid mx-lg-5 shadow-sm mt-lg-3 bg-white position-relative rounded-pill">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('front') }}">
            <img src="{{ asset('images/front/logo-home.png') }}" alt="RenownCRM" class="m-0 p-0"
                    style="height:50px;">
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link position-relative fs-19 me-2 pb-0 active" href="{{ url('/#home') }}">Home</a>
                </li>

                <li class="nav-item dropdown has-megamenu position-static">
                    <a class="nav-link position-relative fs-19 me-2 pb-0"
                        href="#" id="featureMenu" data-bs-toggle="dropdown">
                        Features <i class="fa-solid fa-angle-down"></i>
                    </a>

                    <!-- MEGA MENU -->
                    <div class="dropdown-menu megamenu shadow-lg border-0 p-0">
                        <div class="container-fluid p-0">
                            <div class="row g-0">

                                <!-- LEFT CONTENT -->
                                <div class="col-lg-9 p-4 p-md-5">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'employee-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Employee Management</p>
                                                        <p class="text-muted">Manage your team with ease—track roles, performance, and employee details from one dashboard.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'leave-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-calendar-event text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Leave Management</p>
                                                        <p class="text-muted">Simplify leave requests, approvals, and tracking with an organized system for your team.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'client-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-people text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Client Management</p>
                                                        <p class="text-muted">Keep track of client information, interactions, and relationships to enhance satisfaction and retention.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'task-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-clipboard-check text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Task Management</p>
                                                        <p class="text-muted">Organize, assign, and track tasks effortlessly to boost team productivity.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'onboarding-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-hand-index-thumb text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Onboarding</p>
                                                        <p class="text-muted">Simplify new employee onboarding with organized workflows, task tracking, and smooth coordination.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'invoice-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-receipt-cutoff text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Invoice Management</p>
                                                        <p class="text-muted">Create, send, and track invoices effortlessly while managing payments and financial records—all from one powerful platform.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'project-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-kanban text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Project Management</p>
                                                        <p class="text-muted">Plan, track, and manage projects efficiently with seamless team collaboration.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'payroll-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-cash-coin text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Payroll Management</p>
                                                        <p class="text-muted">Manage salaries, deductions, and payments efficiently with an accurate and streamlined payroll system.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                       <div class="col-md-4">
                                            <a href="{{ route('feature.detail', 'ticket-management') }}">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-ticket-detailed text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <p class="fw-bold mb-1 fs-6 text-dark">Ticket Management</p>
                                                        <p class="text-muted">Manage salaries, deductions, and payments efficiently with an accurate and streamlined payroll system.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <!-- RIGHT IMAGE -->
                                <div class="col-lg-3 d-none d-lg-flex align-items-center justify-content-center border-start bg-light rounded-end">
                                    <div class="text-center p-4">
                                        <img src="{{ asset('images/crm-icon.png') }}" alt="" class="img-fluid">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#pricing') }}">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#how-it-works') }}">How It Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#testimonials') }}">Testimonials</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative fs-19 me-2 pb-0" href="{{ url('/#contact-section') }}">Contact</a>
                </li>
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