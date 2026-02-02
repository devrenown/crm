@extends(FRONT_LAYOUT_PATH)

@section('content')
<section class="py-5 bg-light mt-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4 p-md-5">
            <h1 class="text-center mb-4 fw-bold text-primary">Privacy & Policy</h1>
            <p class="text-muted text-center mb-5">Last updated: {{ date('F d, Y') }}</p>

            <h4 class="fw-semibold mt-4">1. Introduction</h4>
            <p>
              Welcome to <strong>RenownCRM</strong> (“we”, “us”, “our”). This Privacy & Policy explains how we collect, use, disclose, and safeguard your information when you visit our website
              <a href="https://crm.renownsystem.com" target="_blank" class="text-decoration-none text-primary">https://crm.renownsystem.com</a>
              and use our CRM services (“Service”).
            </p>

            <h4 class="fw-semibold mt-4">2. Information We Collect</h4>
            <p>We collect information in the following ways:</p>
            <ul>
              <li><strong>Information you provide:</strong> Such as your name, email address, company name, contact number, and account details.</li>
              <li><strong>Automatically collected data:</strong> Includes IP address, browser type, device identifiers, and usage statistics.</li>
              <li><strong>Third-party data:</strong> Information received from integrations or other connected services.</li>
            </ul>

            <h4 class="fw-semibold mt-4">3. How We Use Your Information</h4>
            <ul>
              <li>To provide and improve our CRM services.</li>
              <li>To respond to your inquiries and provide customer support.</li>
              <li>To send important notifications, updates, and administrative messages.</li>
              <li>To comply with legal obligations and prevent fraud or misuse.</li>
            </ul>

            <h4 class="fw-semibold mt-4">4. Data Sharing & Disclosure</h4>
            <p>
              We do not sell your personal data. However, we may share data with trusted service providers for hosting, analytics, and operational purposes, or when required by law.
            </p>

            <h4 class="fw-semibold mt-4">5. Data Retention</h4>
            <p>
              We retain your information as long as necessary to provide our Service, comply with legal requirements, and resolve disputes.
            </p>

            <h4 class="fw-semibold mt-4">6. Security</h4>
            <p>
              We use industry-standard measures to protect your data. However, no method of transmission over the Internet is 100% secure.
            </p>

            <h4 class="fw-semibold mt-4">7. Your Rights</h4>
            <p>
              You may have rights to access, update, or delete your personal data. To exercise these rights, contact us at
              <a href="mailto:support@renownsystems.com" class="text-decoration-none text-primary">support@renownsystems.com</a>.
            </p>

            <h4 class="fw-semibold mt-4">8. Cookies & Tracking</h4>
            <p>
              We use cookies to enhance your browsing experience, analyze usage, and improve our platform. You can manage cookies through your browser settings.
            </p>

            <h4 class="fw-semibold mt-4">9. Children’s Privacy</h4>
            <p>
              Our Service is not intended for individuals under the age of 16. We do not knowingly collect data from minors.
            </p>

            <h4 class="fw-semibold mt-4">10. Policy Updates</h4>
            <p>
              We may update this Privacy & Policy periodically. Any changes will be posted on this page with the updated date.
            </p>

            <h4 class="fw-semibold mt-4">11. Contact Us</h4>
            <p>
              If you have questions about this Privacy & Policy, please contact us at:
            </p>
            <ul class="list-unstyled">
              <li><strong>Email:</strong> <a href="mailto:support@renownsystems.com" class="text-decoration-none text-primary">support@renownsystems.com</a></li>
              <li><strong>Address:</strong> E-20 1st Floor, Block E <br> Sector-3, Noida-UP 201301 </li>
            </ul>

            <div class="text-center mt-3">
              <a href="{{ url('/') }}" class="btn btn-primary px-4 rounded-pill">Back to Home</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
