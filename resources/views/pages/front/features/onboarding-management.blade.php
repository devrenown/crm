@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-onboard-breadc.webp') }}") center/cover no-repeat;
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

    @media (max-width: 768px) {
       #compare-banner {
         display: block !important;
       } 
    }
</style>

@section('content')
  <section id="breadcrump">
        <div class="container text-white">

            <div class="row mt-5">
                <div class="col-12 col-md-8 col-lg-6">

                    <h1 class="breadcrump-heading display-5 display-md-4 display-lg-3 fw-bold">
                        ONBOARDING <br class="d-none d-md-block"> MANAGEMENT
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Simplify the onboarding process by managing documents, tasks, and employee setup—all in one place.
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
                    <img src="{{ asset('images/features/feature-onboard.webp') }}" alt="Employee Management" class="img-fluid">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">WHAT IS ONBOARDING </span><br>
                        <span class="text-dark">MANAGEMENT SYSTEM?</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        An Onboarding Management System helps organizations efficiently manage the process of welcoming and setting up new employees. From document collection to task assignment, it ensures a smooth and structured onboarding experience. An Onboarding Management System helps organizations efficiently manage the process of welcoming and setting up new employees. 
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Simplified employee onboarding process</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralized document management</p>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Better new hire experience</p>
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
                        <span class="text-primary">POWERFUL ONBOARDING</span><br>
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
                                <h5 class="mt-3">Employee Onboarding </h5>
                                <p class="text-muted small">Easily add new employees and initiate the onboarding process with all required details.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Document Collection</h5>
                                <p class="text-muted small">Collect and manage important documents like ID proofs, contracts, and forms securely.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Onboarding Checklist</h5>
                                <p class="text-muted small">Create step-by-step onboarding checklists to ensure nothing is missed during the process.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Task Assignment</h5>
                                <p class="text-muted small">Assign onboarding tasks to HR, managers, or employees for smooth coordination.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Notifications & Reminders</h5>
                                <p class="text-muted small">Send automated alerts for pending tasks and document submissions.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Employee Data Integration</h5>
                                <p class="text-muted small">Automatically sync onboarding data into the main employee management system.</p>
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
                    <h5>Create Tasks</h5>
                    <p class="small">Quickly create tasks by adding essential details like descriptions, priorities, and deadlines to keep everything well-organized from the start.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Assign to Team</h5>
                    <p class="small">Assign tasks to the right team members or departments, ensuring clear responsibilities and smooth collaboration.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Track Progress</h5>
                    <p class="small">Monitor task status, updates, and ongoing activities in real-time to stay informed and maintain productivity.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Complete & Analyze</h5>
                    <p class="small">Review completed tasks, analyze performance, and generate insights to continuously improve your workflow.</p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">

            <div class="d-flex" id="compare-banner">

                <!-- Admin UI -->
                <div class="mb-4 mb-lg-0">
                    <h5 class="text-white px-4 py-2 d-inline-block mb-3"
                        style="background: linear-gradient(90deg,#2b6ef2,#a020f0); border-radius: 5px;">
                        ADMIN UI
                    </h5>
                    <img src="{{ asset('images/features/feature-onboard-admin-ui.png') }}" class="img-fluid rounded-4">
                </div>

                <!-- Arrow -->
                <div class="d-none d-lg-block">
                    <img src="{{ asset('images/features/arrow.webp') }}" class="img-fluid rounded-4">
                </div>

                <!-- Employee UI -->
                <div class="">
                    <h5 class="text-white px-4 py-2 d-inline-block mb-3"
                        style="background: linear-gradient(90deg,#2b6ef2,#a020f0); border-radius: 5px;">
                        EMPLOYEE UI
                    </h5>
                    <img src="{{ asset('images/features/feature-onboard-emp-ui.webp') }}" class="img-fluid rounded-4">
                </div>

            </div>
        </div>
    </section>
@endsection