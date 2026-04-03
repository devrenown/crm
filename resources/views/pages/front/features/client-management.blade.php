@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-client-breadc.webp') }}") center/cover no-repeat;
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
                        CLIENT <br class="d-none d-md-block"> MANAGEMENT
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Organize client data, track interactions, and build strong relationships—all in one powerful platform.
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
                    <img src="{{ asset('images/features/feature-client.webp') }}" alt="Employee Management" class="img-fluid">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">WHAT IS CLIENT</span><br>
                        <span class="text-dark">MANAGEMENT SYSTEM?</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        A Client Management System helps businesses manage client information, track communication, and maintain strong relationships efficiently. It centralizes all client data and interactions, making it easier to stay organized and deliver better service. A Client Management System helps businesses manage client 
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralized client database</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Better communication tracking</p>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Improved relationship management</p>
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
                        <span class="text-primary">POWERFUL CLIENT</span><br>
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
                                <h5 class="mt-3">Client Database</h5>
                                <p class="text-muted small">Store and manage complete client information including contact details, company info, and history.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Contact Management</h5>
                                <p class="text-muted small">Organize client contacts and keep all communication details in one place.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Interaction Tracking</h5>
                                <p class="text-muted small">Track calls, meetings, emails, and follow-ups to maintain clear communication history.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Task & Follow-Up Management</h5>
                                <p class="text-muted small">Set reminders, assign follow-ups, and ensure timely communication with clients.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Document Management</h5>
                                <p class="text-muted small">Store and share client-related documents securely in one place.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Notes & Activity Logs</h5>
                                <p class="text-muted small">Add notes and maintain a record of all client interactions and updates.</p>
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
                    <h5>Add Client Details</h5>
                    <p class="small">Enter client information including contact details, company data, and requirements.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Track Interactions</h5>
                    <p class="small">Log calls, meetings, and communications to maintain a complete history.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Manage Tasks & Follow-Ups</h5>
                    <p class="small">Assign tasks, set reminders, and ensure timely follow-ups with clients.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Analyze & Improve</h5>
                    <p class="small">Review reports and insights to improve client relationships and business outcomes.</p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">

            <div class="d-flex" id="compare-banner">

                <!-- Admin UI -->
                <div class="mb-4 mb-lg-0">
                    
                    <img src="{{ asset('images/features/feature-client-ui.webp') }}" class="img-fluid rounded-4">
                </div>

            </div>
        </div>
    </section>
@endsection