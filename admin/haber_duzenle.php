<?php
require_once __DIR__ . '/../includes/auth.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM haberler WHERE id = :id");
$stmt->execute(['id' => $id]);
$haber = $stmt->fetch();

if (!$haber) {
    header('Location: haberler.php');
    exit;
}

$kategoriler = $db->query("SELECT id, ad FROM kategoriler ORDER BY ad ASC")->fetchAll();

$hata = '';

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
            $yeniResim = resimYukle($_FILES['resim'] ?? []);

            if ($yeniResim) {
                // Yeni görsel yüklendiyse, eskisini diskten sil
                if ($haber['resim']) {
                    $eskiYol = UPLOAD_DIR . $haber['resim'];
                    if (file_exists($eskiYol)) {
                        unlink($eskiYol);
                    }
                }
                $resimAdi = $yeniResim;
            } else {
                // Yeni görsel yüklenmediyse, eskisini koru
                $resimAdi = $haber['resim'];
            }

            $guncelleStmt = $db->prepare(
                "UPDATE haberler
                 SET kategori_id = :kategori_id, baslik = :baslik, ozet = :ozet,
                     icerik = :icerik, resim = :resim, durum = :durum
                 WHERE id = :id"
            );
            $guncelleStmt->execute([
                'kategori_id' => $kategoriId,
                'baslik' => $baslik,
                'ozet' => $ozet,
                'icerik' => $icerik,
                'resim' => $resimAdi,
                'durum' => $durum,
                'id' => $id,
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
    <title>Haberi Düzenle</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="konteyner" style="max-width:700px; padding-top:40px;">
    <p><a href="haberler.php">← Haberlere dön</a></p>
    <h1>Haberi Düzenle</h1>

    <?php if ($hata): ?>
        <p style="color:red;"><?= e($hata) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <p>
            <label>Kategori<br>
                <select name="kategori_id" style="width:100%; padding:8px;" required>
                    <?php foreach ($kategoriler as $kat): ?>
                        <option value="<?= $kat['id'] ?>" <?= $kat['id'] == $haber['kategori_id'] ? 'selected' : '' ?>>
                            <?= e($kat['ad']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p>
            <label>Başlık<br>
                <input type="text" name="baslik" value="<?= e($haber['baslik']) ?>" style="width:100%; padding:8px;" required>
            </label>
        </p>
        <p>
            <label>Özet<br>
                <textarea name="ozet" rows="2" style="width:100%; padding:8px;" required><?= e($haber['ozet']) ?></textarea>
            </label>
        </p>
        <p>
            <label>İçerik<br>
                <textarea name="icerik" rows="8" style="width:100%; padding:8px;" required><?= e($haber['icerik']) ?></textarea>
            </label>
        </p>
        <?php if ($haber['resim']): ?>
            <p>
                Mevcut görsel:<br>
                <img src="<?= UPLOAD_URL . e($haber['resim']) ?>" style="max-width:200px; margin-top:6px;">
            </p>
        <?php endif; ?>
        <p>
            <label>Yeni görsel yükle (opsiyonel, değiştirmek istemiyorsan boş bırak)<br>
                <input type="file" name="resim" accept=".jpg,.jpeg,.png,.webp">
            </label>
        </p>
        <p>
            <label>Durum<br>
                <select name="durum" style="width:100%; padding:8px;">
                    <option value="taslak" <?= $haber['durum'] === 'taslak' ? 'selected' : '' ?>>Taslak</option>
                    <option value="yayinda" <?= $haber['durum'] === 'yayinda' ? 'selected' : '' ?>>Yayınla</option>
                </select>
            </label>
        </p>
        <button type="submit" style="padding:10px 24px;">Güncelle</button>
    </form>
</div>
</body>
</html>
