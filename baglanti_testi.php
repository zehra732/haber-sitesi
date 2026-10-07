<?php
require_once __DIR__ . '/config/database.php';

$db = new Database();
$baglanti = $db->baglan();

if ($baglanti) {
    echo "Veritabanına başarıyla bağlanıldı!";
}
