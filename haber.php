<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = (new Database())->baglan();

$slug = $_GET['slug'] ?? '';

$haberStmt = $db->prepare(
    "SELECT h.*, k.ad AS kategori_ad
     FROM haberler h
     JOIN kategoriler k ON k.id = h.kategori_id
     WHERE h.slug = :slug AND h.durum = 'yayinda'
     LIMIT 1"
);
$haberStmt->execute(['slug' => $slug]);
$haber = $haberStmt->fetch();

if (!$haber) {
    header('Location: index.php');
    exit;
}

// Görüntülenme sayısını bir artır
$db->prepare("UPDATE haberler SET goruntulenme = goruntulenme + 1 WHERE id = :id")
    ->execute(['id' => $haber['id']]);

$sayfaBasligi = $haber['baslik'];

require_once __DIR__ . '/includes/header.php';
?>

<article class="haber-detay">
    <span class="kategori-etiket"><?= e($haber['kategori_ad']) ?></span>
    <h1><?= e($haber['baslik']) ?></h1>
    <div class="haber-detay-meta">
        <span><?= tarihFormatla($haber['olusturma_tarihi']) ?></span>
        <span><?= (int) $haber['goruntulenme'] ?> görüntülenme</span>
    </div>
    <?php if ($haber['resim']): ?>
        <img src="<?= UPLOAD_URL . e($haber['resim']) ?>" alt="<?= e($haber['baslik']) ?>" style="max-width:100%; max-height:600px; width:auto; height:auto; display:block; margin:0 auto 20px; border-radius:8px;">
    <?php endif; ?>
    <p class="haber-ozet"><?= e($haber['ozet']) ?></p>

    <div class="haber-icerik">
        <?= nl2br(e($haber['icerik'])) ?>
    </div>
</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
