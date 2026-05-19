<!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" id="demo-request-form">
          <div class="">
            <div class="text-end p-3 pb-0">
              <a class="btn-close fs-4" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>

            <div class="text-center">
              <h2 class="modal-title" id="staticBackdropLabel"><span class="text-gradient fw-bold">Request For</span> Demo</h2>
              <p class="demo-paragraph mb-3">Book your personalized demo today and see how our CRM can <br> transform your workflow.</p>
            </div>

          </div>
          <div class="modal-body">
            {{-- <form action="{{ route('save.demo-request') }}" id="demo-form" onsubmit="return validationRecaptcha()" method="post"> --}}

            <div class="row">

              <div class="col-lg-6">
                <img src="{{ asset('images/demo-request-left.webp') }}" loading="lazy" alt="Employee management CRM" class="img">
              </div>

              <form action="{{ route('save.demo-request') }}" class="col-lg-6" id="demo-form" method="post">
                @csrf
                <div id="form-container">

                  <div class="row">
                    
                    <div class="mb-3 col-6">
                      <label for="name">Full Name</label>
                      <input type="text" name="r_name" id="r_name" class="form-control" placeholder="Enter Full Name" required>
                    </div>

                    <div class="mb-3 col-6">
                      <label for="email">Email</label>
                      <input type="email" name="r_email" id="r_email" class="form-control" placeholder="Enter Email" required>
                    </div>

                    <div class="mb-3 col-6">
                      <label for="organization">Organization Name</label>
                      <input type="text" name="r_organization" id="r_organization" class="form-control" placeholder="Enter Organization Name" required>
                    </div>

                    <div class="mb-3 col-6">
                      <label for="company_size">Company Size</label>
                      <select name="r_company_size" id="r_company_size" class="form-select form-control" required>
                        <option value="" selected disabled>Select Company Size</option>
                        <option value="1-50">1 - 50</option>
                        <option value="50-100">50 - 100</option>
                        <option value="100-200">100 - 200</option>
                        <option value="200+">200+</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="contact">Contact Number</label>
                      <input type="number" name="r_contact" id="r_contact" class="form-control" placeholder="Enter Contact Number" required>
                    </div>

                    <div class="mb-3">
                      <label for="additional">Additional Information</label>
                      <textarea class="form-control" id="r_additional" name="r_additional" placeholder="Enter more about your organization" rows="5"></textarea>
                    </div>

                    <div>
                      <!-- <div class="g-recaptcha" id="rcaptcha"  data-sitekey="6LdkPu0rAAAAAH1n75G8L_XC09TkFJheYCNg84bu"></div> -->
                      <div id="captcha-demo"></div>
                      <span class="text-danger" id="captcha-message"></span>
                    </div>

                    <div class="text-end">
                      <button type="submit" class="btn btn-primary text-end" id="sendRequest">Request Demo</button>
                    </div>

                  </div>

                </div>

              </form>
            </div>

          </div>
        </div>
      </div>
    </div>