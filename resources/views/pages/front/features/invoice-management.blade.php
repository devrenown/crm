@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-invoice-breadc.webp') }}") center/cover no-repeat;
        display: flex;
        align-items: center;
        padding: 80px 0;
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
                <div class="col-12 col-md-10 col-lg-8">

                    <h1 class="breadcrump-heading display-5 display-md-4 display-lg-3 fw-bold">
                        Invoice Management <br class="d-none d-md-block"> Software
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Keep your entire billing workflow streamlined with Renown's advanced invoice management software. Stay organised, improve cash flow visibility, and manage client billing across projects.
                    </p>

                    <!--<button class="btn btn-light text-primary fw-bold px-4 px-md-5 py-2 shadow-sm">-->
                    <!--    Request a Demo <i class="bi bi-arrow-right"></i>-->
                    <!--</button>-->

                </div>
            </div>

        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">

                <!-- LEFT IMAGE -->
                <div class="col-12 col-lg-6 mb-4 mb-lg-0 text-center">
                    <img src="{{ asset('images/features/feature-invoice.webp') }}" alt="Employee Management" class="img-fluid" loading="lazy">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">Smart Billing with a </span><br>
                        <span class="text-dark">CRM Invoice System</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        A powerful invoice management software that helps to businesses and startups streamline billing and improve financial visibility. It centralises invoicing workflow, from capturing client data to generating accurate invoices, accelerating approvals and tracking cash flow in real-time. In simple words, Renown CRM scales up how invoices are created, managed, and monitored, adapting to your operations while keeping everything organised, compliant, and accessible from a single dashboard.
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralised billing control</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Real-time visibility</p>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Smart workflow automation</p>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Seamless client syncing</p>
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
                        <span class="text-primary">Accelerate Payment Cycles </span><br>
                        with Automated Invoicing
                    </h2>

                    <p class="text-muted my-3">
                       Manual invoicing includes multiple errors that create approval bottlenecks and delays payments. With our online invoice management system, you can accelerate payment cycles and easily handle increased billing volumes. 
                    </p>

                    <!--<button class="btn btn-primary px-4 py-2">-->
                    <!--    Request Demo →-->
                    <!--</button>-->
                </div>

                <!-- RIGHT CARDS -->
                <div class="col-12 col-lg-7">
                    <div class="row">

                        <!-- Card -->
                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-clock fs-2 icon-box"></i>
                                <h5 class="mt-3">Invoice Creation</h5>
                                <p class="text-muted small">Generate accurate and professional invoices faster with our predefined and structured billing templates.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Invoice Customization</h5>
                                <p class="text-muted small">Personalise invoices based on your brand guidelines, with flexible formats tailored to the unique needs of businesses.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Payment Tracking</h5>
                                <p class="text-muted small">Monitor invoice status, payments, cash flows, and dues in real-time with our invoice monitoring system tracking feature.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Automated Billing</h5>
                                <p class="text-muted small">Automate recurring invoices, set reminders, and accelerate billing workflows to reduce manual errors and approval delays.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Tax & Discount Management</h5>
                                <p class="text-muted small">Manage line items, tasks, discounts, and subscription billing efficiently with our smart billing management system.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Client Integration</h5>
                                <p class="text-muted small">Sync client data seamlessly to accelerate their invoicing, stay compliant, and improve relationships through management workflows.
                                </p>
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
            <h2 class="fw-bold mb-3">Master Your Invoice Management Process in Four Steps</h2>
            <p class="mb-5">
                For businesses, our invoice management tool is the best choice. It is simple and navigation-friendly. Creating, sharing, and tracking invoices becomes much faster through automation. Start managing your billing operations like a professional - from day one.
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Create Invoice</h5>
                    <p class="small">Easily generate invoices using structured templates that are tailored to your business billing requirements.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Send to Client</h5>
                    <p class="small">Keep your clients happy by eliminating approval and invoice processing delays. Accelerate payments through integrated channels.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Track Payments</h5>
                    <p class="small">Our invoice monitoring system updates you on client payments with real-time tracking and automated status notifications.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Manage & Analyze</h5>
                    <p class="small">Access insights, manage records, and analyse billing performance for better financial decision making.</p>
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
                    <img src="{{ asset('images/features/feature-invoice-ui.webp') }}" class="img-fluid rounded-4" loading="lazy">
                </div>

            </div>
        </div>
    </section>
@endsection