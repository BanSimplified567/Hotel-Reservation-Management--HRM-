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
│       │   ├───users\
│       │   │   ├───index.php
│       │   │   ├───create.php
│       │   │   ├───edit.php
│       │   │   └───view.php
│       │   ├───reservations\
│       │   │   ├───index.php
│       │   │   ├───view.php
│       │   │   ├───edit.php
│       │   │   └───status-modal.php
│       │   ├───rooms\
│       │   │   ├───index.php
│       │   │   ├───create.php
│       │   │   ├───edit.php
│       │   │   ├───view.php
│       │   │   └───availability-calendar.php
│       │   ├───services\
│       │   │   ├───index.php
│       │   │   ├───create.php
│       │   │   ├───edit.php
│       │   │   └───status-modal.php
│       │   ├───reports\
│       │   │   ├───index.php
│       │   │   ├───revenue.php
│       │   │   ├───occupancy.php
│       │   │   ├───reservations.php
│       │   │   ├───customers.php
│       │   │   ├───services.php
│       │   │   └───export-modal.php
│       │   ├───contact\
│       │   │   ├───index.php
│       │   │   ├───view.php
│       │   │   ├───reply.php
│       │   │   └───reply-modal.php
│       │   └───partials\
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
│       │   ├───reservations\
│       │   │   ├───index.php
│       │   │   ├───view.php
│       │   │   ├───cancel.php
│       │   │   └───invoice.php
│       │   ├───booking\
│       │   │   ├───index.php
│       │   │   ├───confirmation.php
│       │   │   └───payment.php
│       │   └───profile\
│       │       ├───index.php
│       │       ├───edit.php
│       │       └───change-password.php
│       ├───public\
│       │   ├───room-search.php
│       │   ├───rooms.php
│       │   ├───room-details.php
│       │   ├───room-compare.php
│       │   ├───contact.php
│       │   ├───about.php
│       │   ├───home.php
│       │   ├───amenities.php
│       │   └───gallery.php
│       ├───errors\
│       │   ├───403.php
│       │   ├───404.php
│       │   ├───500.php
│       │   └───maintenance.php
│       └───layout\
│           ├───header.php
│           ├───footer.php
│           └───sidebar.php
├───config\
│   ├───app.php
│   ├───dbconn.php
│   └───load_env.php
├───public\
│   ├───.htaccess
│   ├───index.php
│   └───uploads\
│       ├───profile_pictures\
│       ├───room_images\
│       └───documents\
└───style\
```
