<?php
require_once __DIR__ . '/../includes/auth.php';

$kategoriler = $db->query("SELECT id, ad FROM kategoriler ORDER BY ad ASC")->fetchAll();

$hata = '';
$basari = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategoriId = (int) ($_POST['kategori_id'] ?? 0);
    $baslik = trim($_POST['baslik'] ?? '');
    $ozet = trim($_POST['ozet'] ?? '');
    $icerik = trim($_POST['icerik'] ?? '');
    $durum = ($_POST['durum'] ?? 'taslak') === 'yayinda' ? 'yayinda' : 'taslak';

    if ($kategoriId === 0 || $baslik === '' || $ozet === '' || $icerik === '') {
        $hata = 'Lütfen kategori, başlık, özet ve içerik alanlarını doldur.';
    } else {
        try {
            $resimAdi = resimYukle($_FILES['resim'] ?? []);

            $slug = slugOlustur($baslik);

            // Aynı slug zaten varsa sonuna sayı ekleyerek benzersiz hale getir
            $kontrolStmt = $db->prepare("SELECT COUNT(*) AS adet FROM haberler WHERE slug = :slug");
            $orijinalSlug = $slug;
            $sayac = 1;
            do {
                $kontrolStmt->execute(['slug' => $slug]);
                $varMi = $kontrolStmt->fetch()['adet'] > 0;
                if ($varMi) {
                    $slug = $orijinalSlug . '-' . (++$sayac);
                }
            } while ($varMi);

            $ekleStmt = $db->prepare(
                "INSERT INTO haberler (kategori_id, baslik, slug, ozet, icerik, resim, durum)
                 VALUES (:kategori_id, :baslik, :slug, :ozet, :icerik, :resim, :durum)"
            );
            $ekleStmt->execute([
                'kategori_id' => $kategoriId,
                'baslik' => $baslik,
                'slug' => $slug,
                'ozet' => $ozet,
                'icerik' => $icerik,
                'resim' => $resimAdi,
                'durum' => $durum,
            ]);

            header('Location: haberler.php');
            exit;
        } catch (RuntimeException $e) {
            $hata = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Haber Ekle</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="konteyner" style="max-width:700px; padding-top:40px;">
    <p><a href="index.php">← Panele dön</a></p>
    <h1>Yeni Haber Ekle</h1>

    <?php if ($hata): ?>
        <p style="color:red;"><?= e($hata) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <p>
            <label>Kategori<br>
                <select name="kategori_id" style="width:100%; padding:8px;" required>
                    <option value="">-- Seç --</option>
                    <?php foreach ($kategoriler as $kat): ?>
                        <option value="<?= $kat['id'] ?>"><?= e($kat['ad']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p>
            <label>Başlık<br>
                <input type="text" name="baslik" style="width:100%; padding:8px;" required>
            </label>
        </p>
        <p>
            <label>Özet<br>
                <textarea name="ozet" rows="2" style="width:100%; padding:8px;" required></textarea>
            </label>
        </p>
        <p>
            <label>İçerik<br>
                <textarea name="icerik" rows="8" style="width:100%; padding:8px;" required></textarea>
            </label>
        </p>
        <p>
            <label>Görsel (opsiyonel)<br>
                <input type="file" name="resim" accept=".jpg,.jpeg,.png,.webp">
            </label>
        </p>
        <p>
            <label>Durum<br>
                <select name="durum" style="width:100%; padding:8px;">
                    <option value="taslak">Taslak</option>
                    <option value="yayinda">Yayınla</option>
                </select>
            </label>
        </p>
        <button type="submit" style="padding:10px 24px;">Kaydet</button>
    </form>
</div>
</body>
</html>
