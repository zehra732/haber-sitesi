<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->baglan();

$mesaj = '';
$basarili = false;

$sayim = $db->query("SELECT COUNT(*) AS adet FROM yoneticiler")->fetch();

if ($sayim['adet'] > 0) {
    $mesaj = 'Zaten kayıtlı bir yönetici hesabı var. Bu dosyayı artık silebilirsin.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullaniciAdi = trim($_POST['kullanici_adi'] ?? '');
    $sifre = $_POST['sifre'] ?? '';
    $adSoyad = trim($_POST['ad_soyad'] ?? '');

    if ($kullaniciAdi === '' || $sifre === '' || $adSoyad === '') {
        $mesaj = 'Lütfen tüm alanları doldur.';
    } elseif (strlen($sifre) < 6) {
        $mesaj = 'Şifre en az 6 karakter olmalı.';
    } else {
        $hash = password_hash($sifre, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO yoneticiler (kullanici_adi, sifre, ad_soyad) VALUES (:k, :s, :a)");
        $stmt->execute(['k' => $kullaniciAdi, 's' => $hash, 'a' => $adSoyad]);
        $basarili = true;
        $mesaj = 'Yönetici hesabı oluşturuldu! Artık giriş yapabilirsin.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kurulum</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="konteyner" style="max-width:400px; padding-top:60px;">
    <h1>İlk Kurulum</h1>

    <?php if ($mesaj): ?>
        <p style="color: <?= $basarili ? 'green' : 'red' ?>;"><?= htmlspecialchars($mesaj) ?></p>
    <?php endif; ?>

    <?php if (!$basarili && $sayim['adet'] == 0): ?>
        <form method="POST">
            <p><label>Ad Soyad<br><input type="text" name="ad_soyad" style="width:100%; padding:8px;" required></label></p>
            <p><label>Kullanıcı Adı<br><input type="text" name="kullanici_adi" style="width:100%; padding:8px;" required></label></p>
            <p><label>Şifre<br><input type="password" name="sifre" style="width:100%; padding:8px;" required></label></p>
            <button type="submit" style="padding:10px 20px;">Hesabı Oluştur</button>
        </form>
    <?php endif; ?>

    <?php if ($basarili): ?>
        <a href="login.php">Giriş sayfasına git →</a>
    <?php endif; ?>
</div>
</body>
</html>
