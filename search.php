<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = (new Database())->baglan();

$q = trim($_GET['q'] ?? '');

$haberler = [];
if ($q !== '') {
    $stmt = $db->prepare(
        "SELECT h.*, k.ad AS kategori_ad
         FROM haberler h
         JOIN kategoriler k ON k.id = h.kategori_id
         WHERE h.durum = 'yayinda' AND (h.baslik LIKE :arama OR h.ozet LIKE :arama2)
         ORDER BY h.olusturma_tarihi DESC"
    );
    $arama = '%' . $q . '%';
    $stmt->execute(['arama' => $arama, 'arama2' => $arama]);
    $haberler = $stmt->fetchAll();
}

$sayfaBasligi = 'Arama: ' . $q;
require_once __DIR__ . '/includes/header.php';
?>

<section class="kategori-baslik">
    <h1>"<?= e($q) ?>" için arama sonuçları</h1>
    <p><?= count($haberler) ?> haber bulundu</p>
</section>

<section class="haber-grid">
    <?php if ($q === ''): ?>
        <p>Aramak istediğin kelimeyi üstteki kutuya yazabilirsin.</p>
    <?php elseif (empty($haberler)): ?>
        <p>"<?= e($q) ?>" ile eşleşen bir haber bulunamadı.</p>
    <?php endif; ?>

    <?php foreach ($haberler as $haber): ?>
        <article class="haber-karti">
            <?php if ($haber['resim']): ?>
                <img class="haber-karti-gorsel" src="<?= UPLOAD_URL . e($haber['resim']) ?>" alt="<?= e($haber['baslik']) ?>">
            <?php else: ?>
                <div class="haber-karti-gorsel-yer-tutucu"></div>
            <?php endif; ?>
            <div class="haber-karti-icerik">
                <span class="kategori-etiket"><?= e($haber['kategori_ad']) ?></span>
                <h3><a href="haber.php?slug=<?= e($haber['slug']) ?>"><?= e($haber['baslik']) ?></a></h3>
                <p><?= e(metniKisalt($haber['ozet'], 110)) ?></p>
                <span class="haber-meta"><?= tarihFormatla($haber['olusturma_tarihi']) ?></span>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
