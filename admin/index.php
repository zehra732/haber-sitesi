<?php
require_once __DIR__ . '/../includes/auth.php';

$toplamHaber = $db->query("SELECT COUNT(*) AS adet FROM haberler")->fetch()['adet'];
$toplamKategori = $db->query("SELECT COUNT(*) AS adet FROM kategoriler")->fetch()['adet'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetim Paneli</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="konteyner" style="padding-top:40px;">
    <h1>Hoş geldin, <?= e($_SESSION['admin_ad']) ?></h1>
    <p>Toplam haber: <?= (int) $toplamHaber ?> | Toplam kategori: <?= (int) $toplamKategori ?></p>
    <p><a href="<?= SITE_URL ?>/index.php" target="_blank">Siteyi görüntüle</a> | <a href="logout.php">Çıkış yap</a></p>
</div>
</body>
</html>
