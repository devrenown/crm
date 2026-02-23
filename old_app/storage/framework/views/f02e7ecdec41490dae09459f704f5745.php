    <!-- Contact Section -->
    <section class="px-3 bg-white py-5 px-md-4 px-lg-5" id="contact">
        <div class="container-fluid">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Contact Us</h2>
                <p class="text-muted">We’d love to hear from you</p>
            </div>
            <div class="row g-4 align-items-stretch">
                <!-- Location Info + Map -->

                

                <div class="col-lg-6 d-flex align-items-center" data-aos="fade-up" data-aos-delay="100">
                  <div>
                    <div>
                        <img src="<?php echo e(asset('images/front/contact-png.png')); ?>">
                    </div>

                    
                  </div>
                </div>


                <!-- Contact Form -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 px-3 pt-4 shadow-sm" id="contact-container">
                        <form action="<?php echo e(route('save.contact')); ?>" id="contact-form" method="post">
                            <?php echo csrf_field(); ?>
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
<?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/pages/front/blocks/contact.blade.php ENDPATH**/ ?>