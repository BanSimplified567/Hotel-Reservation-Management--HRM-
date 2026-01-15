<?php
// app/views/layout/footer.php
?>
</div> <!-- Close main content div -->

<!-- Footer -->
<footer class="mt-auto bg-gray-900 text-white py-12">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

      <!-- Company Info -->
      <div>
        <h5 class="text-xl font-bold mb-4 text-white">Hotel Management System</h5>
        <p class="text-gray-300 mb-4">
          Experience luxury and comfort at our premier hotel. Book your perfect stay today.
        </p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
            <i class="fab fa-facebook-f text-lg"></i>
          </a>
          <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
            <i class="fab fa-twitter text-lg"></i>
          </a>
          <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
            <i class="fab fa-instagram text-lg"></i>
          </a>
          <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
            <i class="fab fa-linkedin text-lg"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h5 class="text-xl font-bold mb-4 text-white">Quick Links</h5>
        <ul class="space-y-2">
          <li>
            <a href="index.php" class="text-gray-300 hover:text-white transition-colors duration-200">
              Home
            </a>
          </li>
          <li>
            <a href="index.php?action=rooms" class="text-gray-300 hover:text-white transition-colors duration-200">
              Rooms
            </a>
          </li>
          <li>
            <a href="index.php?action=about" class="text-gray-300 hover:text-white transition-colors duration-200">
              About Us
            </a>
          </li>
          <li>
            <a href="index.php?action=contact" class="text-gray-300 hover:text-white transition-colors duration-200">
              Contact
            </a>
          </li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div>
        <h5 class="text-xl font-bold mb-4 text-white">Contact Info</h5>
        <ul class="space-y-3">
          <li class="flex items-center">
            <i class="fas fa-phone mr-3 text-blue-400"></i>
            <a href="tel:09619839317" class="text-gray-300 hover:text-white transition-colors duration-200">
              0961 983 9317
            </a>
          </li>
          <li class="flex items-center">
            <i class="fas fa-envelope mr-3 text-blue-400"></i>
            <a href="mailto:bansimplified567@gmail.com" class="text-gray-300 hover:text-white transition-colors duration-200">
              bansimplified567@gmail.com
            </a>
          </li>
          <li class="flex items-center">
            <i class="fas fa-map-marker-alt mr-3 text-blue-400"></i>
            <span class="text-gray-300">123 Hotel Street, City, Country</span>
          </li>
        </ul>
      </div>

      <!-- Newsletter - FIXED VERSION -->
      <div>
        <h5 class="text-xl font-bold mb-4 text-white">Newsletter</h5>
        <p class="text-gray-300 mb-4">Subscribe to get special offers and updates.</p>
        <form class="flex flex-col sm:flex-row">
          <input type="email"
            class="flex-grow px-4 py-2 rounded-t-lg sm:rounded-l-lg sm:rounded-tr-none focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 w-full"
            placeholder="Your email"
            required>
          <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-b-lg sm:rounded-r-lg sm:rounded-bl-none transition-colors duration-200 font-medium mt-2 sm:mt-0 sm:w-auto w-full">
            Subscribe
          </button>
        </form>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-gray-700 my-8"></div>

    <!-- Copyright and Links -->
    <div class="flex flex-col md:flex-row justify-between items-center">
      <div class="mb-4 md:mb-0">
        <span class="text-gray-400">
          &copy; <?php echo date('Y'); ?> Hotel Management System. All rights reserved.
        </span>
      </div>
      <div class="flex space-x-6">
        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
          Privacy Policy
        </a>
        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
          Terms of Service
        </a>
        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
          Help Center
        </a>
      </div>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="../../public/assets/js/main.js"></script>

<!-- Toast Notifications -->
<?php if (isset($_SESSION['success'])): ?>
  <div class="fixed bottom-4 right-4 z-50">
    <div class="bg-green-600 text-white rounded-lg shadow-lg max-w-sm">
      <div class="flex items-center justify-between p-4">
        <div class="flex items-center">
          <i class="fas fa-check-circle mr-2"></i>
          <strong>Success</strong>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.remove()"
          class="text-white hover:text-gray-200 ml-4">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="px-4 pb-4">
        <?php echo $_SESSION['success'];
        unset($_SESSION['success']); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="fixed bottom-4 right-4 z-50">
    <div class="bg-red-600 text-white rounded-lg shadow-lg max-w-sm">
      <div class="flex items-center justify-between p-4">
        <div class="flex items-center">
          <i class="fas fa-exclamation-triangle mr-2"></i>
          <strong>Error</strong>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.remove()"
          class="text-white hover:text-gray-200 ml-4">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="px-4 pb-4">
        <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<script>
  $(document).ready(function() {
    // Initialize all DataTables
    $('.data-table').DataTable({
      "pageLength": 25,
      "language": {
        "search": "Search:",
        "lengthMenu": "Show _MENU_ entries",
        "zeroRecords": "No records found",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
        "infoEmpty": "No records available",
        "infoFiltered": "(filtered from _MAX_ total entries)"
      }
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
      $('.alert').remove();
    }, 5000);

    // Auto-dismiss toast notifications after 5 seconds
    setTimeout(function() {
      const toasts = document.querySelectorAll('.fixed.bottom-4.right-4 > div');
      toasts.forEach(toast => {
        toast.remove();
      });
    }, 5000);
  });
</script>

</body>

</html>
