<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$db = (new Database())->baglan();
