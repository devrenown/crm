@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-leave-breadc.webp') }}") center/cover no-repeat;
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
                        Leave Management <br class="d-none d-md-block"> Software
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Automate leave requests, approvals, and records with the Renown leave management system. Keep your teams aligned and prevent staffing gaps.
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
                    <img src="{{ asset('images/features/feature-leave.webp') }}" alt="Employee Management" class="img-fluid" loading="lazy">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">Manage Leave Request without </span><br>
                        <span class="text-dark">Hurting Operations</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        A structured leave tracking through the Renown management system eliminates critical HR issues. It gives more clarity to your businesses when handling employee time-off. Manage multiple leave applications, approvals, and records from one platform and avoid constant follow-ups. You can track availability, leave types, schedules and approvals without disrupting operations or hurting your employees. 
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralised leave tracking</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Real-time visibility</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Policy-based workflows</p>
                    </div>
                     <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Accurate leave records</p>
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
                        <span class="text-primary">Improve Workforce Planning with</span><br>
                        Powerful Features
                    </h2>

                    <p class="text-muted my-3">
                      Powerful leave management features provide real-time visibility of your teams and their availability. Anticipate leaves, ensure adequate staffing levels, and improve project management despite absences.
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
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Leave Application</h5>
                                <p class="text-muted small">Employees can apply for leave using structured forms available on the system based on company policies and balances. </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Approval Workflow</h5>
                                <p class="text-muted small">Define approval workflows to route leave requests to foster quick approvals and nurture accountability in your teams.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Leave Balance Tracking</h5>
                                <p class="text-muted small">With real-time updates, employees can easily track their leave balances before planning their holidays. 
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Leave Types Management</h5>
                                <p class="text-muted small">Our leave management system clearly defines leave types and company policies. This protects businesses from any legal issues.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Holiday Integration</h5>
                                <p class="text-muted small">Our leave management system is integrated with holiday calendars. This helps employees to align leave planning with company schedules and team availability.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-clock fs-2 icon-box"></i>
                                <h5 class="mt-3">Leave History</h5>
                                <p class="text-muted small">Maintain detailed leave history records automatically to improve tracking, reporting, and future planning needs - all from a single platform.</p>
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
            <h2 class="fw-bold mb-3">Manage Leaves in Four Simple Steps</h2>
            <p class="mb-5">
                Create a simple and streamlined leave management process with automation. Improve compliance, ensure consistent leave policy enforcement, and manage leave requests without hurting operations or employee morale.
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Apply for Leave</h5>
                    <p class="small">Employees submit leave requests based on policy, type, and available balance within the system.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Review & Approve</h5>
                    <p class="small">Managers review leave requests and approve them based on availability and organisational policies.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Track Leave Status</h5>
                    <p class="small">Track leave requests, approvals, and balances with full visibility across teams and departments.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Maintain Records</h5>
                    <p class="small">Maintain accurate leave records and history for reporting, compliance, and future workforce planning.</p>
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
                    <img src="{{ asset('images/features/feature-leave-admin-ui.png') }}" class="img-fluid rounded-4" loading="lazy">
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
                    <img src="{{ asset('images/features/feature-leave-emp-ui.webp') }}" class="img-fluid rounded-4" loading="lazy">
                </div>

            </div>
        </div>
    </section>
@endsection