<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

http_response_code(404);

$db = (new Database())->baglan();
$sayfaBasligi = 'Sayfa Bulunamadı';

require_once __DIR__ . '/includes/header.php';
?>

<section style="text-align:center; padding:60px 20px;">
    <h1 style="font-size:72px; margin:0; color:var(--renk-vurgu);">404</h1>
    <h2 style="margin:10px 0 16px;">Aradığın sayfa bulunamadı</h2>
    <p style="color:var(--renk-yazi-soluk); margin-bottom:24px;">
        Bu adres kaldırılmış, taşınmış olabilir ya da hiç var olmamış olabilir.
    </p>
    <a href="<?= SITE_URL ?>/index.php" style="display:inline-block; padding:10px 24px; background:var(--renk-vurgu); color:#fff; border-radius:6px; font-weight:600;">
        Anasayfaya Dön
    </a>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
