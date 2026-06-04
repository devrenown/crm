<style>
    footer {
        margin: 0 !important;
        font-size: 1rem !important;
        text-align: left !important;
        color: inherit !important;
    }

    .footer-section-title {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 22px;
        letter-spacing: .5px;
    }

    .footer-link {
        color: #fff;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 10px;
        transition: 0.3s ease;
        font-size: 15px;
    }

    .footer-link:hover {
        color: #0d6efd;
        transform: translateX(2px);
    }

    .footer-contact p {
        margin-bottom: 14px;
        font-size: 15px;
    }

    .footer-social a {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        color: #0d6efd;
        text-decoration: none;
        transition: 0.3s ease;
    }

    .footer-social a:hover {
        transform: translateY(-3px);
        background: #0d6efd;
        color: #fff;
    }

    .footer-description {
        line-height: 1.9;
        max-width: 320px;
        font-size: 15px;
    }

    @media (max-width: 991px) {
        .footer-column {
            margin-bottom: 30px;
        }
    }
</style>

<!-- Footer Section -->
<footer class="bg-dark text-white px-3 py-5 px-md-4 px-lg-5">
    <div class="container-fluid">

        <!-- Main Footer Row -->
        <div class="row justify-content-between gy-4">

            <!-- Company Info -->
            <div class="col-lg-3 col-md-6 footer-column">
                <img src="{{ asset('images/front/logo-white.webp') }}"
                    alt="RenownCRM Logo"
                    style="height:50px;"
                    class="mb-4">

                <p class="footer-description">
                    Renown System CRM integrates powerful tools that are a game-changer for businesses seeking a unified dashboard to track conversions, streamline workflows, and manage clients.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-3 col-6 footer-column">
                <h6 class="footer-section-title">Quick Links</h6>

                <a href="{{ url('/#features') }}" class="footer-link">Features</a><br>
                <a href="{{ url('/#pricing') }}" class="footer-link">Pricing</a><br>
                <a href="{{ url('/#how-it-works') }}" class="footer-link">How it Works</a><br>
                <a href="{{ url('/#testimonials') }}" class="footer-link">Testimonials</a><br>
                <a href="{{ route('blogs') }}" class="footer-link">Blog</a>
            </div>

            <!-- Features -->
            <div class="col-lg-3 col-md-6 footer-column">
                <h6 class="footer-section-title">Features</h6>

                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('feature.detail', 'employee-management') }}" class="footer-link">
                            Employee Management
                        </a><br>

                        <a href="{{ route('feature.detail', 'leave-management') }}" class="footer-link">
                            Leave Management
                        </a><br>

                        <a href="{{ route('feature.detail', 'client-management') }}" class="footer-link">
                            Client Management
                        </a><br>

                        <a href="{{ route('feature.detail', 'task-management') }}" class="footer-link">
                            Task Management
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('feature.detail', 'invoice-management') }}" class="footer-link">
                            Invoice Management
                        </a><br>

                        <a href="{{ route('feature.detail', 'project-management') }}" class="footer-link">
                            Project Management
                        </a><br>

                        <a href="{{ route('feature.detail', 'payroll-management') }}" class="footer-link">
                            Payroll Management
                        </a><br>

                        <a href="{{ route('feature.detail', 'ticket-management') }}" class="footer-link">
                            Ticket Management
                        </a><br>
                        
                        <a href="{{ route('feature.detail', 'onboarding-management') }}" class="footer-link">
                            Onboarding
                        </a>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="col-lg-2 col-md-3 col-6 footer-column">
                <h6 class="footer-section-title">Support</h6>

                <a href="mailto:support@renownsystem.com" class="footer-link">
                    Help Center
                </a><br>

                <a href="{{ route('privacy-policy') }}" class="footer-link">
                    Privacy Policy
                </a><br>

                <a href="{{ route('terms-conditions') }}" class="footer-link">
                    Terms & Conditions
                </a><br>
                
                <a href="{{ url('/#contact-section') }}" class="footer-link">Contact</a> <br>

                <a href="{{ url('/#faq') }}" class="footer-link">
                    FAQ
                </a>
            </div>

            <!-- Contact -->
            <div class="col-lg-2 col-md-6 footer-column footer-contact px-lg-0">
                <h6 class="footer-section-title">Contact</h6>

                <div class="d-flex align-items-start gap-2 mb-3">
                    <i class="bi bi-geo-alt-fill mt-1"></i>

                    <p class="mb-0">
                        E-20 1st Floor, Block E <br>
                        Sector-3, Noida-UP 201301
                    </p>
                </div>

                <p class="mb-0">
                    <i class="bi bi-envelope-fill me-2"></i>
                    support@renownsystem.com
                </p>
            </div>

        </div>

        <hr class="border-secondary my-4">

        <!-- Bottom Footer -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">

            <p class="mb-0 text-center text-lg-start">
                &copy; 2025 RenownCRM. All Rights Reserved.
            </p>

            <!-- Social Media -->
            <div class="footer-social d-flex gap-2">

                <!-- Facebook -->
                <a href="https://www.facebook.com/RenownSystems"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>

                <!-- Instagram -->
                <a href="https://www.instagram.com/renownsystem/"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>

                <!-- LinkedIn -->
                <a href="https://www.linkedin.com/company/renown-system"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="LinkedIn">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>

                <!-- YouTube -->
                <a href="https://www.youtube.com/@RenownSystemCRM"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Youtube">
                    <i class="fa-brands fa-youtube"></i>
                </a>

                <!-- Twitter/X -->
                <a href="https://x.com/Renown_System"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Twitter">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>

            </div>

        </div>
    </div>
</footer>