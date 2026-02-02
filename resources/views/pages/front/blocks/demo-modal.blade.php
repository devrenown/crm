<!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h1 class="modal-title fs-5 text-primary" id="staticBackdropLabel">Request For Demo</h1>
            <a class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
          </div>
          <div class="modal-body">
            {{-- <form action="{{ route('save.demo-request') }}" id="demo-form" onsubmit="return validationRecaptcha()" method="post"> --}}
            <form action="{{ route('save.demo-request') }}" id="demo-form" method="post">
              @csrf
              <div id="form-container">

                <div class="mb-3">
                  <label for="name">Full Name</label>
                  <input type="text" name="name" id="name" class="form-control" placeholder="Enter Full Name" required>
                </div>

                <div class="mb-3">
                  <label for="organization">Organization Name</label>
                  <input type="text" name="organization" id="organization" class="form-control" placeholder="Enter Organization Name" required>
                </div>

                <div class="mb-3">
                  <label for="company_size">Company Size</label>
                  <select name="company_size" id="company_size" class="form-select form-control" required>
                    <option value="" selected disabled>Select Company Size</option>
                    <option value="1-50">1 - 50</option>
                    <option value="50-100">50 - 100</option>
                    <option value="100-200">100 - 200</option>
                    <option value="200+">200+</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="email">Email</label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                </div>

                <div class="mb-3">
                  <label for="contact">Contact Number</label>
                  <input type="contact" name="contact" id="contact" class="form-control" placeholder="Enter Contact Number" required>
                </div>

                <div class="mb-3">
                  <label for="additional">Additional Information</label>
                  <textarea class="form-control" id="additional" name="additional" placeholder="Enter more about your organization"></textarea>
                </div>

                <div>
                  <!-- <div class="g-recaptcha" id="rcaptcha"  data-sitekey="6LdkPu0rAAAAAH1n75G8L_XC09TkFJheYCNg84bu"></div> -->
                  <div id="captcha-demo"></div>
                  <span class="text-danger" id="captcha-message"></span>
                </div>

                <div class="text-end">
                  <input type="submit" class="btn btn-primary text-end" id="sendRequest" title="Send Request"></input>
                </div>

              </div>

            </form>
          </div>
        </div>
      </div>
    </div>