  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap JS Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

  <!-- Global Scripts -->
  <script>
    // Auto-hide alerts
    $(document).ready(function() {
      setTimeout(function() {
        $('.alert').alert('close');
      }, 5000);

      // Initialize DataTables for tables with datatable class
      $('.datatable').DataTable({
        "pageLength": 25,
        "responsive": true,
        "order": [],
        "language": {
          "search": "_INPUT_",
          "searchPlaceholder": "Search...",
          "lengthMenu": "Show _MENU_ entries",
          "info": "Showing _START_ to _END_ of _TOTAL_ entries",
          "paginate": {
            "first": "First",
            "last": "Last",
            "next": "Next",
            "previous": "Previous"
          }
        }
      });

      // Confirm delete actions
      $('.confirm-delete').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
          e.preventDefault();
        }
      });

      // Confirm status changes
      $('.confirm-action').on('click', function(e) {
        const message = $(this).data('confirm') || 'Are you sure you want to perform this action?';
        if (!confirm(message)) {
          e.preventDefault();
        }
      });

      // Form validation
      $('form.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        $(this).addClass('was-validated');
      });
    });
  </script>
</body>

</html>
