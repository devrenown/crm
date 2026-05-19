@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-project-breadc.webp') }}") center/cover no-repeat;
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
                       Project Management  <br class="d-none d-md-block"> Software
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Businesses must utilize their limited resources to their full potential. With Renown System project management software, you can help such dynamic teams meet deadlines and deliver outcomes effectively - through collaboration and planned workflows. 
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
                    <img src="{{ asset('images/features/feature-project.webp') }}" alt="Employee Management" class="img-fluid" loading="lazy">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">Manage Global Teams with </span><br>
                        <span class="text-dark">Renown Project CRM</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        Renown’s project management software is not just designed for small businesses. It supports global team collaboration through adequate task allocation and tracking progress. You can better control your resources while your teams get more clarity about daily KRAs. The centralized system software, with easy access to tasks and data, eliminates confusion, keeps teams aligned, and ensures accountability. Additionally, the integrated task tracking software helps streamline workflows so your teams can prioritize tasks based on deadlines and deliver projects with confidence. 
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralized project tracking</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Better team collaboration</p>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Real-time project updates</p>
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
                        <span class="text-primary">Boost Project Success 10X </span><br>
                        with Our Management Tool
                    </h2>

                    <p class="text-muted my-3">
                      Renown’s simple project management tool allows you to build projects end-to-end. With its powerful features, you can personalize workflows, track industry-specific metrics, and do a lot more. Develop accountability in your teams through engagement and high productivity, for your business growth. 
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
                                <h5 class="mt-3">Project Creation</h5>
                                <p class="text-muted small">Create and onboard global teams from separate departments easily.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Priority Management</h5>
                                <p class="text-muted small">Decide priorities based on project deadlines and alert assigned teams.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Task Status Tracking</h5>
                                <p class="text-muted small">Through the task tracking feature, our project management software monitors team progress.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Assign Task</h5>
                                <p class="text-muted small">Allocate responsibilities suitable and relevant to each team member from a single project management CRM software.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Due Dates & Deadlines</h5>
                                <p class="text-muted small">With Due Dates, you can ensure the timely delivery of each project from your respective teams. 
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Team Collaboration</h5>
                                <p class="text-muted small">The unified CRM dashboard fosters team collaboration and faster execution.</p>
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
            <h2 class="fw-bold mb-3">Step Up Your Project Execution Speed with Renown</h2>
            <p class="mb-5">
                Our project management CRM comes with an intuitive dashboard to simplify project execution for businesses. This simple project management system allows you to streamline tasks, track progress, and strengthen team collaboration for consistent, on-time delivery.
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Create Tasks</h5>
                    <p class="small">Easy to create tasks in the Renown project management system for better clarity, accountability, and streamlined workflows.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Assign to Team</h5>
                    <p class="small">Allocate tasks to respective teams as per their individual experience and relevant expertise.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Track Progress</h5>
                    <p class="small">Monitor task status using Renown’s project management software with a task tracking feature.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Complete & Analyze</h5>
                    <p class="small">Review outcomes on the project management CRM and compare them against industry-specific metrics.</p>
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
                    <img src="{{ asset('images/features/feature-project-admin-ui.webp') }}" class="img-fluid rounded-4" loading="lazy">
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
                    <img src="{{ asset('images/features/feature-project-emp-ui.webp') }}" class="img-fluid rounded-4" loading="lazy">
                </div>

            </div>
        </div>
    </section>
@endsection