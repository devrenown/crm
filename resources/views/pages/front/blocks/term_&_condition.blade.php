@extends(FRONT_LAYOUT_PATH)

@section('content')
<section class="py-5 bg-light mt-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4 p-md-5">
            <h1 class="text-center mb-4 fw-bold text-primary">Terms & Conditions</h1>
            <p class="text-muted text-center mb-5">Last updated: {{ date('F d, Y') }}</p>

            <h4 class="fw-semibold mt-4">1. Introduction</h4>
            <p>
              Welcome to <strong>RenownCRM</strong> (“we”, “us”, “our”). These Terms & Conditions (“Terms”) govern your access to and use of our website 
              <a href="https://crm.renownsystem.com" target="_blank" class="text-decoration-none text-primary">https://crm.renownsystem.com</a>
              and all related services (“Service”).
            </p>

            <h4 class="fw-semibold mt-4">2. Acceptance of Terms</h4>
            <p>
              By accessing or using our Service, you agree to be bound by these Terms. If you do not agree, you must discontinue use immediately.
            </p>

            <h4 class="fw-semibold mt-4">3. Use of the Service</h4>
            <ul>
              <li>You must use the Service only for lawful purposes and in accordance with these Terms.</li>
              <li>You agree not to misuse, copy, or reverse-engineer any part of our platform.</li>
              <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
              <li>All information you provide must be accurate, complete, and current.</li>
            </ul>

            <h4 class="fw-semibold mt-4">4. Account Responsibilities</h4>
            <p>
              When you register with RenownCRM, you are responsible for all activity that occurs under your account. We are not liable for losses resulting from unauthorized use of your credentials.
            </p>

            <h4 class="fw-semibold mt-4">5. Subscription & Payments</h4>
            <p>
              Certain parts of the Service may require payment. All billing information you provide must be valid and accurate. Subscriptions, once activated, are non-transferable and subject to our refund and cancellation policies.
            </p>

            <h4 class="fw-semibold mt-4">6. Intellectual Property</h4>
            <p>
              All content, trademarks, and code within RenownCRM are owned or licensed by us. You may not copy, reproduce, or distribute any part of the Service without prior written consent.
            </p>

            <h4 class="fw-semibold mt-4">7. Prohibited Activities</h4>
            <ul>
              <li>Attempting to interfere with the functionality or security of the Service.</li>
              <li>Using the Service for spamming, phishing, or distributing malware.</li>
              <li>Collecting or harvesting data from other users without permission.</li>
            </ul>

            <h4 class="fw-semibold mt-4">8. Termination</h4>
            <p>
              We reserve the right to suspend or terminate your access to the Service if you violate these Terms or engage in misuse of our platform. Upon termination, your right to use the Service will cease immediately.
            </p>

            <h4 class="fw-semibold mt-4">9. Limitation of Liability</h4>
            <p>
              RenownCRM and its affiliates shall not be liable for any indirect, incidental, or consequential damages arising out of your use or inability to use the Service.
            </p>

            <h4 class="fw-semibold mt-4">10. Disclaimer</h4>
            <p>
              The Service is provided “as is” and “as available” without warranties of any kind, whether express or implied. We do not guarantee uninterrupted or error-free operation.
            </p>

            <h4 class="fw-semibold mt-4">11. Modifications to Terms</h4>
            <p>
              We may update these Terms from time to time. Any changes will be posted on this page with the updated date. Continued use of the Service after such updates constitutes acceptance of the new Terms.
            </p>

            <h4 class="fw-semibold mt-4">12. Governing Law</h4>
            <p>
              These Terms shall be governed by and construed in accordance with the laws of India. Any disputes will be subject to the jurisdiction of courts in Noida, Uttar Pradesh.
            </p>

            <h4 class="fw-semibold mt-4">13. Contact Information</h4>
            <p>
              For any questions about these Terms & Conditions, please contact us:
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
