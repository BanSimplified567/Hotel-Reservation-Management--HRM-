<?php
// app/controllers/ContactController.php
require_once __DIR__ . '/Path/BaseController.php';

class ContactController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContactForm();
        } else {
            $this->showContactForm();
        }
    }

    private function showContactForm()
    {
        // Pre-fill user info if logged in
        $user_data = [];
        if (isset($_SESSION['user_id'])) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT first_name, last_name, email, phone
                    FROM users
                    WHERE id = ?
                ");
                $stmt->execute([$_SESSION['user_id']]);
                $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Get user data error: " . $e->getMessage());
            }
        }

        $data = [
            'user_data' => $user_data,
            'page_title' => 'Contact Us'
        ];

        $this->render('public/contact', $data);
    }

    private function handleContactForm()
    {
        $errors = [];

        // Collect form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $user_id = $_SESSION['user_id'] ?? null;

        // Validation
        if (empty($name)) {
            $errors[] = "Name is required.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($subject)) {
            $errors[] = "Subject is required.";
        }

        if (empty($message)) {
            $errors[] = "Message is required.";
        } elseif (strlen($message) < 10) {
            $errors[] = "Message must be at least 10 characters.";
        }

        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO contact_messages
                    (user_id, name, email, phone, subject, message, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$user_id, $name, $email, $phone, $subject, $message]);

                // Log the action if user is logged in
                if ($user_id) {
                    $this->logAction($user_id, "Submitted contact form: $subject");
                }

                // Send email notification to admin (in production)
                // $this->sendContactEmail($name, $email, $subject, $message);

                $_SESSION['success'] = "Thank you for your message! We'll get back to you soon.";
                $this->redirect('contact');

            } catch (PDOException $e) {
                error_log("Contact form submission error: " . $e->getMessage());
                $errors[] = "Failed to submit message. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('contact');
        }
    }

    public function adminIndex()
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        // Get filter parameters
        $status = $_GET['status'] ?? 'unread';
        $search = $_GET['search'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 20;

        // Build query
        $query = "
            SELECT cm.*, u.username, u.first_name, u.last_name
            FROM contact_messages cm
            LEFT JOIN users u ON cm.user_id = u.id
            WHERE 1=1
        ";
        $params = [];

        if ($status == 'unread') {
            $query .= " AND cm.is_read = 0";
        } elseif ($status == 'read') {
            $query .= " AND cm.is_read = 1";
        } elseif ($status == 'replied') {
            $query .= " AND cm.is_replied = 1";
        }

        if (!empty($search)) {
            $query .= " AND (
                cm.name LIKE ? OR
                cm.email LIKE ? OR
                cm.subject LIKE ? OR
                cm.message LIKE ? OR
                u.username LIKE ?
            )";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        // Get total count
        $countQuery = "SELECT COUNT(*) FROM (" . $query . ") as total";
        $countStmt = $this->pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalMessages = $countStmt->fetchColumn();
        $totalPages = ceil($totalMessages / $perPage);

        // Add pagination
        $offset = ($page - 1) * $perPage;
        $query .= " ORDER BY cm.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        // Execute query
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'messages' => $messages,
            'status' => $status,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalMessages' => $totalMessages,
            'page_title' => 'Contact Messages'
        ];

        $this->render('admin/contact/index', $data);
    }

    public function adminView($id)
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        try {
            // Get message
            $stmt = $this->pdo->prepare("
                SELECT cm.*, u.username, u.first_name, u.last_name, u.email as user_email
                FROM contact_messages cm
                LEFT JOIN users u ON cm.user_id = u.id
                WHERE cm.id = ?
            ");
            $stmt->execute([$id]);
            $message = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$message) {
                $_SESSION['error'] = "Message not found.";
                $this->redirect('admin/contact');
            }

            // Mark as read
            if (!$message['is_read']) {
                $stmt = $this->pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
                $stmt->execute([$id]);
            }

            // Get replies if any
            $stmt = $this->pdo->prepare("
                SELECT * FROM contact_replies
                WHERE contact_message_id = ?
                ORDER BY created_at
            ");
            $stmt->execute([$id]);
            $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = [
                'message' => $message,
                'replies' => $replies,
                'page_title' => 'View Contact Message'
            ];

            $this->render('admin/contact/view', $data);
        } catch (PDOException $e) {
            error_log("View contact message error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load message.";
            $this->redirect('admin/contact');
        }
    }

    public function adminReply($id)
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reply_message = trim($_POST['reply_message'] ?? '');

            if (empty($reply_message)) {
                $_SESSION['error'] = "Reply message is required.";
                $this->redirect('admin/contact', ['sub_action' => 'view', 'id' => $id]);
            }

            try {
                // Start transaction
                $this->pdo->beginTransaction();

                // Add reply
                $stmt = $this->pdo->prepare("
                    INSERT INTO contact_replies
                    (contact_message_id, user_id, message, created_at)
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$id, $_SESSION['user_id'], $reply_message]);

                // Update message status
                $stmt = $this->pdo->prepare("
                    UPDATE contact_messages
                    SET is_replied = 1, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$id]);

                // Get message details for email
                $stmt = $this->pdo->prepare("
                    SELECT cm.email, cm.name, cm.subject
                    FROM contact_messages cm
                    WHERE cm.id = ?
                ");
                $stmt->execute([$id]);
                $message = $stmt->fetch(PDO::FETCH_ASSOC);

                // Commit transaction
                $this->pdo->commit();

                // Log the action
                $this->logAction($_SESSION['user_id'], "Replied to contact message #$id");

                // Send reply email (in production)
                // $this->sendReplyEmail($message['email'], $message['name'], $message['subject'], $reply_message);

                $_SESSION['success'] = "Reply sent successfully.";
                $this->redirect('admin/contact', ['sub_action' => 'view', 'id' => $id]);

            } catch (PDOException $e) {
                $this->pdo->rollBack();
                error_log("Reply to contact error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to send reply.";
                $this->redirect('admin/contact', ['sub_action' => 'view', 'id' => $id]);
            }
        }

        $this->redirect('admin/contact');
    }

    public function adminDelete($id)
    {
        $this->requireLogin('admin');

        try {
            // Delete replies first
            $stmt = $this->pdo->prepare("DELETE FROM contact_replies WHERE contact_message_id = ?");
            $stmt->execute([$id]);

            // Delete message
            $stmt = $this->pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->execute([$id]);

            // Log the action
            $this->logAction($_SESSION['user_id'], "Deleted contact message #$id");

            $_SESSION['success'] = "Message deleted successfully.";
        } catch (PDOException $e) {
            error_log("Delete contact message error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete message.";
        }

        $this->redirect('admin/contact');
    }

    private function logAction($userId, $action)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO logs (user_id, action, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $action]);
        } catch (PDOException $e) {
            error_log("Log action error: " . $e->getMessage());
        }
    }

    private function sendContactEmail($name, $email, $subject, $message)
    {
        // In production, implement email sending
        // This is a placeholder function
        $to = "admin@hotel.com";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>From:</strong> $name ($email)</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        ";

        // mail($to, "Contact Form: $subject", $email_body, $headers);
        return true;
    }

    private function sendReplyEmail($to_email, $to_name, $original_subject, $reply_message)
    {
        // In production, implement email sending
        // This is a placeholder function
        $subject = "Re: $original_subject";
        $headers = "From: noreply@hotel.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_body = "
            <h2>Reply to your message</h2>
            <p>Dear $to_name,</p>
            <p>Thank you for contacting us. Here is our reply:</p>
            <p>$reply_message</p>
            <p>Best regards,<br>Hotel Management Team</p>
        ";

        // mail($to_email, $subject, $email_body, $headers);
        return true;
    }
}
