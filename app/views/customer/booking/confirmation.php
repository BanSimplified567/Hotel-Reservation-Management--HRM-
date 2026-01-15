<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Confirmation - Hotel Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <style>
    .confirmation-header {
      background: linear-gradient(135deg, #198754 0%, #20c997 100%);
      color: white;
      padding: 3rem;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 2rem;
    }

    .confirmation-icon {
      font-size: 4rem;
      margin-bottom: 1rem;
    }

    .detail-card {
      border-left: 4px solid #198754;
      padding-left: 1rem;
    }

    .payment-card {
      border: 2px solid #198754;
      border-radius: 10px;
      overflow: hidden;
    }

    .price-breakdown td {
      padding: 8px 0;
      border-bottom: 1px solid #dee2e6;
    }

    .price-breakdown tr:last-child td {
      border-bottom: none;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <?php include '../layout/customer-header.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <?php include '../layout/customer-sidebar.php'; ?>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <!-- Confirmation Header -->
        <div class="confirmation-header">
          <div class="confirmation-icon">
            <i class="bi bi-check-circle"></i>
          </div>
          <h1 class="h2 mb-3">Booking Confirmation</h1>
          <p class="lead mb-0">Your reservation has been created successfully!</p>
          <p class="mb-0">Reservation #<?php echo str_pad($reservation['id'], 6, '0', STR_PAD_LEFT); ?></p>
        </div>

        <!-- Alert -->
        <div class="alert alert-warning mb-4">
          <div class="d-flex">
            <i class="bi bi-exclamation-triangle fs-4 me-3"></i>
            <div>
              <h5 class="alert-heading mb-2">Payment Required</h5>
              <p class="mb-2">
                Your reservation is <strong>pending</strong> and requires payment to be confirmed.
                Please complete payment within 24 hours to secure your booking.
              </p>
              <p class="mb-0">
                <strong>Deposit due:</strong> $<?php echo number_format($reservation['deposit_amount'], 2); ?>
              </p>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Left Column: Booking Details -->
          <div class="col-lg-8">
            <!-- Booking Summary -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Booking Summary</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-card mb-4">
                      <small class="text-muted d-block">Room Type</small>
                      <h5 class="mb-1"><?php echo htmlspecialchars($reservation['room_type']); ?></h5>
                      <p class="text-muted">Room #<?php echo $reservation['room_number']; ?></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-card mb-4">
                      <small class="text-muted d-block">Stay Duration</small>
                      <h5 class="mb-1"><?php echo $nights; ?> Nights</h5>
                      <p class="text-muted">
                        <?php echo date('M j', strtotime($reservation['check_in'])); ?> -
                        <?php echo date('M j, Y', strtotime($reservation['check_out'])); ?>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-card mb-4">
                      <small class="text-muted d-block">Check-in</small>
                      <h6 class="mb-1"><?php echo date('l, F j, Y', strtotime($reservation['check_in'])); ?></h6>
                      <p class="text-muted">From 3:00 PM</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-card mb-4">
                      <small class="text-muted d-block">Check-out</small>
                      <h6 class="mb-1"><?php echo date('l, F j, Y', strtotime($reservation['check_out'])); ?></h6>
                      <p class="text-muted">Until 11:00 AM</p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-card mb-4">
                      <small class="text-muted d-block">Guests</small>
                      <h6 class="mb-1"><?php echo $reservation['guests']; ?> Guests</h6>
                      <p class="text-muted">Maximum capacity for this room</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-card mb-4">
                      <small class="text-muted d-block">Booking Date</small>
                      <h6 class="mb-1"><?php echo date('F j, Y g:i A', strtotime($reservation['created_at'])); ?></h6>
                      <p class="text-muted">Reservation created</p>
                    </div>
                  </div>
                </div>

                <?php if ($reservation['service_name']): ?>
                  <div class="detail-card">
                    <small class="text-muted d-block">Additional Service</small>
                    <h6 class="mb-1"><?php echo htmlspecialchars($reservation['service_name']); ?></h6>
                    <p class="text-muted">$<?php echo number_format($reservation['service_price'], 2); ?></p>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Next Steps -->
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>What's Next?</h5>
              </div>
              <div class="card-body">
                <div class="list-group list-group-flush">
                  <div class="list-group-item d-flex align-items-center">
                    <div class="me-3">
                      <span class="badge bg-primary rounded-circle p-2">1</span>
                    </div>
                    <div>
                      <h6 class="mb-1">Complete Payment</h6>
                      <p class="mb-0 text-muted">Pay the 20% deposit to confirm your booking</p>
                    </div>
                  </div>
                  <div class="list-group-item d-flex align-items-center">
                    <div class="me-3">
                      <span class="badge bg-secondary rounded-circle p-2">2</span>
                    </div>
                    <div>
                      <h6 class="mb-1">Receive Confirmation</h6>
                      <p class="mb-0 text-muted">We'll email you a booking confirmation</p>
                    </div>
                  </div>
                  <div class="list-group-item d-flex align-items-center">
                    <div class="me-3">
                      <span class="badge bg-secondary rounded-circle p-2">3</span>
                    </div>
                    <div>
                      <h6 class="mb-1">Prepare for Your Stay</h6>
                      <p class="mb-0 text-muted">Pack your bags and get ready!</p>
                    </div>
                  </div>
                  <div class="list-group-item d-flex align-items-center">
                    <div class="me-3">
                      <span class="badge bg-secondary rounded-circle p-2">4</span>
                    </div>
                    <div>
                      <h6 class="mb-1">Check-in</h6>
                      <p class="mb-0 text-muted">Arrive at the hotel and present your ID</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Payment -->
          <div class="col-lg-4">
            <div class="payment-card">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Payment Details</h5>
              </div>
              <div class="card-body">
                <!-- Price Breakdown -->
                <div class="mb-4">
                  <h6 class="mb-3">Price Breakdown</h6>
                  <table class="table price-breakdown w-100">
                    <tr>
                      <td>Room (<?php echo $nights; ?> nights)</td>
                      <td class="text-end">$<?php echo number_format($roomTotal, 2); ?></td>
                    </tr>

                    <?php if ($serviceTotal > 0): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($reservation['service_name']); ?></td>
                        <td class="text-end">$<?php echo number_format($serviceTotal, 2); ?></td>
                      </tr>
                    <?php endif; ?>

                    <tr>
                      <td>Tax (10%)</td>
                      <td class="text-end">$<?php echo number_format(($roomTotal + $serviceTotal) * 0.10, 2); ?></td>
                    </tr>

                    <tr class="table-success">
                      <td><strong>Total Amount</strong></td>
                      <td class="text-end"><strong>$<?php echo number_format($roomTotal + $serviceTotal + ($roomTotal + $serviceTotal) * 0.10, 2); ?></strong></td>
                    </tr>
                  </table>
                </div>

                <!-- Deposit Required -->
                <div class="alert alert-info mb-4">
                  <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle fs-4 me-3"></i>
                    <div>
                      <h6 class="alert-heading mb-1">Deposit Required</h6>
                      <p class="mb-0">A 20% deposit is required to confirm your booking</p>
                    </div>
                  </div>
                </div>

                <!-- Deposit Amount -->
                <div class="text-center mb-4">
                  <h3 class="text-success">$<?php echo number_format($reservation['deposit_amount'], 2); ?></h3>
                  <p class="text-muted">Deposit Due Now</p>
                  <div class="d-grid">
                    <button class="btn btn-success btn-lg"
                      data-bs-toggle="modal"
                      data-bs-target="#paymentModal">
                      <i class="bi bi-lock me-2"></i> Secure Payment
                    </button>
                  </div>
                </div>

                <!-- Payment Methods -->
                <div class="mb-4">
                  <h6 class="mb-2">Accepted Payment Methods</h6>
                  <div class="d-flex justify-content-center gap-2">
                    <i class="bi bi-credit-card-2-front fs-4 text-primary"></i>
                    <i class="bi bi-paypal fs-4 text-info"></i>
                    <i class="bi bi-bank fs-4 text-success"></i>
                  </div>
                </div>

                <!-- Help Text -->
                <div class="text-center">
                  <p class="small text-muted mb-0">
                    <i class="bi bi-shield-check text-success"></i>
                    Your payment is secured with 256-bit SSL encryption
                  </p>
                </div>
              </div>
            </div>

            <!-- Need Help? -->
            <div class="card mt-4">
              <div class="card-body text-center">
                <h6><i class="bi bi-question-circle me-2"></i>Need Help?</h6>
                <p class="small text-muted mb-2">
                  Contact our customer support for assistance
                </p>
                <div class="d-grid gap-2">
                  <a href="mailto:support@hotelmanagement.com" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-envelope me-1"></i> Email Support
                  </a>
                  <a href="tel:+1234567890" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-telephone me-1"></i> Call Now
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="index.php?action=customer/booking/payment&id=<?php echo $reservation['id']; ?>">
          <div class="modal-header">
            <h5 class="modal-title">Secure Payment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="text-center mb-4">
              <h4 class="text-success">$<?php echo number_format($reservation['deposit_amount'], 2); ?></h4>
              <p class="text-muted">Deposit Amount</p>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
              <label class="form-label">Payment Method</label>
              <select class="form-select" name="payment_method" required>
                <option value="">Select payment method</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="paypal">PayPal</option>
              </select>
            </div>

            <!-- Card Details -->
            <div class="mb-3">
              <label class="form-label">Card Number</label>
              <input type="text"
                class="form-control"
                name="card_number"
                placeholder="1234 5678 9012 3456"
                pattern="[\d\s]{16,19}"
                required>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Expiry Date</label>
                <input type="text"
                  class="form-control"
                  name="card_expiry"
                  placeholder="MM/YY"
                  pattern="\d{2}/\d{2}"
                  required>
              </div>
              <div class="col-md-6">
                <label class="form-label">CVC</label>
                <input type="text"
                  class="form-control"
                  name="card_cvc"
                  placeholder="123"
                  pattern="\d{3,4}"
                  required>
              </div>
            </div>

            <!-- Name on Card -->
            <div class="mb-3">
              <label class="form-label">Name on Card</label>
              <input type="text"
                class="form-control"
                name="card_name"
                value="<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>"
                required>
            </div>

            <!-- Security Info -->
            <div class="alert alert-info small">
              <div class="d-flex align-items-center">
                <i class="bi bi-shield-check me-2"></i>
                <div>
                  <strong>Secure Payment</strong>
                  <p class="mb-0">Your payment is protected with 256-bit SSL encryption</p>
                </div>
              </div>
            </div>

            <!-- Terms -->
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="paymentTerms" required>
              <label class="form-check-label small" for="paymentTerms">
                I authorize Hotel Management to charge my card for the deposit amount.
                I understand this payment is non-refundable according to our cancellation policy.
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-lock me-1"></i> Pay $<?php echo number_format($reservation['deposit_amount'], 2); ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../layout/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto-dismiss alerts
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);

    // Format card number input
    document.querySelector('input[name="card_number"]')?.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
      let formatted = value.replace(/(\d{4})/g, '$1 ').trim();
      e.target.value = formatted.substring(0, 19);
    });

    // Format expiry date input
    document.querySelector('input[name="card_expiry"]')?.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
      if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
      }
      e.target.value = value.substring(0, 5);
    });

    // Format CVC input
    document.querySelector('input[name="card_cvc"]')?.addEventListener('input', function(e) {
      e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
    });
  </script>
</body>

</html>
