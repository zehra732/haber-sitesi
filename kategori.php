<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = (new Database())->baglan();
$slug = $_GET['slug'] ?? '';

$katStmt = $db->prepare("SELECT * FROM kategoriler WHERE slug = :slug LIMIT 1");
$katStmt->execute(['slug' => $slug]);
$kategori = $katStmt->fetch();

if (!$kategori) {
    header('Location: index.php');
    exit;
}

$sayfaBasligi = $kategori['ad'];

require_once __DIR__ . '/includes/header.php';

$haberlerStmt = $db->prepare(
    "SELECT h.*, k.ad AS kategori_ad
     FROM haberler h
     JOIN kategoriler k ON k.id = h.kategori_id
     WHERE h.kategori_id = :kategori_id AND h.durum = 'yayinda'
     ORDER BY h.olusturma_tarihi DESC"
);
$haberlerStmt->execute(['kategori_id' => $kategori['id']]);
$haberler = $haberlerStmt->fetchAll();
?>

<section class="kategori-baslik">
    <h1><?= e($kategori['ad']) ?></h1>
    <p><?= count($haberler) ?> haber listeleniyor</p>
</section>

<section class="haber-grid">
    <?php if (empty($haberler)): ?>
        <p>Bu kategoride henüz haber bulunmuyor.</p>
    <?php endif; ?>

    <?php foreach ($haberler as $haber): ?>
        <article class="haber-karti">
                <?php if ($haber['resim']): ?>
                    <img class="haber-karti-gorsel" src="<?= UPLOAD_URL . e($haber['resim']) ?>" alt="<?= e($haber['baslik']) ?>">
                <?php else: ?>
                    <div class="haber-karti-gorsel-yer-tutucu"></div>
                <?php endif; ?>
                <div class="haber-karti-icerik">
            <div class="haber-karti-icerik">
                <h3><a href="haber.php?slug=<?= e($haber['slug']) ?>"><?= e($haber['baslik']) ?></a></h3>
                <p><?= e(metniKisalt($haber['ozet'], 110)) ?></p>
                <span class="haber-meta"><?= tarihFormatla($haber['olusturma_tarihi']) ?></span>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
