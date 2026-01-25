# Hotel Management System

A comprehensive web-based hotel management system built with PHP, featuring user authentication, room bookings, reservations management, and administrative controls.

## Features

- **User Authentication**: Registration, login, logout, password reset
- **Role-based Access**: Admin, Staff, Customer, and Guest roles
- **Room Management**: View, search, and book rooms
- **Reservation System**: Create, edit, cancel reservations
- **Admin Dashboard**: Manage users, rooms, services, reports
- **Customer Dashboard**: View reservations, profile management
- **Public Pages**: Room listings, contact forms, about page
- **Responsive Design**: Bootstrap-based UI

## Technologies Used

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Database**: MySQL/MariaDB
- **Web Server**: Apache/Nginx
- **Development Environment**: Laragon/XAMPP
- **Icons**: FontAwesome
- **Charts**: (if applicable)

## Installation

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd Hotel
   ```

2. **Set up environment**:
   - Copy `.env.example` to `.env` and configure database settings
   - Ensure PHP, MySQL, and Apache are running

3. **Database Setup**:
   - Import `reservation.sql` and `reservation_guests.sql` into your MySQL database
   - Update database credentials in `config/dbconn.php` or via environment variables

4. **Dependencies**:
   - The project uses Composer for PHP dependencies (if applicable)
   - Run `composer install` if `composer.json` is present

5. **Web Server Configuration**:
   - Point your web server to the `public/` directory
   - Ensure `public/index.php` is the entry point

6. **Access the Application**:
   - Open `http://localhost/Hotel/public/` in your browser
   - Default admin credentials: (set during installation or check database)

## Folder Structure

```
Hotel/
├── README.md
├── RULES.md
├── reservation.sql
├── reservation_guests.sql
├── app/
│   ├── controllers/
│   │   ├── AboutController.php
│   │   ├── BookingController.php
│   │   ├── CustomerController.php
│   │   ├── DashboardController.php
│   │   ├── ProfileController.php
│   │   ├── ReservationController.php
│   │   ├── RoomSearchController.php
│   │   ├── Admin/
│   │   │   ├── ContactController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ReportController.php
│   │   │   ├── ReservationController.php
│   │   │   ├── ReservationGuestsController.php
│   │   │   ├── RoomController.php
│   │   │   ├── ServiceController.php
│   │   │   └── UserController.php
│   │   ├── Auth/
│   │   │   └── AuthController.php
│   │   ├── Path/
│   │   │   ├── BaseController.php
│   │   │   └── Controller.php
│   │   └── Public/
│   │       ├── AboutController.php
│   │       ├── AmenitiesController.php
│   │       ├── ContactController.php
│   │       ├── GalleryController.php
│   │       ├── HomeController.php
│   │       └── RoomController.php
│   ├── middleware/
│   │   └── auth.php
│   └── views/
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── contact/
│       │   │   ├── index.php
│       │   │   ├── reply-modal.php
│       │   │   ├── reply.php
│       │   │   └── view.php
│       │   ├── partials/
│       │   ├── profile/
│       │   │   ├── edit.php
│       │   │   └── index.php
│       │   ├── reports/
│       │   │   ├── customers.php
│       │   │   ├── index.php
│       │   │   ├── occupancy.php
│       │   │   ├── reservations.php
│       │   │   ├── revenue.php
│       │   │   └── services.php
│       │   ├── reservation-guests/
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   ├── index.php
│       │   │   └── view.php
│       │   ├── reservations/
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   ├── index.php
│       │   │   └── view.php
│       │   ├── rooms/
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   ├── index.php
│       │   │   └── view.php
│       │   ├── services/
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   ├── index.php
│       │   │   └── view.php
│       │   └── users/
│       │       ├── create.php
│       │       ├── edit.php
│       │       ├── index.php
│       │       └── view.php
│       ├── auth/
│       │   ├── error.php
│       │   ├── forgot-password.php
│       │   ├── login.php
│       │   ├── logout.php
│       │   ├── register.php
│       │   └── reset-password.php
│       ├── customer/
│       │   ├── dashboard.php
│       │   ├── booking/
│       │   │   ├── confirmation.php
│       │   │   ├── index.php
│       │   │   └── payment.php
│       │   ├── profile/
│       │   └── reservations/
│       ├── errors/
│       │   ├── 403.php
│       │   └── 404.php
│       ├── images/
│       ├── layout/
│       │   ├── admin-header.php
│       │   ├── base-footer.php
│       │   ├── base-header.php
│       │   ├── footer.php
│       │   ├── navbar.php
│       │   └── sidebar.php
│       └── public/
│           ├── about.php
│           ├── amenities.php
│           ├── contact.php
│           ├── gallery.php
│           ├── room-details.php
│           ├── room-search.php
│           └── rooms.php
├── config/
│   ├── app.php
│   ├── dbconn.php
│   └── load_env.php
├── public/
│   ├── index.php
│   ├── images/
│   │   └── rooms/
│   └── uploads/
└── style/
```

## Usage

- **Guests**: Can view rooms, search availability, register/login
- **Customers**: Book rooms, view reservations, manage profile
- **Staff**: Manage reservations, rooms, services
- **Admins**: Full access including user management and reports

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit changes
4. Push to branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please contact the development team or create an issue in the repository.
