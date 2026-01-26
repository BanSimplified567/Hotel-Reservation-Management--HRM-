<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment - Hotel Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <style>
    .payment-header {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      padding: 3rem;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 2rem;
    }

    .payment-icon {
      font-size: 4rem;
      margin-bottom: 1rem;
    }

    .payment-card {
      border: 2px solid #28a745;
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
        <!-- Payment Header -->
        <div class="payment-header">
          <div class="payment-icon">
            <i class="bi bi-credit-card"></i>
          </div>
          <h1 class="h2 mb-3">Secure Payment</h1>
          <p class="lead mb-0">Complete your booking with a secure payment</p>
        </div>

        <!-- Alert -->
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <div class="row">
          <!-- Left Column: Payment Form -->
          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Payment Details</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="index.php?action=customer/booking/payment&id=<?= $reservation['id'] ?>">
                  <!-- Payment Method -->
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Payment Method</label>
                    <select class="form-select" name="payment_method" required>
                      <option value="">Select payment method</option>
                      <option value="credit_card" <?= (($_POST['payment_method'] ?? '') == 'credit_card') ? 'selected' : '' ?>>Credit Card</option>
                      <option value="debit_card" <?= (($_POST['payment_method'] ?? '') == 'debit_card') ? 'selected' : '' ?>>Debit Card</option>
                      <option value="paypal" <?= (($_POST['payment_method'] ?? '') == 'paypal') ? 'selected' : '' ?>>PayPal</option>
                    </select>
                  </div>

                  <!-- Card Details -->
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Card Number</label>
                    <input type="text"
                      class="form-control"
                      name="card_number"
                      placeholder="1234 5678 9012 3456"
                      pattern="[\d\s]{16,19}"
                      value="<?= htmlspecialchars($_POST['card_number'] ?? '') ?>"
                      required>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Expiry Date</label>
                      <input type="text"
                        class="form-control"
                        name="card_expiry"
                        placeholder="MM/YY"
                        pattern="\d{2}/\d{2}"
                        value="<?= htmlspecialchars($_POST['card_expiry'] ?? '') ?>"
                        required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">CVC</label>
                      <input type="text"
                        class="form-control"
                        name="card_cvc"
                        placeholder="123"
                        pattern="\d{3,4}"
                        value="<?= htmlspecialchars($_POST['card_cvc'] ?? '') ?>"
                        required>
                    </div>
                  </div>

                  <!-- Name on Card -->
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Name on Card</label>
                    <input type="text"
                      class="form-control"
                      name="card_name"
                      value="<?= htmlspecialchars($_POST['card_name'] ?? $_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?>"
                      required>
                  </div>

                  <!-- Security Info -->
                  <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-center">
                      <i class="bi bi-shield-check me-2"></i>
                      <div>
                        <strong>Secure Payment</strong>
                        <p class="mb-0">Your payment is protected with 256-bit SSL encryption</p>
                      </div>
                    </div>
                  </div>

                  <!-- Terms -->
                  <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="paymentTerms" name="paymentTerms" required>
                    <label class="form-check-label small" for="paymentTerms">
                      I authorize Hotel Management to charge my card for the deposit amount.
                      I understand this payment is non-refundable according to our cancellation policy.
                    </label>
                  </div>

                  <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg">
                      <i class="bi bi-lock me-2"></i> Pay $<?= number_format($deposit_amount, 2) ?>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Right Column: Summary -->
          <div class="col-lg-4">
            <div class="payment-card">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Booking Summary</h5>
              </div>
              <div class="card-body">
                <!-- Reservation Details -->
                <div class="mb-4">
                  <h6 class="mb-3">Reservation Details</h6>
                  <div class="mb-2">
                    <small class="text-muted d-block">Reservation #</small>
                    <strong><?= str_pad($reservation['id'], 6, '0', STR_PAD_LEFT) ?></strong>
                  </div>
                  <div class="mb-2">
                    <small class="text-muted d-block">Room</small>
                    <strong>Room #<?= $reservation['room_number'] ?> (<?= $reservation['room_type'] ?>)</strong>
                  </div>
                  <div class="mb-2">
                    <small class="text-muted d-block">Stay</small>
                    <strong><?= $nights ?> nights</strong>
                  </div>
                  <div class="mb-2">
                    <small class="text-muted d-block">Dates</small>
                    <strong><?= date('M j', strtotime($reservation['check_in'])) ?> - <?= date('M j, Y', strtotime($reservation['check_out'])) ?></strong>
                  </div>
                </div>

                <!-- Price Breakdown -->
                <div class="mb-4">
                  <h6 class="mb-3">Price Breakdown</h6>
                  <table class="table price-breakdown w-100">
                    <tr>
                      <td>Room (<?= $nights ?> nights)</td>
                      <td class="text-end">$<?= number_format($room_total, 2) ?></td>
                    </tr>
                    <tr>
                      <td>Tax (10%)</td>
                      <td class="text-end">$<?= number_format($tax_amount, 2) ?></td>
                    </tr>
                    <tr class="table-success">
                      <td><strong>Total Amount</strong></td>
                      <td class="text-end"><strong>$<?= number_format($total_amount, 2) ?></strong></td>
                    </tr>
                  </table>
                </div>

                <!-- Deposit Required -->
                <div class="text-center mb-4">
                  <h4 class="text-success">$<?= number_format($deposit_amount, 2) ?></h4>
                  <p class="text-muted">Deposit Due Now (20%)</p>
                  <div class="d-grid">
                    <span class="badge bg-success">Secure Payment</span>
                  </div>
                </div>

                <!-- Payment Methods -->
                <div class="mb-4">
                  <h6 class="mb-2">Accepted Methods</h6>
                  <div class="d-flex justify-content-center gap-2">
                    <i class="bi bi-credit-card-2-front fs-4 text-primary"></i>
                    <i class="bi bi-paypal fs-4 text-info"></i>
                    <i class="bi bi-bank fs-4 text-success"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-3">
              <a href="index.php?action=booking-confirmation" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Confirmation
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <?php include '../layout/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
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

    // Auto-dismiss alerts
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
</body>

</html>
