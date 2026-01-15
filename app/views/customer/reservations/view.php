<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservation Details - Hotel Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <style>
    .detail-card {
      border-radius: 10px;
      overflow: hidden;
    }

    .timeline {
      position: relative;
      padding-left: 30px;
    }

    .timeline:before {
      content: '';
      position: absolute;
      left: 10px;
      top: 0;
      bottom: 0;
      width: 2px;
      background: #e9ecef;
    }

    .timeline-item {
      position: relative;
      margin-bottom: 20px;
    }

    .timeline-item:before {
      content: '';
      position: absolute;
      left: -25px;
      top: 5px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #6c757d;
    }

    .timeline-item.completed:before {
      background: #198754;
    }

    .timeline-item.current:before {
      background: #0d6efd;
      box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
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
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="index.php?action=customer/reservations">Reservations</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  #<?php echo str_pad($reservation['id'], 6, '0', STR_PAD_LEFT); ?>
                </li>
              </ol>
            </nav>
            <h1 class="h2 mb-0">Reservation Details</h1>
          </div>
          <div class="btn-group">
            <a href="index.php?action=customer/reservations/invoice&id=<?php echo $reservation['id']; ?>"
              class="btn btn-outline-primary" target="_blank">
              <i class="bi bi-receipt me-1"></i> Invoice
            </a>
            <?php if ($reservation['status'] === 'pending'): ?>
              <a href="index.php?action=customer/booking/confirmation&id=<?php echo $reservation['id']; ?>"
                class="btn btn-warning">
                <i class="bi bi-credit-card me-1"></i> Complete Payment
              </a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Status Alert -->
        <div class="alert alert-<?php
                                echo $reservation['status'] === 'confirmed' ? 'success' : ($reservation['status'] === 'pending' ? 'warning' : ($reservation['status'] === 'cancelled' ? 'danger' : 'secondary'));
                                ?> d-flex align-items-center mb-4" role="alert">
          <i class="bi bi-<?php
                          echo $reservation['status'] === 'confirmed' ? 'check-circle' : ($reservation['status'] === 'pending' ? 'clock' : ($reservation['status'] === 'cancelled' ? 'x-circle' : 'check2'));
                          ?> me-2 fs-4"></i>
          <div>
            <h5 class="alert-heading mb-1">
              Reservation <?php echo ucfirst($reservation['status']); ?>
            </h5>
            <p class="mb-0">
              <?php
              if ($reservation['status'] === 'pending') {
                echo 'Awaiting payment confirmation';
              } elseif ($reservation['status'] === 'confirmed') {
                echo 'Your booking is confirmed and ready';
              } elseif ($reservation['status'] === 'cancelled') {
                echo 'This reservation has been cancelled';
                if ($reservation['cancelled_at']) {
                  echo ' on ' . date('M d, Y', strtotime($reservation['cancelled_at']));
                }
              } else {
                echo 'This reservation has been completed';
              }
              ?>
            </p>
          </div>
        </div>

        <div class="row">
          <!-- Left Column -->
          <div class="col-lg-8">
            <!-- Reservation Timeline -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Reservation Timeline</h5>
              </div>
              <div class="card-body">
                <div class="timeline">
                  <?php
                  $timelineItems = [
                    [
                      'status' => 'created',
                      'date' => $reservation['created_at'],
                      'title' => 'Reservation Created',
                      'description' => 'Your booking request was submitted',
                      'completed' => true
                    ],
                    [
                      'status' => 'confirmed',
                      'date' => $reservation['confirmed_at'] ?? null,
                      'title' => 'Payment Confirmed',
                      'description' => 'Payment processed and booking confirmed',
                      'completed' => in_array($reservation['status'], ['confirmed', 'completed'])
                    ],
                    [
                      'status' => 'check_in',
                      'date' => $reservation['check_in'],
                      'title' => 'Check-in Date',
                      'description' => 'Scheduled arrival date',
                      'completed' => $reservation['status'] === 'completed' ||
                        (strtotime($reservation['check_in']) < time())
                    ],
                    [
                      'status' => 'check_out',
                      'date' => $reservation['check_out'],
                      'title' => 'Check-out Date',
                      'description' => 'Scheduled departure date',
                      'completed' => $reservation['status'] === 'completed' ||
                        (strtotime($reservation['check_out']) < time())
                    ]
                  ];

                  if ($reservation['status'] === 'cancelled') {
                    $timelineItems[] = [
                      'status' => 'cancelled',
                      'date' => $reservation['cancelled_at'],
                      'title' => 'Reservation Cancelled',
                      'description' => $reservation['cancellation_reason'] ?? 'Cancelled by customer',
                      'completed' => true
                    ];
                  }
                  ?>

                  <?php foreach ($timelineItems as $item): ?>
                    <?php if ($item['date']): ?>
                      <div class="timeline-item <?php echo $item['completed'] ? 'completed' : ''; ?>">
                        <h6 class="mb-1"><?php echo $item['title']; ?></h6>
                        <p class="text-muted small mb-1"><?php echo $item['description']; ?></p>
                        <small class="text-muted">
                          <i class="bi bi-calendar me-1"></i>
                          <?php echo date('F j, Y g:i A', strtotime($item['date'])); ?>
                        </small>
                      </div>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <!-- Room Details -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-door-closed me-2"></i>Room Details</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <h5><?php echo htmlspecialchars($reservation['room_type']); ?></h5>
                    <p class="text-muted">Room #<?php echo $reservation['room_number']; ?></p>
                    <p><?php echo htmlspecialchars($reservation['room_description']); ?></p>

                    <div class="row mt-3">
                      <div class="col-6">
                        <small class="text-muted d-block">Check-in</small>
                        <strong><?php echo date('D, M j, Y', strtotime($reservation['check_in'])); ?></strong>
                      </div>
                      <div class="col-6">
                        <small class="text-muted d-block">Check-out</small>
                        <strong><?php echo date('D, M j, Y', strtotime($reservation['check_out'])); ?></strong>
                      </div>
                    </div>

                    <div class="row mt-3">
                      <div class="col-6">
                        <small class="text-muted d-block">Duration</small>
                        <strong>
                          <?php
                          $checkIn = new DateTime($reservation['check_in']);
                          $checkOut = new DateTime($reservation['check_out']);
                          echo $checkOut->diff($checkIn)->days . ' nights';
                          ?>
                        </strong>
                      </div>
                      <div class="col-6">
                        <small class="text-muted d-block">Guests</small>
                        <strong><?php echo $reservation['guests']; ?> guests</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 text-center">
                    <div class="bg-light rounded p-3">
                      <h3>$<?php echo number_format($reservation['price_per_night'], 2); ?></h3>
                      <small class="text-muted">per night</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php if ($reservation['service_name']): ?>
              <!-- Service Details -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0"><i class="bi bi-stars me-2"></i>Additional Service</h5>
                </div>
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-md-8">
                      <h6><?php echo htmlspecialchars($reservation['service_name']); ?></h6>
                      <p class="text-muted mb-0"><?php echo htmlspecialchars($reservation['service_description']); ?></p>
                    </div>
                    <div class="col-md-4 text-end">
                      <h5 class="text-primary">$<?php echo number_format($reservation['service_price'], 2); ?></h5>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($reservation['special_requests']): ?>
              <!-- Special Requests -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0"><i class="bi bi-chat-text me-2"></i>Special Requests</h5>
                </div>
                <div class="card-body">
                  <p class="mb-0"><?php echo nl2br(htmlspecialchars($reservation['special_requests'])); ?></p>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- Right Column -->
          <div class="col-lg-4">
            <!-- Price Breakdown -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Price Breakdown</h5>
              </div>
              <div class="card-body">
                <table class="table price-breakdown w-100">
                  <?php
                  $checkIn = new DateTime($reservation['check_in']);
                  $checkOut = new DateTime($reservation['check_out']);
                  $nights = $checkOut->diff($checkIn)->days;
                  $roomTotal = $reservation['price_per_night'] * $nights;
                  $serviceTotal = $reservation['service_price'] ?? 0;
                  $taxRate = 0.10;
                  $taxAmount = ($roomTotal + $serviceTotal) * $taxRate;
                  $grandTotal = $roomTotal + $serviceTotal + $taxAmount;
                  ?>

                  <tr>
                    <td>Room (<?php echo $nights; ?> nights × $<?php echo number_format($reservation['price_per_night'], 2); ?>)</td>
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
                    <td class="text-end">$<?php echo number_format($taxAmount, 2); ?></td>
                  </tr>

                  <tr>
                    <td><strong>Total Amount</strong></td>
                    <td class="text-end"><strong>$<?php echo number_format($grandTotal, 2); ?></strong></td>
                  </tr>

                  <?php if ($reservation['deposit_amount'] > 0): ?>
                    <tr>
                      <td>Deposit Paid</td>
                      <td class="text-end text-success">-$<?php echo number_format($reservation['deposit_amount'], 2); ?></td>
                    </tr>

                    <tr>
                      <td><strong>Balance Due</strong></td>
                      <td class="text-end"><strong>$<?php echo number_format($grandTotal - $reservation['deposit_amount'], 2); ?></strong></td>
                    </tr>
                  <?php endif; ?>
                </table>
              </div>
            </div>

            <!-- Payment Information -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Payment Information</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <small class="text-muted d-block">Payment Status</small>
                  <span class="badge bg-<?php
                                        echo $reservation['payment_status'] === 'paid' ? 'success' : 'warning';
                                        ?>">
                    <?php echo ucfirst($reservation['payment_status'] ?? 'pending'); ?>
                  </span>
                </div>

                <?php if ($reservation['payment_method']): ?>
                  <div class="mb-3">
                    <small class="text-muted d-block">Payment Method</small>
                    <strong><?php echo ucfirst($reservation['payment_method']); ?></strong>
                  </div>
                <?php endif; ?>

                <?php if ($reservation['transaction_id']): ?>
                  <div class="mb-3">
                    <small class="text-muted d-block">Transaction ID</small>
                    <code><?php echo $reservation['transaction_id']; ?></code>
                  </div>
                <?php endif; ?>

                <?php if ($reservation['payment_date']): ?>
                  <div class="mb-3">
                    <small class="text-muted d-block">Payment Date</small>
                    <strong><?php echo date('M j, Y g:i A', strtotime($reservation['payment_date'])); ?></strong>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <a href="index.php?action=customer/reservations/invoice&id=<?php echo $reservation['id']; ?>"
                    class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-download me-1"></i> Download Invoice
                  </a>

                  <?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
                    <button type="button" class="btn btn-outline-danger"
                      data-bs-toggle="modal" data-bs-target="#cancelModal">
                      <i class="bi bi-x-circle me-1"></i> Cancel Reservation
                    </button>
                  <?php endif; ?>

                  <a href="index.php?action=customer/booking" class="btn btn-outline-secondary">
                    <i class="bi bi-plus-circle me-1"></i> Make Another Booking
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Cancel Modal -->
  <?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
    <div class="modal fade" id="cancelModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cancel Reservation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form method="POST" action="index.php?action=customer/reservations/cancel&id=<?php echo $reservation['id']; ?>">
            <div class="modal-body">
              <p>Are you sure you want to cancel reservation #<?php echo str_pad($reservation['id'], 6, '0', STR_PAD_LEFT); ?>?</p>
              <div class="mb-3">
                <label class="form-label">Reason for cancellation:</label>
                <textarea class="form-control" name="cancellation_reason" rows="3"
                  placeholder="Optional reason for cancellation"></textarea>
              </div>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Cancellation Policy:</strong><br>
                • Cancellations within 24 hours of check-in may incur a 50% fee<br>
                • No-show reservations will be charged the full amount
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>

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
  </script>
</body>

</html>
