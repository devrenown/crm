@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/employee-management-breadcrump.webp') }}") center/cover no-repeat;
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
                        Employee Management  <br class="d-none d-md-block"> Software
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        A secure employee management CRM to handle daily HR work, from adding and maintaining employee records to tracking performance, and more - all from a single dashboard.
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
                    <img src="{{ asset('images/features/image 68.webp') }}" alt="Employee Management" class="img-fluid">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">Automation That Ends </span><br>
                        <span class="text-dark">Operational Bottlenecks</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        Operational bottlenecks directly impact visibility, performance, and resource alignment. Disconnected systems, manual logging, and raw employee data cause job delays, inconsistencies in work hours/deliverables, and resource allocation blind spots. Additionally, your existing system becomes insufficient when it comes to supporting large, distributed global teams. Employee management software is a powerful automation tool for small businesses. It manages the complete employee lifecycle with minimal human intervention. It simplifies HR operations and scales as your small team expands beyond your geography.
                    </p>
                        <p class="text-muted mb-4">
                            For better workforce management, this automation software provides:
                        </p>
                    
                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">A centralized employee data repository for easy access</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">HR process automation for time and operational efficiency</p>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">A seamless tracking system for payroll and performance</p>
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
                        <span class="text-primary">Organize Your Workforce with </span><br>
                        Powerful CRM Features
                    </h2>

                    <p class="text-muted my-3">
                        Manage your workforce and elevate their performance with our employee management CRM - designed to simplify operations and improve productivity.
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
                                <h5 class="mt-3">Attendance</h5>
                                <p class="text-muted small">For small business teams, accurate management of employee check-ins is crucial. With Renown employee management software for small business with attendance tracking, you can record employee attendance without any difficulty.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Shift</h5>
                                <p class="text-muted small">Our employee management system tools automate shift assignments and help HRs simplify workforce scheduling without any bottlenecks.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Designation</h5>
                                <p class="text-muted small">Automate the allocation of individual roles and responsibilities across your teams with our powerful CRM software. Manage and record employee designations effortlessly from a single platform.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Department</h5>
                                <p class="text-muted small">Group employees into departments for streamlined operations.
                                    Manage teams efficiently and improve coordination across different business units.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Holiday</h5>
                                <p class="text-muted small">Allow your workforce to plan their holidays efficiently by checking their attendance records and leave allocations. This ensures smooth operations for your business.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Work Report</h5>
                                <p class="text-muted small">Generate detailed work reports with staff management software, empowering smarter performance tracking for employee management system users.</p>
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
            <h2 class="fw-bold mb-3">How Renown Employee Management CRM Works</h2>
            <p class="mb-5">
                Our employment management software simplifies HR operations with intuitive navigation and smart automation. Manage your teams in four simple steps. 
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Add Employees</h5>
                    <p class="small">Easy to onboard employees to the employee management system for small teams, creating a single repository for recording their roles, departments, and other essential information.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Assign Roles & Tasks</h5>
                    <p class="small">Define specific roles, assign responsibilities, and monitor performance for clarity and smooth workflow across teams.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Track Activity</h5>
                    <p class="small">Monitor attendance,  leaves, performance metrics, and other day-to-day activities of your teams and stay updated on your team's progress.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Generate Reports</h5>
                    <p class="small">Generate and access performance reports using our CRM for employee management. Actionable workforce insights from such reports help with informed decisions for your company.</p>
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
                    <img src="{{ asset('images/features/feature-admin-ui.webp') }}" class="img-fluid rounded-4">
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
                    <img src="{{ asset('images/features/emp-dash.webp') }}" class="img-fluid rounded-4">
                </div>

            </div>
        </div>
    </section>
@endsection