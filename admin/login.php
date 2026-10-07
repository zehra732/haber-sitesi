<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$db = (new Database())->baglan();
$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullaniciAdi = trim($_POST['kullanici_adi'] ?? '');
    $sifre = $_POST['sifre'] ?? '';

    if ($kullaniciAdi === '' || $sifre === '') {
        $hata = 'Kullanıcı adı ve şifre zorunludur.';
    } else {
        $stmt = $db->prepare("SELECT * FROM yoneticiler WHERE kullanici_adi = :k LIMIT 1");
        $stmt->execute(['k' => $kullaniciAdi]);
        $yonetici = $stmt->fetch();

        if ($yonetici && password_verify($sifre, $yonetici['sifre'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $yonetici['id'];
            $_SESSION['admin_ad'] = $yonetici['ad_soyad'];
            header('Location: index.php');
            exit;
        }

        $hata = 'Kullanıcı adı veya şifre hatalı.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Girişi</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="konteyner" style="max-width:380px; padding-top:80px;">
    <h1>Yönetici Girişi</h1>

    <?php if ($hata): ?>
        <p style="color:red;"><?= e($hata) ?></p>
    <?php endif; ?>

    <form method="POST">
        <p><label>Kullanıcı Adı<br><input type="text" name="kullanici_adi" style="width:100%; padding:8px;" required autofocus></label></p>
        <p><label>Şifre<br><input type="password" name="sifre" style="width:100%; padding:8px;" required></label></p>
        <button type="submit" style="padding:10px 20px;">Giriş Yap</button>
    </form>
</div>
</body>
</html>
