@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-invoice-breadc.webp') }}") center/cover no-repeat;
        display: flex;
        align-items: center;
    }

    .breadcrump-heading {
        font-weight: 700;
    }

    #how-it-works {
        background: url("{{ asset('images/features/feature-how-it-bg.webp') }}") center/cover no-repeat;
    }

    #feature-detail {
        background: url("{{ asset('images/features/feature-bg.webp') }}") center/cover no-repeat;
    }
</style>

@section('content')
  <section id="breadcrump">
        <div class="container text-white">

            <div class="row mt-5">
                <div class="col-12 col-md-8 col-lg-6">

                    <h1 class="breadcrump-heading display-5 display-md-4 display-lg-3 fw-bold">
                        INVOICE <br class="d-none d-md-block"> MANAGEMENT
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Create, send, and track invoices effortlessly while managing payments and financial records—all from one powerful platform.
                    </p>

                    <button class="btn btn-light text-primary fw-bold px-4 px-md-5 py-2 shadow-sm">
                        Request a Demo <i class="bi bi-arrow-right"></i>
                    </button>

                </div>
            </div>

        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">

                <!-- LEFT IMAGE -->
                <div class="col-12 col-lg-6 mb-4 mb-lg-0 text-center">
                    <img src="{{ asset('images/features/feature-invoice.webp') }}" alt="Employee Management" class="img-fluid">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">WHAT IS INVOICE</span><br>
                        <span class="text-dark">MANAGEMENT SYSTEM?</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        An Invoice Management System helps businesses create, manage, and track invoices efficiently. It streamlines the billing process, reduces manual errors, and ensures accurate financial records. With everything in one place, you can stay on top of payments and improve cash flow. An Invoice Management System .
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Easy invoice creation</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Real-time payment tracking</p>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Organized billing records</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light" id="feature-detail">
        <div class="container">

            <div class="row align-items-center">

                <!-- LEFT TEXT -->
                <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                    <h2 class="fw-bold">
                        <span class="text-primary">POWERFUL INVOICE</span><br>
                        MANAGEMENT FEATURES
                    </h2>

                    <p class="text-muted my-3">
                       Manage your workforce efficiently with smart tools designed to simplify operations, improve productivity, and give you full control over your team.
                    </p>

                    <button class="btn btn-primary px-4 py-2">
                        Request Demo →
                    </button>
                </div>

                <!-- RIGHT CARDS -->
                <div class="col-12 col-lg-7">
                    <div class="row">

                        <!-- Card -->
                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-clock fs-2 icon-box"></i>
                                <h5 class="mt-3">Invoice Creation</h5>
                                <p class="text-muted small">Create professional invoices quickly with all essential details including client information, products/services, pricing, and taxes.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Invoice Customization</h5>
                                <p class="text-muted small">Customize invoice templates with your company logo, branding, and personalized details for a professional look.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Payment Tracking</h5>
                                <p class="text-muted small">Track invoice status in real-time with clear labels such as Paid, Pending, or Overdue to stay updated on payments.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Automated Billing</h5>
                                <p class="text-muted small">Set up recurring invoices and automate billing processes to save time and reduce manual work.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Tax & Discount Management</h5>
                                <p class="text-muted small">Easily apply taxes, discounts, and adjustments to ensure accurate billing calculations.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Client Integration</h5>
                                <p class="text-muted small">Link invoices with client details to maintain organized records and better manage billing history.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 text-white" id="how-it-works">

        <div class="container text-center">

            <!-- Heading -->
            <h2 class="fw-bold mb-3">HOW IT WORKS</h2>
            <p class="mb-5">
                Manage your employees effortlessly with a simple and streamlined process designed for maximum efficiency.
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Create Invoice</h5>
                    <p class="small">Add client details, products or services, pricing, and taxes to generate a professional invoice in minutes.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Send to Client</h5>
                    <p class="small">Share invoices instantly with clients via email or directly through the system for quick delivery.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Track Payments</h5>
                    <p class="small">Monitor payment status in real-time and stay updated on pending, paid, or overdue invoices.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Manage & Analyze</h5>
                    <p class="small">Maintain records and analyze reports to improve financial planning and decision-making.</p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">

            <div id="compare-banner">

                <!-- Admin UI -->
                <div class="mb-4 mb-lg-0">
                    {{-- <h5 class="text-white px-4 py-2 d-inline-block mb-3"
                        style="background: linear-gradient(90deg,#2b6ef2,#a020f0); border-radius: 5px;">
                        ADMIN UI
                    </h5> --}}
                    <img src="{{ asset('images/features/feature-invoice-ui.webp') }}" class="img-fluid rounded-4">
                </div>

            </div>
        </div>
    </section>
@endsection