
  <style>
    @media print {
      .no-print {
        display: none !important;
      }

      body {
        font-size: 12px;
      }

      .container {
        max-width: 100% !important;
      }
    }

    .invoice-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .invoice-header {
      border-bottom: 2px solid #666;
      padding-bottom: 20px;
      margin-bottom: 30px;
    }

    .invoice-title {
      color: #333;
      font-weight: 300;
    }

    .invoice-details {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 5px;
    }

    .table th {
      border-top: none;
      border-bottom: 2px solid #dee2e6;
    }

    .total-row {
      font-size: 1.2em;
      font-weight: bold;
    }

    .watermark {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      font-size: 80px;
      color: rgba(0, 0, 0, 0.1);
      z-index: -1;
      font-weight: bold;
      white-space: nowrap;
    }
  </style>

  <div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <!-- Print Controls -->
        <div class="no-print mb-4 text-end">
          <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="bi bi-printer"></i> Print Invoice
          </button>
          <a href="index.php?action=customer/reservations/view&id=<?php echo $reservation['id']; ?>"
            class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Reservation
          </a>
        </div>

        <!-- Watermark -->
        <div class="watermark">
          <?php echo $reservation['status'] === 'paid' ? 'PAID' : 'INVOICE'; ?>
        </div>

        <!-- Invoice Container -->
        <div class="invoice-container">
          <!-- Header -->
          <div class="invoice-header">
            <div class="row">
              <div class="col-md-6">
                <h1 class="invoice-title">INVOICE</h1>
                <h2 class="h4">Hotel Management System</h2>
                <p class="mb-1">
                  123 Hotel Street<br>
                  City, State 12345<br>
                  Phone: (123) 456-7890<br>
                  Email: info@hotelmanagement.com
                </p>
              </div>
              <div class="col-md-6 text-md-end">
                <h3 class="mb-0">Invoice #<?php echo str_pad($reservation['id'], 6, '0', STR_PAD_LEFT); ?></h3>
                <p class="mb-2">
                  <strong>Date:</strong> <?php echo date('F j, Y'); ?><br>
                  <strong>Due Date:</strong> <?php echo date('F j, Y', strtotime($reservation['check_in'])); ?><br>
                  <strong>Status:</strong>
                  <span class="badge bg-<?php echo $reservation['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                    <?php echo strtoupper($reservation['status']); ?>
                  </span>
                </p>
              </div>
            </div>
          </div>

          <!-- Billing Information -->
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="invoice-details">
                <h5 class="mb-3">Bill To:</h5>
                <p class="mb-1"><strong><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></strong></p>
                <p class="mb-1"><?php echo htmlspecialchars($reservation['email']); ?></p>
                <p class="mb-1"><?php echo htmlspecialchars($reservation['phone']); ?></p>
                <?php if ($reservation['address']): ?>
                  <p class="mb-0"><?php echo htmlspecialchars($reservation['address']); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="invoice-details">
                <h5 class="mb-3">Reservation Details:</h5>
                <p class="mb-1"><strong>Check-in:</strong> <?php echo date('F j, Y', strtotime($reservation['check_in'])); ?></p>
                <p class="mb-1"><strong>Check-out:</strong> <?php echo date('F j, Y', strtotime($reservation['check_out'])); ?></p>
                <p class="mb-1"><strong>Guests:</strong> <?php echo $reservation['guests']; ?></p>
                <p class="mb-1"><strong>Room:</strong> <?php echo htmlspecialchars($reservation['room_type'] . ' #' . $reservation['room_number']); ?></p>
                <?php if ($reservation['service_name']): ?>
                  <p class="mb-0"><strong>Service:</strong> <?php echo htmlspecialchars($reservation['service_name']); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Invoice Items -->
          <div class="table-responsive mb-4">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th width="50%">Description</th>
                  <th width="15%" class="text-center">Quantity</th>
                  <th width="15%" class="text-center">Unit Price</th>
                  <th width="20%" class="text-end">Amount</th>
                </tr>
              </thead>
              <tbody>
                <!-- Room Charges -->
                <tr>
                  <td>
                    <strong><?php echo htmlspecialchars($reservation['room_type']); ?></strong><br>
                    <small class="text-muted">
                      Room #<?php echo $reservation['room_number']; ?> •
                      <?php echo date('M j', strtotime($reservation['check_in'])) . ' - ' . date('M j, Y', strtotime($reservation['check_out'])); ?>
                    </small>
                  </td>
                  <td class="text-center"><?php echo $nights; ?> nights</td>
                  <td class="text-center">$<?php echo number_format($reservation['price_per_night'], 2); ?></td>
                  <td class="text-end">$<?php echo number_format($roomTotal, 2); ?></td>
                </tr>

                <!-- Service Charges -->
                <?php if ($serviceTotal > 0): ?>
               <!-- In the invoice table section, update variable names: -->
