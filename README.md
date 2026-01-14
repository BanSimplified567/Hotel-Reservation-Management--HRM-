# Hotel Management System

This project is a web-based application for a hotel management system, developed in PHP. It features a complete user authentication system, including user registration, login, logout, and password reset functionalities. Once authenticated, users are redirected to a personal dashboard.

The application follows a Model-View-Controller (MVC) like architecture, with a clear separation of concerns between the business logic, the presentation layer, and the request handling.

## Technologies Used

*   **Backend:** PHP
*   **Frontend:** HTML, CSS
*   **Database:** MySQL / MariaDB (inferred)
*   **Web Server:** Apache (inferred from `.htaccess`)
*   **Development Environment:** Laragon

## Folder Structure

```
c:\laragon\www\Hotel\c:\laragon\www\Hotel\
├───.gitignore
├───README.md
├───reservation.sql
├───.env.example
├───composer.json
├───phpunit.xml
├───app\
│   ├───controllers\
│   │   ├───Admin\
│   │   │   ├───DashboardController.php
│   │   │   ├───UserController.php
│   │   │   ├───ReservationController.php
│   │   │   ├───RoomController.php
│   │   │   ├───ServiceController.php
│   │   │   └───ReportController.php
│   │   ├───Auth\
│   │   │   └───AuthController.php
│   │   ├───DashboardController.php
│   │   ├───ProfileController.php
│   │   ├───ReservationController.php
│   │   ├───BookingController.php
│   │   ├───RoomSearchController.php
│   │   ├───ContactController.php
│   │   ├───AboutController.php
│   │   ├───RoomController.php
│   │   └───Path\
│   │       ├───BaseController.php
│   │       └───Controller.php
│   ├───middleware\
│   │   └───auth.php
│   └───views\
│       ├───admin\
│       │   ├───dashboard.php
│       │   ├───users\ (NEW - PARTIAL)
│       │   │   ├───index.php
│       │   │   ├───create.php
│       │   │   ├───edit.php
│       │   │   └───view.php
│       │   ├───reservations\ (NEW - PARTIAL)
│       │   │   ├───index.php
│       │   │   ├───view.php
│       │   │   ├───edit.php
│       │   │   └───status-modal.php
│       │   ├───rooms\ (NEW - PARTIAL)
│       │   │   ├───index.php
│       │   │   ├───create.php
│       │   │   ├───edit.php
│       │   │   ├───view.php
│       │   │   └───availability-calendar.php
│       │   ├───services\ (NEW - PARTIAL)
│       │   │   ├───index.php
│       │   │   ├───create.php
│       │   │   ├───edit.php
│       │   │   └───status-modal.php
│       │   ├───reports\ (NEW - PARTIAL)
│       │   │   ├───index.php
│       │   │   ├───revenue.php
│       │   │   ├───occupancy.php
│       │   │   ├───reservations.php
│       │   │   ├───customers.php
│       │   │   ├───services.php
│       │   │   └───export-modal.php
│       │   ├───contact\ (NEW - MISSING)
│       │   │   ├───index.php
│       │   │   ├───view.php
│       │   │   ├───reply.php
│       │   │   └───reply-modal.php
│       │   └───partials\ (NEW - MISSING)
│       │       ├───header.php
│       │       ├───sidebar.php
│       │       ├───footer.php
│       │       └───pagination.php
│       ├───auth\
│       │   ├───error.php
│       │   ├───forgot-password.php
│       │   ├───login.php
│       │   ├───logout.php
│       │   ├───register.php
│       │   └───reset-password.php
│       ├───customer\
│       │   ├───dashboard.php
│       │   ├───reservations\ (NEW - PARTIAL)
│       │   │   ├───index.php
│       │   │   ├───view.php
│       │   │   ├───cancel.php
│       │   │   └───invoice.php
│       │   ├───booking\ (NEW - PARTIAL)
│       │   │   ├───index.php
│       │   │   ├───confirmation.php
│       │   │   └───payment.php
│       │   └───profile\ (NEW - PARTIAL)
│       │       ├───index.php
│       │       ├───edit.php
│       │       └───change-password.php
│       ├───public\
│       │   ├───room-search.php
│       │   ├───rooms.php
│       │   ├───room-details.php (NEW - MISSING)
│       │   ├───room-compare.php (NEW - MISSING)
│       │   ├───contact.php
│       │   ├───about.php
│       │   ├───home.php (NEW - MISSING)
│       │   ├───amenities.php (NEW - MISSING)
│       │   └───gallery.php (NEW - MISSING)
│       ├───errors\
│       │   ├───403.php
│       │   ├───404.php
│       │   ├───500.php (NEW - MISSING)
│       │   └───maintenance.php (NEW - MISSING)
│       └───layout\
│           ├───header.php
│           ├───footer.php
│           ├───sidebar.php
│           ├───navbar.php
│           ├───admin-header.php (NEW - MISSING)
│           ├───admin-sidebar.php (NEW - MISSING)
│           ├───admin-footer.php (NEW - MISSING)
│           ├───customer-header.php (NEW - MISSING)
│           └───customer-sidebar.php (NEW - MISSING)
├───config\
│   ├───app.php
│   ├───dbconn.php
│   ├───load_env.php
│   ├───constants.php (NEW - MISSING)
│   ├───routes.php (NEW - MISSING)
│   ├───security.php (NEW - MISSING)
│   ├───mail.php (NEW - MISSING)
│   └───pagination.php (NEW - MISSING)
├───img\
├───public\
│   ├───.htaccess
│   ├───index.php
│   ├───assets\ (NEW - MISSING)
│   │   ├───css\
│   │   │   ├───style.css
│   │   │   ├───admin.css
│   │   │   ├───customer.css
│   │   │   └───responsive.css
│   │   ├───js\
│   │   │   ├───main.js
│   │   │   ├───admin.js
│   │   │   ├───booking.js
│   │   │   └───validation.js
│   │   └───img\
│   │       ├───logo.png
│   │       ├───favicon.ico
│   │       ├───rooms\
│   │       ├───gallery\
│   │       └───team\
│   └───uploads\ (NEW - MISSING)
│       ├───profile_pictures\
│       ├───room_images\
│       └───documents\
└───style\
```
