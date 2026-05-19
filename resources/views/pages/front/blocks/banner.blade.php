<style>
    
    @media (max-width: 768px) {

        .hero-title {
            font-size: 2rem !important;
            line-height: 1.2;
        }

        .hero-text {
            font-size: 15px;
        }

    }
</style>

<!-- Hero Section -->
    <section class="hero-section position-relative" id="home">
        <div class="container-fluid mx-lg-5">
            <div class="row align-items-center gy-5">

                <!-- Left Content -->
                <div class="col-lg-6 mt-lg-0">
                    <span class="badge badge-soft mb-3">
                        <i class="fa-solid fa-bolt-lightning text-warning"></i> All-in-One Business Management
                    </span>

                    <h1 class="hero-title">
                        <span class="text-gradient fw-bold text-uppercase">RENOWN SYSTEM </span> – <br>The Best CRM Solution for, <br> Business Growth.
                    </h1>

                    <p class="hero-text mb-5">
                        Give your business a unified CRM solution that elevates operational efficiency through workflow automation and powers growth with advanced performance analytics.
                    </p>

                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-primary btn-lg border-3 border-top-0 border-white shadow-sm">
                            Request a Demo →
                        </a>
                        <a href="{{ route('organization.signup', encrypt('FREE TRIAL')) }}" class="btn btn-outline-primary btn-lg shadow-sm">
                            Start a Free Trial →
                        </a>
                    </div>
                </div>

                
            <!-- Mobile Hero -->

            <div class="col-lg-6 mt-0 text-end position-relative">

                <picture>
                    <!-- Mobile -->
                    <source
                        media="(max-width: 768px)"
                        srcset="./images/banner-right-phone.webp"
                        type="image/webp">

                    <!-- Desktop -->
                    <source
                        media="(min-width: 769px)"
                        srcset="./images/hero-right.webp"
                        type="image/webp">

                    <img
                        src="./images/hero-right.webp"
                        class="img-fluid hero-image"
                        alt="Best CRM tools for small businesses"
                        fetchpriority="high"
                        decoding="async"
                        width="700"
                        height="600">
                </picture>

            </div>

            </div>

            <div id="hero-features-card" class="position-absolute pe-lg-5 w-100">
                <div class="cards row g-3 pe-lg-5">
                    <div class="col-md-2 col-lg-2 col-6">
                        <div class="card d-flex align-items-center justify-content-center p-3 shadow">
                            <div><img src="./images/ph_handshake-thin.svg" class="mb-2" alt="Onboarding Management" loading="lazy" decoding="async" width="48" height="48"></div>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Onboarding</p>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Management</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-6">
                        <div class="card d-flex align-items-center justify-content-center p-3 shadow">
                            <div><img src="./images/uit_calender.svg" class="mb-2" alt="Leave Management" loading="lazy" decoding="async" width="48" height="48"></div>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Leave</p>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Management</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-6">
                        <div class="card d-flex align-items-center justify-content-center p-3 shadow">
                            <div><img src="./images/project icon.svg" class="mb-2" alt="Project Management" loading="lazy" decoding="async" width="48" height="48"></div>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Project</p>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Management</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-6">
                        <div class="card d-flex align-items-center justify-content-center p-3 shadow">
                            <div><img src="./images/employee icon.svg" class="mb-2" alt="Employee Management" loading="lazy" decoding="async" width="48" height="48"></div>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Employee</p>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Management</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-6">
                        <div class="card d-flex align-items-center justify-content-center p-3 shadow">
                            <div><img src="./images/chat icon.svg" class="mb-2" alt="Chat System" loading="lazy" decoding="async" width="48" height="48"></div>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Chat</p>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">System</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-6">
                        <div class="card d-flex align-items-center justify-content-center p-3 shadow">
                            <div><img src="./images/solar_wallet-money-linear.svg" class="mb-2" alt="Payroll Management" loading="lazy" decoding="async" width="48" height="48"></div>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Payroll</p>
                            <p class="mb-0 fs-6 fs-md-5 fs-lg-5">Management</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>