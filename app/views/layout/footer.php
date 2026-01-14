<?php
// app/views/layout/footer.php
?>
</div> <!-- Close main content div -->
</div> <!-- Close row div -->
</div> <!-- Close container-fluid div -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
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
      $('.alert').alert('close');
    }, 5000);
  });
</script>
</body>

</html>
