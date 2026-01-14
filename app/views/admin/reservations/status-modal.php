<!-- This is a reusable modal for status updates that can be included in other views -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Update Reservation Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="" id="statusForm">
        <div class="modal-body">
          <input type="hidden" name="reservation_id" id="reservationId">

          <div class="form-group">
            <label for="status">Status *</label>
            <select class="form-control" id="status" name="status" required>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="checked_in">Checked-in</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <div class="form-group">
            <label for="notes">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"
              placeholder="Add any notes about this status change..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // This function can be called from any view to show the status modal
  function showStatusModal(reservationId, currentStatus, actionUrl) {
    document.getElementById('reservationId').value = reservationId;
    document.getElementById('statusForm').action = actionUrl;
    document.getElementById('status').value = currentStatus;
    document.getElementById('notes').value = '';

    $('#statusModal').modal('show');
  }
</script>
