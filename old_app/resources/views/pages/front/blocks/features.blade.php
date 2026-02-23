

@push('styles')

 <style>
    .feature-card {
        width: 250px;
    }

    @media (max-width: 600px) {
        .feature-card {
            width: 100%;
        }
    }
 </style>

@endpush

<!-- Features Section -->
    <section class="px-3 py-5 px-md-4 px-lg-5" id="features">
        <div class="container-fluid">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Key Features</h2>
                <p class="text-muted">Everything you need to manage your business efficiently</p>
            </div>
            <div class="row gap-1 gap-lg-3 justify-content-between row-cols-lg-4 row-cols-md-3 row-cols-1 flex-shrink-0">

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-people-fill fs-1 text-white border px-3 py-2 bg-blue-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Employee Management</h5>
                    <p>Manage your team with ease—track roles, performance, and employee details from one dashboard.</p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-card-checklist fs-1 text-white border px-3 py-2 bg-green-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Task Management</h5>
                    <p>Organize, assign, and track tasks effortlessly to boost team productivity.</p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-clipboard-check-fill fs-1 text-white border px-3 py-2 bg-yellow-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Project Management</h5>
                    <p>Plan, track, and manage projects efficiently with seamless team collaboration.</p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-calendar3 fs-1 text-white border px-3 py-2 bg-red-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Leave Management</h5>
                    <p>Simplify leave requests, approvals, and tracking with an organized system for your team.</p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-person-workspace fs-1 text-white border px-3 py-2 bg-brown-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Onboarding Management</h5>
                    <p>Simplify new employee onboarding with organized workflows, task tracking, and smooth
                        coordination.</p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-cash-stack fs-1 text-white border px-3 py-2 bg-pink-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Payroll Management</h5>
                    <p>Manage salaries, deductions, and payments efficiently with an accurate and streamlined payroll
                        system.</p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-person-lines-fill fs-1 text-white border px-3 py-2 bg-violate-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Client Management</h5>
                    <p>Keep track of client information, interactions, and relationships to enhance satisfaction and
                        retention.</p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-calculator fs-1 text-white border px-3 py-2 bg-skyblue-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Accounting Management</h5>
                    <p>Keep your finances organized—manage accounts, track expenses, and generate reports efficiently.
                    </p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-bar-chart-fill fs-1 text-white border px-3 py-2 bg-green-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Sales Management</h5>
                    <p>Track leads, monitor sales performance, and streamline your sales pipeline to close deals faster.
                    </p>
                </div>

                <div class="col p-3 card shadow-sm feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon mb-2 mb-lg-3">
                        <i class="bi bi-chat-dots-fill fs-1 text-white border px-3 py-2 bg-blue-gradient"></i>
                    </div>
                    <h5 class="fw-bold">Chat</h5>
                    <p>Communicate instantly with your team and clients through a secure, easy-to-use chat system.</p>
                </div>

            </div>
        </div>
    </section>