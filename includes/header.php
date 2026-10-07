<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$db = (new Database())->baglan();
$kategoriStmt = $db->query("SELECT id, ad, slug FROM kategoriler ORDER BY ad ASC");
$tumKategoriler = $kategoriStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($sayfaBasligi) ? e($sayfaBasligi) . ' - ' . SITE_ADI : SITE_ADI ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/img/favicon.png">
    <link rel="icon" type="image/x-icon" href="<?= SITE_URL ?>/assets/img/favicon.ico">
    <link rel="apple-touch-icon" href="<?= SITE_URL ?>/assets/img/apple-touch-icon.png">
</head>
<body>
<header class="site-header">
    <div class="konteyner site-header-ic">
        <a href="<?= SITE_URL ?>/index.php" class="logo">
            <svg width="435" height="127.5" viewBox="0 0 680 200" style="display:block;">
                <rect x="135" y="105" width="70" height="70" transform="rotate(45 170 140)" fill="#B23A2F"/>
                <circle cx="110" cy="100" r="70" fill="#1B3A5C" stroke="#0F2740" stroke-width="2"/>
                <circle cx="110" cy="100" r="58" fill="none" stroke="#FFFFFF" stroke-opacity="0.35" stroke-width="1.5"/>
                <text x="110" y="112" text-anchor="middle" font-size="34" font-weight="500" fill="#FFFFFF" font-family="Georgia, serif">HS</text>
                <line x1="200" y1="55" x2="200" y2="145" stroke="#D3D1C7" stroke-width="1"/>
                <text x="225" y="95" font-size="30" font-weight="500" fill="#1B3A5C" font-family="Georgia, serif">Haber Son</text>
                <text x="225" y="122" font-size="13" fill="#5C5A55">Doğru haber, doğru kaynaktan</text>
            </svg>
        </a>
        <nav class="ana-menu">
            <a href="<?= SITE_URL ?>/index.php">Anasayfa</a>
            <?php foreach ($tumKategoriler as $kat): ?>
                <a href="<?= SITE_URL ?>/kategori.php?slug=<?= e($kat['slug']) ?>">
                    <?= e($kat['ad']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <form action="<?= SITE_URL ?>/search.php" method="GET" style="display:flex; align-items:center;">
            <input type="text" name="q" placeholder="Haber ara..." style="padding:7px 10px; border:1px solid var(--renk-cizgi); border-radius:4px; font-size:14px;">
            <button type="submit" style="padding:7px 12px; margin-left:6px; border:none; background:var(--renk-vurgu); color:#fff; border-radius:4px; cursor:pointer;">Ara</button>
        </form>
    </div>
</header>
<?php
$tickerStmt = $db->query(
        "SELECT baslik, slug FROM haberler WHERE durum = 'yayinda' ORDER BY olusturma_tarihi DESC LIMIT 6"
);
$tickerHaberler = $tickerStmt->fetchAll();
if (!empty($tickerHaberler)):
    ?>
    <div class="son-dakika-serit">
        <span class="son-dakika-etiket">Son Dakika</span>
        <div class="son-dakika-kayan">
            <div class="son-dakika-icerik">
                <?php foreach (array_merge($tickerHaberler, $tickerHaberler) as $t): ?>
                    <a href="haber.php?slug=<?= e($t['slug']) ?>"><?= e($t['baslik']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<main class="konteyner ana-icerik">