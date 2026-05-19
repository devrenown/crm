@extends(FRONT_LAYOUT_PATH)

<style>
	#breadcrump {
        min-height: 60vh;
        width: 100%;
        background: url("{{ asset('images/features/feature-client-breadc.webp') }}") center/cover no-repeat;
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
                        Client Management <br class="d-none d-md-block"> Software
                    </h1>

                    <p class="fs-6 fs-md-5 mb-3">
                        Turn leads into loyal, brand advocates with personalized, data-driven engagement - all from one powerful CRM software.
                    </p>

                    <!--<button data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-light text-primary fw-bold px-4 px-md-5 py-2 shadow-sm">-->
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
                    <img src="{{ asset('images/features/feature-client.webp') }}" alt="Employee Management" loading="lazy" class="img-fluid">
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-12 col-lg-6">

                    <!-- Heading -->
                    <h2 class="fw-bold mb-3">
                        <span class="text-primary">Build Stronger Relationships</span><br>
                        <span class="text-dark">with Automated Client Tracking</span>
                    </h2>  

                    <!-- Description -->
                    <p class="text-muted mb-4">
                        Renown’s client management software for businesses helps improve communication with clients by automating lead tracking, reminders, and follow-ups. This powerful software system streamlines workflows for your teams, frees up time from manual work, eliminates human errors and prevents missed opportunities for your business. Teams can easily manage multiple clients and leads while maintaining personalized engagement and building stronger relationships with them. This client management system improves efficiency across every stage of the client journey. 
                    </p>

                    <!-- Features -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Centralized database</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Automated communication tracking</p>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Personalized engagement</p>
                    </div>
                     <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-primary me-3 fs-5"></i>
                        <p class="mb-0 text-muted">Streamlined workflows</p>
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
                        <span class="text-primary">Boost Conversions with Powerful CRM</span><br>
                         Software Features
                    </h2> 

                    <p class="text-muted my-3">
                        Renown’s customer management software creates a data repository that allows teams to make informed decisions, improve forecasting, and enhance engagement with clients at every stage of the funnel.  Remove bottlenecks and improve conversions for your business.
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
                                <h5 class="mt-3">Client Database</h5>
                                <p class="text-muted small">Centralized client records help simplify data access and management for businesses.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-arrow-repeat fs-2 icon-box"></i>
                                <h5 class="mt-3">Contact Management</h5>
                                <p class="text-muted small">Our CRM software keeps your leads and their details organized. This helps to improve communication and engagement.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-star fs-2 icon-box"></i>
                                <h5 class="mt-3">Interaction Tracking</h5>
                                <p class="text-muted small">Track client interaction history to improve communication and better relationships.</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-people fs-2 icon-box"></i>
                                <h5 class="mt-3">Task & Follow-Up Management</h5>
                                <p class="text-muted small">Automate manual tasks and follow-ups to help teams with improved conversion efficiency.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-calendar fs-2 icon-box"></i>
                                <h5 class="mt-3">Document Management</h5>
                                <p class="text-muted small">Securely store and access client documents from a single dashboard to help streamline workflows for teams.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                                <i class="bi bi-file-earmark-text fs-2 icon-box"></i>
                                <h5 class="mt-3">Notes & Activity Logs</h5>
                                <p class="text-muted small">Our client tracking software for teams helps maintain detailed logs of every client interaction and their status.</p>
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
            <h2 class="fw-bold mb-3">How Our Customer Management Software Works</h2>
            <p class="mb-5">
                Our client management system offers intuitive navigation, helping your team to onboard with the platform in four simple steps. 
            </p>

            <div class="row text-start text-center text-md-start">

                <!-- Step -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">01</div>
                    <h5>Add Client Details</h5>
                    <p class="small">Easy to add client data on the software system to create a repository for easy access and data-driven engagement.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">02</div>
                    <h5>Track Interactions</h5>
                    <p class="small">Monitor every interaction with respective clients and improve future communications with personalized messaging.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">03</div>
                    <h5>Manage Tasks & Follow-Ups</h5>
                    <p class="small">Automate entry of client data and their status with our tracking software. Prevent any missed opportunity for your business.</p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="bg-white text-dark p-3 rounded-4 d-inline-block mb-3">04</div>
                    <h5>Analyze & Improve</h5>
                    <p class="small">Leverage data and analytics to manage leads and boost conversions with value-driven insights.</p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">

            <div class="d-flex" id="compare-banner">

                <!-- Admin UI -->
                <div class="mb-4 mb-lg-0">
                    
                    <img src="{{ asset('images/features/feature-client-ui.webp') }}" loading="lazy" class="img-fluid rounded-4">
                </div>

            </div>
        </div>
    </section>
@endsection