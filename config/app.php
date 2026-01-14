<?php

define('BASE_PATH', dirname(__DIR__));

// DB credentials from .env with fallback defaults
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'hotelreservation'));
