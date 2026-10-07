<?php
require_once __DIR__ . '/includes/header.php';
$enCokOkunanlarStmt = $db->prepare(
        "SELECT baslik, slug, goruntulenme
     FROM haberler
     WHERE durum = 'yayinda' AND olusturma_tarihi >= DATE_SUB(NOW(), INTERVAL 7 DAY)
     ORDER BY goruntulenme DESC
     LIMIT 5"
);
$enCokOkunanlarStmt->execute();
$enCokOkunanlar = $enCokOkunanlarStmt->fetchAll();
$haberlerStmt = $db->prepare(
    "SELECT h.*, k.ad AS kategori_ad, k.slug AS kategori_slug
     FROM haberler h
     JOIN kategoriler k ON k.id = h.kategori_id
     WHERE h.durum = 'yayinda'
     ORDER BY h.olusturma_tarihi DESC"
);
$haberlerStmt->execute();
$haberler = $haberlerStmt->fetchAll();
?>
<?php if (!empty($enCokOkunanlar)): ?>
    <aside class="en-cok-okunan-kutu">
        <h2>Bu Hafta En Çok Okunanlar</h2>
        <ol>
            <?php foreach ($enCokOkunanlar as $i => $h): ?>
                <li>
                    <span class="siralama-no"><?= $i + 1 ?></span>
                    <a href="haber.php?slug=<?= e($h['slug']) ?>"><?= e($h['baslik']) ?></a>
                    <span class="goruntulenme-sayi"><?= (int) $h['goruntulenme'] ?> görüntülenme</span>
                </li>
            <?php endforeach; ?>
        </ol>
    </aside>
<?php endif; ?>
    <section class="haber-listesi-baslik">
        <h2>Son Dakika Haberleri</h2>
    </section>

    <section class="haber-grid">
        <?php if (empty($haberler)): ?>
            <p>Henüz yayınlanmış bir haber bulunmuyor.</p>
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