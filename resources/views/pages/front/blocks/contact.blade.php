    <!-- Contact Section -->
    <section class="px-3 bg-white py-5 px-md-4 px-lg-5" id="contact-section">
        <div class="container-fluid">
            <div class="text-center mb-5">
                <span class="section-title-badge">Get In Touch</span>
                <h2 class="mt-4"><span class="text-gradient fw-bold">Contact </span> Us</h2>
                <p class="text-muted">We’d love to hear from you</p>
            </div>
            <div class="row g-4 align-items-stretch">

                <div class="col-lg-6 d-flex align-items-center">
                  
                    <!-- <img src="{{ asset('images/front/contact-png.png') }}"> -->
                </div>


                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="card h-100 px-3 pt-4 shadow-sm bg-gradient-blue" id="contact-container">
                        <form action="{{ route('save.contact') }}" id="contact-form" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="fw-bold text-white">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Your Full Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="fw-bold text-white">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="you@company.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="fw-bold text-white">Phone Number</label>
                                <input type="number" id="phone" name="phone" class="form-control" placeholder="Enter Your Contact Number" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="fw-bold text-white">Message</label>
                                <textarea id="message" name="message" class="form-control" placeholder="Leave us a message..." rows="4"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                              <div id="captcha-contact"></div>
                            </div>

                            <div id="error-message" class="text-danger"></div>
                            <button type="submit" id="sendMessage" class="btn btn-white w-100 shadow py-2 fs-6 fw-bold text-blue">Send Message <i class="fa-solid fa-arrow-right"></i></button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