<tr>
  <td class="text-center"><?php echo $nights; ?> nights</td>
  <td class="text-center">$<?php echo number_format($reservation['price_per_night'], 2); ?></td>
  <td class="text-end">$<?php echo number_format($roomTotal, 2); ?></td> <!-- Changed from $room_total -->
</tr>

<!-- Later in the same file: -->
<?php if ($servicesTotal > 0): ?> <!-- Changed from $serviceTotal -->
  <tr>
    <td class="text-center">1</td>
    <td class="text-center">$<?php echo number_format($reservation['service_price'], 2); ?></td>
    <td class="text-end">$<?php echo number_format($servicesTotal, 2); ?></td> <!-- Changed from $serviceTotal -->
  </tr>
<?php endif; ?>

<!-- Subtotal row: -->
<tr>
  <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
  <td class="text-end">$<?php echo number_format($roomTotal + $servicesTotal, 2); ?></td>
</tr>

<!-- Tax row: -->
<tr>
  <td colspan="3" class="text-end"><strong>Tax (10%)</strong></td>
  <td class="text-end">$<?php echo number_format($taxAmount, 2); ?></td> <!-- Changed from $tax_amount -->
</tr>

<!-- Total row: -->
<tr class="total-row table-active">
  <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
  <td class="text-end"><strong>$<?php echo number_format($grandTotal, 2); ?></strong></td> <!-- Changed from $grand_total -->
</tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Payment Information -->
          <?php if ($reservation['payment_status'] === 'paid'): ?>
            <div class="alert alert-success mb-4">
              <h5><i class="bi bi-check-circle"></i> Payment Received</h5>
              <p class="mb-0">
                <strong>Payment Method:</strong> <?php echo ucfirst($reservation['payment_method']); ?> •
                <strong>Transaction ID:</strong> <?php echo $reservation['transaction_id']; ?> •
                <strong>Date:</strong> <?php echo date('F j, Y', strtotime($reservation['payment_date'])); ?>
              </p>
            </div>
          <?php endif; ?>

          <!-- Terms & Notes -->
          <div class="row">
            <div class="col-md-6">
              <div class="card border-0 bg-light">
                <div class="card-body">
                  <h6 class="card-title">Payment Terms</h6>
                  <p class="small mb-0">
                    • Balance due upon check-in<br>
                    • 20% deposit required to confirm booking<br>
                    • Cancellation fee may apply within 24 hours<br>
                    • No-show reservations charged in full
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-0 bg-light">
                <div class="card-body">
                  <h6 class="card-title">Notes</h6>
                  <p class="small mb-0">
                    • Check-in time: 3:00 PM<br>
                    • Check-out time: 11:00 AM<br>
                    • Late check-out may incur additional charges<br>
                    • Please present ID at check-in
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="text-center mt-5 pt-4 border-top">
            <p class="text-muted small mb-2">
              Thank you for choosing Hotel Management System
            </p>
            <p class="text-muted small">
              If you have any questions about this invoice, please contact:<br>
              Email: billing@hotelmanagement.com • Phone: (123) 456-7890
            </p>
            <p class="text-muted small">
              <strong>Invoice generated on:</strong> <?php echo date('F j, Y g:i A'); ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

