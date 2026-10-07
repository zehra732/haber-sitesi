<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_ADI', 'Haber Son');
define('SITE_URL', 'http://localhost/haber-sitesi');
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');

date_default_timezone_set('Europe/Istanbul');
