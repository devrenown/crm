    <style>
        footer {
          margin: 0 !important;
          font-size: 1rem !important;
          text-align: left !important;
          color: inherit !important;
        }
    </style>

    <!-- Footer Section -->
    <footer class="bg-dark text-white px-3 py-5 px-md-4 px-lg-5">
        <div class="container-fluid text-md-left">
            <div class="row">

                <!-- Company Info -->
                <div class="col-md-4 col-lg-4 col-xl-3 mx-auto mt-3">
                    <img src="{{ asset('images/front/logo-white.png') }}" alt="RenownCRM Logo" style="height:50px;"
                        class="mb-3">
                    <p>
                        RenownCRM delivers powerful tools to grow your business with smarter lead, customer, and sales management.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-2 col-6 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h6 class="text-uppercase fw-bold mb-4">Quick Links</h6>
                    <p><a href="{{ url('/#features') }}" class="text-white text-decoration-none">Features</a></p>
                    <!-- <p><a href="#pricing" class="text-white text-decoration-none">Pricing</a></p> -->
                    <p><a href="{{ url('/#how-it-works') }}" class="text-white text-decoration-none">How it Works</a></p>
                    <p><a href="{{ url('/#testimonials') }}" class="text-white text-decoration-none">Testimonials</a></p>
                    <p><a href="{{ url('/#contact') }}" class="text-white text-decoration-none">Contact</a></p>
                </div>

                <!-- Support -->
                <div class="col-md-3 col-6 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h6 class="text-uppercase fw-bold mb-4">Support</h6>
                    <p><a href="mailto:support@renownsystems.com" class="text-white text-decoration-none">Help Center</a></p>
                    <p><a href="{{ route('privacy-policy') }}" class="text-white text-decoration-none">Privacy Policy</a></p>
                    <p><a href="{{ route('terms-conditions') }}" class="text-white text-decoration-none">Terms & Conditions</a></p>
                    <p><a href="{{ url('/#faq') }}" class="text-white text-decoration-none">FAQ</a></p>
                </div>

                <!-- Contact -->
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                    <p><i class="bi bi-geo-alt-fill me-2"></i> E-20 1st Floor, Block E <br> Sector-3, Noida-UP 201301 </p>
                    <p><i class="bi bi-envelope-fill me-2"></i> support@renownsystems.com</p>
                    <!-- <p><i class="bi bi-telephone-fill me-2"></i> +91 9876543210</p> -->

                    <!-- Social Media -->
                    <div class="mt-3">
                        <a href="https://www.facebook.com/RenownSystems" target="_blank" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="https://x.com/Renown_System" target="_blank" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="https://www.linkedin.com/company/renown-system" target="_blank" class="text-white me-3"><i class="bi bi-linkedin"></i></a>
                        <a href="https://www.instagram.com/renownsystem/" target="_blank" class="text-white"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <hr class="mt-4">

            <!-- Copyright -->
            <div class="text-center">
                <p class="mb-0">&copy; 2025 RenownCRM. All Rights Reserved.</p>
            </div>
        </div>
    </footer>