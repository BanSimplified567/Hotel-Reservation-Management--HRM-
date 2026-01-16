# Hotel Management System - Coding Rules & Conventions

This document outlines the coding standards, architectural patterns, and conventions used in the Hotel Management System application.

## Table of Contents
1. [Architecture Overview](#architecture-overview)
2. [File Structure](#file-structure)
3. [Naming Conventions](#naming-conventions)
4. [Controller Rules](#controller-rules)
5. [View Rules](#view-rules)
6. [Database Rules](#database-rules)
7. [Routing Rules](#routing-rules)
8. [Security Rules](#security-rules)
9. [Error Handling](#error-handling)
10. [Code Style](#code-style)
11. [Image & File Handling](#image--file-handling)
12. [Session Management](#session-management)

---

## Architecture Overview

### MVC-Like Pattern
- **Model**: Database interactions via PDO in controllers (no separate model layer)
- **View**: PHP templates in `app/views/`
- **Controller**: Request handlers in `app/controllers/`
- **Entry Point**: `public/index.php` handles routing

### Key Principles
- Separation of concerns: Controllers handle logic, Views handle presentation
- Single Responsibility: Each controller handles one domain area
- DRY (Don't Repeat Yourself): Common functionality in BaseController
- Security First: Always validate and sanitize user input

---

## File Structure

### Directory Organization
```
app/
├── controllers/          # Business logic handlers
│   ├── Admin/           # Admin-specific controllers
│   ├── Auth/            # Authentication controllers
│   └── Path/            # Base controllers
├── views/               # Presentation templates
│   ├── admin/          # Admin views
│   ├── auth/           # Auth views
│   ├── customer/       # Customer views
│   ├── public/         # Public views
│   ├── errors/         # Error pages
│   └── layout/         # Shared layout components
├── middleware/          # Middleware functions
config/                  # Configuration files
public/                  # Public entry point
```

### File Naming
- **Controllers**: `{Name}Controller.php` (e.g., `DashboardController.php`)
- **Views**: `{name}.php` (lowercase, kebab-case for multi-word)
- **Config**: `{name}.php` (e.g., `dbconn.php`, `app.php`)

---

## Naming Conventions

### Classes
- **Controllers**: `{Name}Controller` (PascalCase)
  - Example: `DashboardController`, `AuthController`
- **Admin Controllers**: `Admin{Name}Controller` or in `Admin/` namespace
  - Example: `AdminDashboardController` or `Admin/DashboardController.php`

### Methods
- **Public Methods**: `camelCase()` for action methods
  - Example: `index()`, `handleLogin()`, `showProfile()`
- **Private Methods**: `camelCase()` prefixed with action type
  - Example: `handleLogin()`, `showLoginForm()`, `getUserDetails()`

### Variables
- **PHP Variables**: `$camelCase`
  - Example: `$userId`, `$availableRooms`, `$primaryImage`
- **Database Fields**: `snake_case`
  - Example: `user_id`, `room_type_id`, `check_in`

### Constants
- **PHP Constants**: `UPPER_SNAKE_CASE`
  - Example: `BASE_PATH`, `DB_HOST`, `DB_NAME`

### Routes/Actions
- **Route Names**: `kebab-case` or `namespace/action`
  - Example: `login`, `admin/dashboard`, `room-search`

---

## Controller Rules

### Base Controller Pattern
```php
<?php
// app/controllers/{Name}Controller.php
require_once __DIR__ . '/Path/BaseController.php';

class {Name}Controller extends BaseController
{
    // All controllers extend BaseController
    // PDO is injected via constructor
}
```

### Controller Structure
1. **Constructor**: Accept `$pdo` parameter
2. **Public Methods**: Action handlers (e.g., `index()`, `create()`, `edit()`)
3. **Private Methods**: Helper methods prefixed with action type
   - `handle{Action}()` - Process form submissions
   - `show{Action}Form()` - Display forms
   - `get{Data}()` - Fetch data from database

### Controller Example
```php
class DashboardController extends BaseController
{
    public function index()
    {
        $this->requireLogin('customer');

        $userId = $_SESSION['user_id'];
        $data = [
            'user' => $this->getUserDetails($userId),
            'page_title' => 'Customer Dashboard'
        ];

        $this->render('customer/dashboard', $data);
    }

    private function getUserDetails($userId)
    {
        // Database logic here
    }
}
```

### Controller Rules
- ✅ Always extend `BaseController`
- ✅ Inject `$pdo` via constructor
- ✅ Use `requireLogin()` or `requireLogin($role)` for protected routes
- ✅ Use `render($view, $data)` to display views
- ✅ Use `redirect($action, $params)` for redirects
- ✅ Keep methods focused and single-purpose
- ✅ Use private methods for database queries
- ✅ Always handle PDO exceptions with try-catch
- ❌ Never output HTML directly in controllers
- ❌ Never access `$_GET`/`$_POST` without validation
- ❌ Never expose database credentials

---

## View Rules

### View Structure
```php
<?php
// app/views/{section}/{name}.php
// Note: Variables are passed via $data array from controller
?>

<!-- HTML/PHP Template -->
```

### View Rules
- ✅ Use PHP opening tag `<?php` at the top
- ✅ Always escape output with `htmlspecialchars()`
- ✅ Use `<?php echo ... ?>` or `<?= ... ?>` for output
- ✅ Use `<?php if (...): ?> ... <?php endif; ?>` for conditionals
- ✅ Use `<?php foreach (...): ?> ... <?php endforeach; ?>` for loops
- ✅ Include comments explaining data structure
- ✅ Use consistent indentation (2 or 4 spaces)
- ❌ Never include business logic in views
- ❌ Never access database directly from views
- ❌ Never use `extract()` manually (handled by BaseController)

### View Example
```php
<?php
// app/views/customer/dashboard.php
// Note: $user, $availableRooms, $page_title are passed from controller
?>

<h1><?php echo htmlspecialchars($page_title); ?></h1>
<?php foreach ($availableRooms as $room): ?>
    <div><?php echo htmlspecialchars($room['type']); ?></div>
<?php endforeach; ?>
```

### Image Paths in Views
- Use relative paths: `images/{filename}`
- For uploaded files: `uploads/{category}/{filename}`
- Always provide fallback: `images/default-room.jpg`
- Use `onerror` attribute for image fallback

---

## Database Rules

### PDO Configuration
```php
$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
    DB_USER,
    DB_PASS,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
);
```

### Database Query Rules
- ✅ Always use prepared statements
- ✅ Use `PDO::FETCH_ASSOC` for fetch mode
- ✅ Use `try-catch` for all database operations
- ✅ Log errors with `error_log()`
- ✅ Use named placeholders or `?` placeholders
- ✅ Validate data before database operations
- ❌ Never use string concatenation for queries
- ❌ Never expose database errors to users
- ❌ Never use `mysql_*` functions (deprecated)

### Query Example
```php
try {
    $stmt = $this->pdo->prepare("
        SELECT id, username, email
        FROM users
        WHERE id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Get user error: " . $e->getMessage());
    return [];
}
```

### Database Naming
- **Tables**: `snake_case`, plural (e.g., `users`, `room_types`)
- **Columns**: `snake_case` (e.g., `user_id`, `check_in`)
- **Foreign Keys**: `{table}_id` (e.g., `user_id`, `room_type_id`)

---

## Routing Rules

### Route Definition
Routes are defined in `public/index.php` using a switch statement:
```php
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        guest_only();
        require_once '../app/controllers/Auth/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->login();
        break;
}
```

### Route Patterns
- **Simple Routes**: `action=login`
- **Namespaced Routes**: `action=admin/dashboard`
- **Sub-actions**: `action=rooms&sub_action=view&id=123`

### Route Rules
- ✅ Use `guest_only()` for public-only routes
- ✅ Use `authorize(['role'])` for protected routes
- ✅ Always require controller file before instantiating
- ✅ Pass `$pdo` to controller constructor
- ✅ Handle 404 for unknown routes
- ❌ Never skip authorization checks
- ❌ Never expose internal file paths

### Route Organization
```php
// ========== AUTH ROUTES ==========
case 'login': ...
case 'register': ...

// ========== DASHBOARD ROUTES ==========
case 'dashboard': ...
case 'admin/dashboard': ...

// ========== ADMIN ROUTES ==========
case 'admin/users': ...
```

---

## Security Rules

### Authentication
- ✅ Always check `$_SESSION['user_id']` for authentication
- ✅ Use `requireLogin()` or `requireLogin($role)` in controllers
- ✅ Use `authorize(['role'])` middleware for route protection
- ✅ Regenerate session ID on login: `session_regenerate_id(true)`
- ✅ Hash passwords with `password_hash()` and verify with `password_verify()`
- ❌ Never store plain text passwords
- ❌ Never trust client-side validation alone

### Input Validation
- ✅ Always validate and sanitize user input
- ✅ Use `trim()` for string inputs
- ✅ Use `filter_var()` for email validation
- ✅ Use type casting for numbers: `intval()`, `floatval()`
- ✅ Validate required fields before processing
- ❌ Never use `$_GET`/`$_POST` directly without validation
- ❌ Never trust user input

### SQL Injection Prevention
- ✅ Always use prepared statements
- ✅ Never concatenate user input into SQL queries
- ✅ Use parameter binding: `$stmt->execute([$value])`

### XSS Prevention
- ✅ Always escape output: `htmlspecialchars($value)`
- ✅ Use `htmlspecialchars()` in all view templates
- ❌ Never output user input without escaping

### CSRF Protection
- ✅ Use CSRF tokens for forms (recommended)
- ✅ Verify POST requests are intentional

### Session Security
- ✅ Start session with `session_start()`
- ✅ Store minimal data in sessions
- ✅ Clear sensitive data on logout
- ✅ Use secure session settings in production

---

## Error Handling

### Error Logging
```php
try {
    // Database operation
} catch (PDOException $e) {
    error_log("Operation error: " . $e->getMessage());
    // Handle error gracefully
}
```

### Error Display Rules
- ✅ Log errors with `error_log()`
- ✅ Show user-friendly error messages
- ✅ Use `$_SESSION['error']` for user-facing errors
- ✅ Use `$_SESSION['success']` for success messages
- ❌ Never expose technical error details to users
- ❌ Never show database errors directly

### Error Pages
- Use `app/views/errors/403.php` for forbidden access
- Use `app/views/errors/404.php` for not found
- Set HTTP status codes: `http_response_code(403)`

---

## Code Style

### PHP Tags
- ✅ Use `<?php` for opening tags
- ✅ Omit closing `?>` tag in pure PHP files
- ✅ Use `<?php ?>` for inline PHP in views

### Indentation
- Use **4 spaces** for indentation (or 2 spaces consistently)
- Be consistent within each file

### Comments
- ✅ Use `//` for single-line comments
- ✅ Use `/* */` for multi-line comments
- ✅ Add file header comments: `// app/controllers/{Name}Controller.php`
- ✅ Comment complex logic
- ✅ Use section dividers: `// ========== SECTION ==========`

### Code Organization
```php
<?php
// File header comment

class Controller extends BaseController
{
    // 1. Constructor
    public function __construct($pdo) { }

    // 2. Public action methods
    public function index() { }

    // 3. Private helper methods
    private function helperMethod() { }
}
```

### Spacing
- ✅ One blank line between methods
- ✅ One blank line between logical sections
- ✅ No trailing whitespace
- ✅ Consistent spacing around operators

---

## Image & File Handling

### Image Paths
- **Static Images**: `images/{filename}` (relative to view directory)
- **Uploaded Images**: `uploads/{category}/{filename}`
- **Default Images**: `images/default-room.jpg`

### Image Processing Rules
- ✅ Process image paths in controller methods
- ✅ Use helper methods like `processRoomImages()`
- ✅ Handle multiple path formats (uploads/, images/, filename only)
- ✅ Always provide fallback default images
- ✅ Use `basename()` to extract filename from paths
- ✅ Store relative paths in database

### File Upload Rules
- ✅ Validate file types and sizes
- ✅ Use secure file names (sanitize)
- ✅ Store in `public/uploads/{category}/`
- ✅ Check file existence before using
- ❌ Never trust uploaded file names
- ❌ Never execute uploaded files

### Image Display in Views
```php
// Controller processes images
$room['images'] = $this->processRoomImages($room);

// View uses processed images
$primaryImage = $room['images']['primary'] ?? 'images/default-room.jpg';
<img src="<?php echo htmlspecialchars($primaryImage); ?>"
     onerror="this.src='images/default-room.jpg';">
```

---

## Session Management

### Session Variables
- `$_SESSION['user_id']` - Current user ID
- `$_SESSION['username']` - Current username
- `$_SESSION['email']` - Current user email
- `$_SESSION['role']` - User role (admin, staff, customer)
- `$_SESSION['first_name']` - User first name
- `$_SESSION['last_name']` - User last name
- `$_SESSION['error']` - Error messages
- `$_SESSION['success']` - Success messages

### Session Rules
- ✅ Start session in `public/index.php`
- ✅ Check session before accessing protected resources
- ✅ Clear session data on logout
- ✅ Use session for flash messages
- ❌ Never store sensitive data in sessions
- ❌ Never expose session IDs

### Flash Messages
```php
// Set message
$_SESSION['error'] = "Error message";
$_SESSION['success'] = "Success message";

// Display in view
if (isset($_SESSION['error'])) {
    echo $_SESSION['error'];
    unset($_SESSION['error']);
}
```

---

## Additional Best Practices

### Configuration
- ✅ Use `.env` file for environment variables
- ✅ Use `config/app.php` for application constants
- ✅ Never commit `.env` file to version control
- ✅ Use `env()` helper function for environment variables

### Helper Functions
- ✅ Use middleware functions: `authorize()`, `guest_only()`
- ✅ Use helper functions: `current_user_id()`, `has_role()`
- ✅ Keep helper functions in `app/middleware/`

### Code Reusability
- ✅ Extract common logic to BaseController
- ✅ Use private helper methods in controllers
- ✅ Create reusable view components
- ✅ Share layout components via `layout/` directory

### Testing Considerations
- ✅ Write testable code (separate concerns)
- ✅ Use dependency injection (PDO via constructor)
- ✅ Keep methods focused and small

---

## Quick Reference Checklist

### Before Committing Code
- [ ] All user input is validated and sanitized
- [ ] All database queries use prepared statements
- [ ] All output is escaped with `htmlspecialchars()`
- [ ] Error handling is implemented
- [ ] Authorization checks are in place
- [ ] Code follows naming conventions
- [ ] Comments are added for complex logic
- [ ] No hardcoded credentials or paths
- [ ] Session security is maintained
- [ ] Images have fallback paths

---

## Version History
- **v1.0** - Initial rules document based on application analysis

---

**Note**: These rules are based on the current codebase patterns. Update this document as conventions evolve.
