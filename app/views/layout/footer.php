<?php
// app/views/layout/footer.php
?>
</div> <!-- Close main content div -->

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-4">
  <div class="container">
    <div class="row">
      <!-- Company Info -->
      <div class="col-md-6 col-lg-3 mb-4">
        <h5 class="h6 fw-bold mb-3">Hotel Management System</h5>
        <p class="small text-white-50 mb-3">
          Experience luxury and comfort at our premier hotel. Book your perfect stay today.
        </p>
        <div class="d-flex gap-3">
          <a href="#" class="text-white-50 hover:text-white">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="text-white-50 hover:text-white">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" class="text-white-50 hover:text-white">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="text-white-50 hover:text-white">
            <i class="fab fa-linkedin"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-md-6 col-lg-2 mb-4">
        <h5 class="h6 fw-bold mb-3">Quick Links</h5>
        <ul class="list-unstyled">
          <li class="mb-2">
            <a href="index.php" class="text-white-50 small text-decoration-none hover:text-white">
              Home
            </a>
          </li>
          <li class="mb-2">
            <a href="index.php?action=rooms" class="text-white-50 small text-decoration-none hover:text-white">
              Rooms
            </a>
          </li>
          <li class="mb-2">
            <a href="index.php?action=about" class="text-white-50 small text-decoration-none hover:text-white">
              About Us
            </a>
          </li>
          <li class="mb-2">
            <a href="index.php?action=contact" class="text-white-50 small text-decoration-none hover:text-white">
              Contact
            </a>
          </li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div class="col-md-6 col-lg-3 mb-4">
        <h5 class="h6 fw-bold mb-3">Contact Info</h5>
        <ul class="list-unstyled">
          <li class="mb-2 d-flex align-items-center">
            <i class="fas fa-phone me-2 text-primary small"></i>
            <a href="tel:09619839317" class="text-white-50 small text-decoration-none hover:text-white">
              0961 983 9317
            </a>
          </li>
          <li class="mb-2 d-flex align-items-center">
            <i class="fas fa-envelope me-2 text-primary small"></i>
            <a href="mailto:bansimplified567@gmail.com" class="text-white-50 small text-decoration-none hover:text-white">
              bansimplified567@gmail.com
            </a>
          </li>
          <li class="mb-2 d-flex align-items-center">
            <i class="fas fa-map-marker-alt me-2 text-primary small"></i>
            <span class="text-white-50 small">123 Hotel Street, City, Country</span>
          </li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="col-md-6 col-lg-4 mb-4">
        <h5 class="h6 fw-bold mb-3">Newsletter</h5>
        <p class="text-white-50 small mb-3">Subscribe to get special offers and updates.</p>
        <form class="input-group">
          <input type="email"
            class="form-control form-control-sm"
            placeholder="Your email"
            required>
          <button type="submit"
            class="btn btn-primary btn-sm">
            Subscribe
          </button>
        </form>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-top border-secondary my-3"></div>

    <!-- Copyright and Links -->
    <div class="row">
      <div class="col-md-6 mb-2 mb-md-0">
        <span class="text-white-50 small">
          &copy; <?php echo date('Y'); ?> Hotel Management System. All rights reserved.
        </span>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="#" class="text-white-50 small text-decoration-none me-3 hover:text-white">
          Privacy Policy
        </a>
        <a href="#" class="text-white-50 small text-decoration-none me-3 hover:text-white">
          Terms of Service
        </a>
        <a href="#" class="text-white-50 small text-decoration-none hover:text-white">
          Help Center
        </a>
      </div>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="../../public/assets/js/main.js"></script>

<!-- Toast Notifications -->
<?php if (isset($_SESSION['success'])): ?>
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header bg-success text-white">
        <i class="fas fa-check-circle me-2"></i>
        <strong class="me-auto">Success</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?php echo $_SESSION['success'];
        unset($_SESSION['success']); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header bg-danger text-white">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong class="me-auto">Error</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<script>
  $(document).ready(function() {
    // Initialize DataTables
    $('.data-table').DataTable({
      "pageLength": 10,
      "language": {
        "search": "Search:",
        "lengthMenu": "Show _MENU_ entries",
        "zeroRecords": "No records found",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
        "infoEmpty": "No records available",
        "infoFiltered": "(filtered from _MAX_ total entries)"
      },
      "responsive": true
    });

    // Initialize Bootstrap toasts
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
      return new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: 5000
      })
    });

    // Auto-hide toasts after 5 seconds
    toastList.forEach(function(toast) {
      toast.show();
    });
  });
</script>

