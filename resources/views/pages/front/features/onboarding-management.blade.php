@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-onboard-breadc.webp') }}") center/cover no-repeat;
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
                <div class="col-12 col-md-10 col-lg-8">

                    <h1 class="breadcrump-heading display-5 display-md-4 display-lg-3 fw-bold">
                         Onboarding Management <br class="d-none d-md-block"> Software
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Let’s make onboarding smoother for both teams and new hires. With our simple and easy onboarding management tool, new hires can complete the process in no time. 
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
                    <img src="{{ asset('images/features/feature-onboard.webp') }}" alt="Employee Management" class="img-fluid" loading="lazy">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">Accelerate Induction with a </span><br>
                        <span class="text-dark">Smart Onboarding System</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        Renown's employee onboarding software helps solve critical hiring bottlenecks. It not only accelerates the induction process but also simplifies the experience for new hires. Digital and automated workflows can also reduce the time spent per hire significantly. Through centralised tasks, documentation, and communication, you can provide every new hire a smooth, organised, and efficient onboarding journey. 
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Structured onboarding workflows</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralised employee data</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Automated task tracking</p>
                    </div>
                     <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Consistent onboarding experience</p>
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
                        <span class="text-primary">Centralise Onboarding with</span><br>
                        Advanced Features
                    </h2>

                    <p class="text-muted my-3">
                      With Renown's online software, you can build a centralised and collaborative onboarding system. Because digital and automated workflows can boost new employee retention by upto 82%. 
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
                                <h5 class="mt-3">Employee Onboarding </h5>
                                <p class="text-muted small">Streamline onboarding journeys for new hires. Structured workflows are designed to provide consistent employee experiences.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Document Collection</h5>
                                <p class="text-muted small">Collect and manage employee documents securely in a centralised repository, and access data in real-time.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Onboarding Checklist</h5>
                                <p class="text-muted small">Our structured onboarding system provides a complete checklist to complete the induction process - tailored to each employee's role.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Task Assignment</h5>
                                <p class="text-muted small">Through the employee onboarding platform, you can assign induction-related tasks to maintain process clarity and accountability.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Notifications & Reminders</h5>
                                <p class="text-muted small">You can set automated alerts and reminders to help your HR team stay on track with the new employee onboarding task.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Employee Data Integration</h5>
                                <p class="text-muted small">Integrate employee data seamlessly across systems for unified onboarding and operational efficiency.</p>
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
            <h2 class="fw-bold mb-3">Onboard New Employees in Four Simple Steps</h2>
            <p class="mb-5">
                Our simple onboarding management tool handles the entire new hiring workflow - from initial setup to completion. Maintain visibility, consistency, and control throughout the entire onboarding process.
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Create Tasks</h5>
                    <p class="small">Set up onboarding tasks based on roles, workflows, and employee requirements efficiently.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Assign to Team</h5>
                    <p class="small">Allocate tasks to relevant team members, ensuring accountability and smooth onboarding execution.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Track Progress</h5>
                    <p class="small">Monitor onboarding progress in real time with visibility across all assigned tasks.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Complete & Analyze</h5>
                    <p class="small">Evaluate onboarding performance and optimise workflows using actionable insights and detailed reporting.</p>
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
                    <img src="{{ asset('images/features/feature-onboard-admin-ui.png') }}" class="img-fluid rounded-4" loading="lazy">
                </div>

                <!-- Arrow -->
                <div class="d-none d-lg-block">
                    <img src="{{ asset('images/features/arrow.webp') }}" class="img-fluid rounded-4" loading="lazy">
                </div>

                <!-- Employee UI -->
                <div class="">
                    <h5 class="text-white px-4 py-2 d-inline-block mb-3"
                        style="background: linear-gradient(90deg,#2b6ef2,#a020f0); border-radius: 5px;">
                        EMPLOYEE UI
                    </h5>
                    <img src="{{ asset('images/features/feature-onboard-emp-ui.webp') }}" class="img-fluid rounded-4" loading="lazy">
                </div>

            </div>
        </div>
    </section>
@endsection