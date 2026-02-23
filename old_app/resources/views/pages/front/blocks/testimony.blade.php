    <!-- Testimonials Section -->
    {{-- <section class="px-3 py-5 px-md-4 px-lg-5" id="testimonials">
        <div class="container-fluid">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">What Our Clients Say</h2>
                <p class="text-muted">Trusted by businesses worldwide</p>
            </div>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="testimonial-card mx-auto col-md-8" data-aos="fade-up">
                            <p>"MyCRM has transformed the way we manage our clients. The interface is intuitive and the
                                support is excellent!"</p>
                            <h6 class="fw-bold mt-3">Sarah Johnson</h6>
                            <small class="text-muted">CEO, Bright Solutions</small>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="testimonial-card mx-auto col-md-8">
                            <p>"Thanks to MyCRM, our sales team is closing more deals than ever before. Highly
                                recommend!"</p>
                            <h6 class="fw-bold mt-3">Michael Lee</h6>
                            <small class="text-muted">Sales Manager, GlobalTech</small>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="testimonial-card mx-auto col-md-8">
                            <p>"The analytics feature gives us deep insights into our performance. A game-changer for
                                our company."</p>
                            <h6 class="fw-bold mt-3">Emily Davis</h6>
                            <small class="text-muted">Marketing Director, VisionCorp</small>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section> --}}

    <section class="px-3 py-5 px-md-4 px-lg-5 bg-light" id="testimonials">
      <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">What Our Clients Say</h2>
        <p class="text-muted">Empowering teams to achieve more with RenownCRM</p>
      </div>

      <div class="slider-container" data-aos="fade-up">
        <!-- Testimonial 1 -->
        <div class="card p-3" style="max-height: 300px;">
          <p>"RenownCRM has completely streamlined our customer management process. We can now track leads and
            close deals faster than ever. It’s the perfect mix of simplicity and power."</p>
          <h6 class="fw-bold mt-3">Amit Sharma</h6>
          <small class="text-muted">Operations Head</small>
          <div class="text-warning fs-6 mt-2">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="card p-3" style="max-height: 300px;">
          <p>"The automation tools in RenownCRM have saved our sales team countless hours. From lead capture
            to follow-up reminders, everything works seamlessly."</p>
          <h6 class="fw-bold mt-3">Priya Mehta</h6>
          <small class="text-muted">Sales Director</small>
          <div class="text-warning fs-6 mt-2">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="card p-3" style="max-height: 300px;">
          <p>"We love how customizable RenownCRM is. It adapts to our workflow perfectly and helps our team
            collaborate more effectively across departments."</p>
          <h6 class="fw-bold mt-3">James Carter</h6>
          <small class="text-muted">Project Manager</small>
          <div class="text-warning fs-6 mt-2">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
          </div>
        </div>

        <!-- Testimonial 4 -->
        <div class="card p-3" style="max-height: 300px;">
          <p>"The analytics and reporting dashboard give us deep visibility into our sales performance.
            RenownCRM has truly become the backbone of our business decisions."</p>
          <h6 class="fw-bold mt-3">Sneha Patel</h6>
          <small class="text-muted">Marketing Head</small>
          <div class="text-warning fs-6 mt-2">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
          </div>
        </div>

        <!-- Testimonial 5 -->
        <div class="card p-3" style="max-height: 300px;">
          <p>"Exceptional support team! They helped us migrate all our data quickly and provided training that
            made onboarding effortless."</p>
          <h6 class="fw-bold mt-3">Rohit Verma</h6>
          <small class="text-muted">Founder</small>
          <div class="text-warning fs-6 mt-2">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
          </div>
        </div>
      </div>
    </section>


@push('scripts')
  <script>
    $('.slider-container').loopslider({
        visibleItems: 4 // Amount for slides showing in window at once
        ,step: 1 // Amount of slides scrolling each time
        ,gap: 20 // Margin between each slide (in px)
        ,slideDuration: 400 // Slide transition duration (in ms)
        ,easing: 'swing' // "swing" or "linear", more easing jqueryui.com/easing/
        ,autoplay: true // Auto play slides
        ,autoplayInterval: 2000 // Delay between slides
        ,stopOnHover: false // Stop slideshow on mouse over
        ,touchSupport: true // Handling swipe gestures
        ,responsive: {
            480: {visibleItems: 1,step: 1}
            ,760: {visibleItems: 3,step: 3}
            ,1000: {visibleItems: 4,step: 3}
        }
        ,fullscreen: false  //If true sets the height of the slides equal of a viewport height
        ,parallax: null
        
        // Controls
        ,pagination: false
        ,navigation: false // prev and next buttons
        ,prevButton: '#prev' // CSS selector for element used to populate the "Prev" control
        ,nextButton: '#next' // CSS selector for element used to populate the "Next" control
        ,stopButton: '#stop' // CSS selector for element used to populate the "Stop" control
        ,playButton: '#play' // CSS selector for element used to populate the "Play" control
        
        // Callbacks
        ,onStop: function(){ /* your code here */ }
        ,onPlay: function(){ /* your code here */ }
        ,onMove: function(index, $element, direction){ /* your code here */ }
    });
  </script>
@endpush