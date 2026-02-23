    <!-- Contact Section -->
    <section class="px-3 bg-white py-5 px-md-4 px-lg-5" id="contact">
        <div class="container-fluid">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Contact Us</h2>
                <p class="text-muted">We’d love to hear from you</p>
            </div>
            <div class="row g-4 align-items-stretch">
                <!-- Location Info + Map -->

                {{-- <div class="col-lg-6" data-aos="fade-up" data-aos-delay="50">
                    <h5 class="fw-bold mb-3">Our Location</h5>

                    <div class="d-block d-lg-flex justify-content-between align-items-center">
                        <p><i class="bi bi-geo-alt-fill text-danger me-2"></i> E-20 1st Floor, Block E <br> Sector-3, Noida-UP 201301 </p>
                        <!-- <p><i class="bi bi-telephone-fill text-success me-2"></i> +91 9876543210</p> -->
                        <p><i class="bi bi-envelope-fill text-primary me-2"></i> support@renownsystems.com</p>
                    </div>
                    

                    <div style="border-radius:10px; overflow:hidden;">
                        <iframe src="https://maps.google.com/maps?q=28.576973,77.319373&z=15&output=embed" width="100%"
                            height="300" style="border:0;" allowfullscreen loading="lazy">
                        </iframe>
                    </div>
                </div> --}}

                <div class="col-lg-6 d-flex align-items-center" data-aos="fade-up" data-aos-delay="100">
                  <div>
                    <div>
                        <img src="{{ asset('images/front/contact-png.png') }}">
                    </div>

                    {{-- <h2 class="fw-bold mb-3">Get in Touch</h2>
                    <p class="text-muted mb-4">
                      Have questions or need assistance? Our team is here to help you explore how Renown CRM can simplify your 
                      business operations and boost productivity. Reach out to us anytime — we’d love to hear from you.
                    </p>

                    <ul class="list-unstyled">
                      <li class="d-flex align-items-center mb-3">
                        <i class="bi bi-envelope-fill text-primary fs-5 me-3"></i>
                        <span class="text-dark">support@renownsystem.com</span>
                      </li>
                      <li class="d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-primary fs-5 me-3"></i>
                        <span class="text-dark">
                          E-20 1st Floor, Block E Sector-3, Noida-UP 201301
                        </span>
                      </li>
                    </ul> --}}
                  </div>
                </div>


                <!-- Contact Form -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 px-3 pt-4 shadow-sm" id="contact-container">
                        <form action="{{ route('save.contact') }}" id="contact-form" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="fw-bold">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="fw-bold">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="fw-bold">Phone Number</label>
                                <input type="number" id="phone" name="phone" class="form-control" placeholder="Mobile Number" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="fw-bold">Message</label>
                                <textarea id="message" name="message" class="form-control" placeholder="Your Message" rows="4"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                              <!-- <div class="g-recaptcha" id="rcaptcha"  data-sitekey="6LdkPu0rAAAAAH1n75G8L_XC09TkFJheYCNg84bu"></div> -->
                              <div id="captcha-contact"></div>
                            </div>

                            <div id="error-message" class="text-danger"></div>
                            <button type="submit" id="sendMessage" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
