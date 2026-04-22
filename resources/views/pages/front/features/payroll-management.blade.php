@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-payroll-breadc.webp') }}") center/cover no-repeat;
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
                        Payroll Management <br class="d-none d-md-block"> Software
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Transform your outdated payroll system and automate the entire workflow from one unified platform.
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
                    <img src="{{ asset('images/features/feature-payroll.webp') }}" alt="Employee Management" class="img-fluid">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">Streamline Payroll Operations</span><br>
                        <span class="text-dark">with Renown CRM</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                       Simplify the entire payroll cycle and save valuable HR hours wasted on manual data entry and reconciliations. Renown’s online payroll system is easily scalable. It helps small businesses not only handle salaries, compliance, and employee records from one place, but it can also accommodate new payroll for future hires. It supports growing teams, creates structured processes, accelerates workflows, and provides better visibility across every payroll cycle.  
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralised employee and payroll data</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Structured salary and payment workflows</p>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Clear visibility into payroll operations</p>
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
                        <span class="text-primary">Renown Payroll Software -</span><br>
                        Built for Accuracy
                    </h2>

                    <p class="text-muted my-3">
                     Keep your payroll processes consistent, accurate, and compliant with Renown’s payroll management system. Automate workflows and reduce manual errors by upto 30%, delivering a positive payroll experience to your employees. 
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
                                <h5 class="mt-3">Salary Management</h5>
                                <p class="text-muted small">Manage your employee payroll workflow with our payroll management system. Leverage the platform to clearly define salary structure, allowances and bonuses, and eliminate calculation errors through automation.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Automated Payroll Processing</h5>
                                <p class="text-muted small">Manual data entry, compliance checks, and reconciliation are time-intensive HR workflows. On our payroll management software, you can eliminate manual work and reduce salary processing time through automation.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Deductions & Compliance</h5>
                                <p class="text-muted small">The software system automatically updates different laws (tax laws/TDS and labor laws/PF, ESI. This helps your HR team to generate compliance reports, handle deductions and taxes and reduce risks of penalties.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Payslip Generation</h5>
                                <p class="text-muted small">Payslips are essential documents, serving as a digital proof of employment. The HR department can use Renown CRM to automate, generate, and share detailed payslips with their employees and build trust on ther company.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Attendance Integration</h5>
                                <p class="text-muted small">The HR department can quickly sync employee attendance data directly into our payroll CRM. Data centralisation builds collaboration between teams, ensures accurate salary calculations and eliminates any manual adjustments.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Reports & Analytics</h5>
                                <p class="text-muted small">Respective department heads can easily access attendance and payroll reports and analytics because our payroll system has an integrated employee management feature. This helps managers track expenses, trends, and improve decision-making.</p>
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
            <h2 class="fw-bold mb-3">How Renown Payroll CRM Works</h2>
            <p class="mb-5">
                Renown’s simple payroll software connects employee management and teams, helping HR, finance, and payroll collaborate and accelerate the payroll process. Get started quickly in four simple steps.
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Set Salary Structure</h5>
                    <p class="small">Define employee salary components, allowances, and payment rules before processing payroll cycles.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Track Attendance & Data</h5>
                    <p class="small">Capture attendance and employee data to ensure accurate payroll inputs every month.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Process Payroll</h5>
                    <p class="small">Run payroll calculations automatically based on defined structures and real-time employee data.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Generate Payslips</h5>
                    <p class="small">Create and distribute payslips to employees instantly through the payroll management software.</p>
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
                    <img src="{{ asset('images/features/feature-payroll-admin-ui.webp') }}" class="img-fluid rounded-4">
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
                    <img src="{{ asset('images/features/feature-payroll-emp-ui.webp') }}" class="img-fluid rounded-4">
                </div>

            </div>
        </div>
    </section>
@endsection