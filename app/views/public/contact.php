<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-4">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
        <p class="lead text-light">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Contact Form -->
      <div class="col-lg-6">
        <h2 class="display-6 fw-bold mb-4 text-dark">Send us a Message</h2>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger mb-4">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="alert alert-success mb-4">
            Thank you for your message. We will get back to you soon.
          </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=contact" class="needs-validation" novalidate>
          <div class="mb-4">
            <label for="name" class="form-label fw-semibold">Full Name *</label>
            <input type="text" name="name" id="name"
                   value="<?php echo htmlspecialchars(($user_data['first_name'] ?? '') . ' ' . ($user_data['last_name'] ?? '')); ?>"
                   class="form-control form-control-lg"
                   required>
            <div class="invalid-feedback">
              Please enter your full name.
            </div>
          </div>

          <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email Address *</label>
            <input type="email" name="email" id="email"
                   value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>"
                   class="form-control form-control-lg"
                   required>
            <div class="invalid-feedback">
              Please enter a valid email address.
            </div>
          </div>

          <div class="mb-4">
            <label for="phone" class="form-label fw-semibold">Phone Number</label>
            <input type="tel" name="phone" id="phone"
                   value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>"
                   class="form-control form-control-lg">
          </div>

          <div class="mb-4">
            <label for="subject" class="form-label fw-semibold">Subject *</label>
            <input type="text" name="subject" id="subject"
                   value="<?php echo htmlspecialchars($_SESSION['old']['subject'] ?? ''); ?>"
                   class="form-control form-control-lg"
                   required>
            <div class="invalid-feedback">
              Please enter a subject.
            </div>
          </div>

          <div class="mb-4">
            <label for="message" class="form-label fw-semibold">Message *</label>
            <textarea name="message" id="message" rows="6"
                      class="form-control form-control-lg"
                      required><?php echo htmlspecialchars($_SESSION['old']['message'] ?? ''); ?></textarea>
            <div class="invalid-feedback">
              Please enter your message.
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold">
            <i class="fas fa-paper-plane me-2"></i> Send Message
          </button>
        </form>
      </div>

      <!-- Contact Information -->
      <div class="col-lg-6">
        <h2 class="display-6 fw-bold mb-4 text-dark">Get in Touch</h2>
        <div class="row g-4">
          <div class="col-12">
            <div class="d-flex">
              <div class="bg-primary bg-opacity-10 p-3 rounded me-4 flex-shrink-0">
                <i class="fas fa-map-marker-alt text-primary fs-4"></i>
              </div>
              <div>
                <h3 class="fw-semibold text-dark mb-2">Address</h3>
                <p class="text-muted mb-0">123 Luxury Street, City Center<br>12345, Country</p>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="d-flex">
              <div class="bg-primary bg-opacity-10 p-3 rounded me-4 flex-shrink-0">
                <i class="fas fa-phone text-primary fs-4"></i>
              </div>
              <div>
                <h3 class="fw-semibold text-dark mb-2">Phone</h3>
                <p class="text-muted mb-0">+1 (123) 456-7890<br>+1 (123) 456-7891</p>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="d-flex">
              <div class="bg-primary bg-opacity-10 p-3 rounded me-4 flex-shrink-0">
                <i class="fas fa-envelope text-primary fs-4"></i>
              </div>
              <div>
                <h3 class="fw-semibold text-dark mb-2">Email</h3>
                <p class="text-muted mb-0">info@luxuryhotel.com<br>reservations@luxuryhotel.com</p>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="d-flex">
              <div class="bg-primary bg-opacity-10 p-3 rounded me-4 flex-shrink-0">
                <i class="fas fa-clock text-primary fs-4"></i>
              </div>
              <div>
                <h3 class="fw-semibold text-dark mb-2">Business Hours</h3>
                <p class="text-muted mb-0">
                  Monday - Friday: 9:00 AM - 6:00 PM<br>
                  Saturday: 10:00 AM - 4:00 PM<br>
                  Sunday: Closed
                </p>
              </div>
            </div>
          </div>

          <!-- Social Media -->
          <div class="col-12 mt-3">
            <h3 class="fw-semibold text-dark mb-3">Follow Us</h3>
            <div class="d-flex gap-3">
              <a href="#" class="social-icon bg-primary bg-opacity-10 text-primary p-3 rounded hover-primary">
                <i class="fab fa-facebook-f fs-5"></i>
              </a>
              <a href="#" class="social-icon bg-primary bg-opacity-10 text-primary p-3 rounded hover-primary">
                <i class="fab fa-twitter fs-5"></i>
              </a>
              <a href="#" class="social-icon bg-primary bg-opacity-10 text-primary p-3 rounded hover-primary">
                <i class="fab fa-instagram fs-5"></i>
              </a>
              <a href="#" class="social-icon bg-primary bg-opacity-10 text-primary p-3 rounded hover-primary">
                <i class="fab fa-linkedin fs-5"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Map Section -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
          <div class="text-center text-white">
            <i class="fas fa-map-marked-alt display-1 mb-3"></i>
            <h3 class="mb-2">Interactive Map</h3>
            <p class="mb-0">Map integration can be added here</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
// Bootstrap form validation
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>

<style>
.hero-section {
  background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%);
}

.form-control-lg {
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  border: 1px solid #dee2e6;
}

.form-control-lg:focus {
  border-color: var(--bs-primary);
  box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
}

.social-icon {
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
}

.social-icon:hover {
  background-color: var(--bs-primary) !important;
  color: white !important;
  transform: translateY(-3px);
}

.hover-primary:hover {
  color: var(--bs-primary) !important;
}
</style>
