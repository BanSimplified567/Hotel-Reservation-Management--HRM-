<?php
// app/controllers/Public/ContactController.php
require_once __DIR__ . '/../Path/BaseController.php';

class ContactController extends BaseController
{

  public function index()
  {
    $success = false;
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $result = $this->handleContactForm();
      $success = $result['success'];
      $error = $result['error'];
    }

    $data = [
      'success' => $success,
      'error' => $error,
      'contact_info' => $this->getContactInfo(),
      'business_hours' => $this->getBusinessHours(),
      'page_title' => 'Contact Us'
    ];

    $this->render('public/contact', $data);
  }

  private function handleContactForm()
  {
    try {
      // Validate inputs
      $name = trim($_POST['name'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $phone = trim($_POST['phone'] ?? '');
      $message = trim($_POST['message'] ?? '');

      // Basic validation
      if (empty($name) || empty($email) || empty($message)) {
        return ['success' => false, 'error' => 'Please fill in all required fields.'];
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Please enter a valid email address.'];
      }

      // Save to database
      $sql = "INSERT INTO contact_messages (name, email, phone, message)
                    VALUES (:name, :email, :phone, :message)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':message' => $message
      ]);

      // Send email notification (optional - you'll need to configure your mail server)
      // $this->sendEmailNotification($name, $email, $phone, $message);

      return ['success' => true, 'error' => ''];
    } catch (Exception $e) {
      error_log("Contact form error: " . $e->getMessage());
      return ['success' => false, 'error' => 'An error occurred. Please try again later.'];
    }
  }

  private function sendEmailNotification($name, $email, $phone, $message)
  {
    // This is a basic example - you'll need to configure your mail server
    $to = "hotel@example.com";
    $subject = "New Contact Form Submission";
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $emailBody = "
        <html>
        <body>
            <h2>New Contact Message</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Message:</strong></p>
            <p>{$message}</p>
        </body>
        </html>
        ";

    mail($to, $subject, $emailBody, $headers);
  }

  public function getContactInfo()
  {
    return [
      'address' => '123 Hotel Street, Cityville, ST 12345',
      'phone' => '+1 (555) 123-4567',
      'email' => 'info@luxuryhotel.com',
      'front_desk' => '+1 (555) 123-4568',
      'reservations' => '+1 (555) 123-4569',
      'fax' => '+1 (555) 123-4570',
      'emergency' => '+1 (555) 123-9111'
    ];
  }

  public function getBusinessHours()
  {
    return [
      ['day' => 'Monday - Friday', 'hours' => '7:00 AM - 11:00 PM'],
      ['day' => 'Saturday', 'hours' => '8:00 AM - 10:00 PM'],
      ['day' => 'Sunday', 'hours' => '8:00 AM - 9:00 PM'],
      ['day' => 'Reception', 'hours' => '24/7']
    ];
  }
}
