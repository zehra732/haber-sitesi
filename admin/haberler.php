<?php
require_once __DIR__ . '/../includes/auth.php';

$haberler = $db->query(
    "SELECT h.id, h.baslik, h.durum, h.olusturma_tarihi, k.ad AS kategori_ad
     FROM haberler h
     JOIN kategoriler k ON k.id = h.kategori_id
     ORDER BY h.olusturma_tarihi DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Haberler</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="konteyner" style="padding-top:40px;">
    <p><a href="index.php">← Panele dön</a></p>
    <h1>Haberler</h1>
    <p><a href="haber_ekle.php">+ Yeni Haber Ekle</a></p>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
        <tr style="text-align:left; border-bottom:2px solid #ccc;">
            <th style="padding:8px;">Başlık</th>
            <th style="padding:8px;">Kategori</th>
            <th style="padding:8px;">Durum</th>
            <th style="padding:8px;">Tarih</th>
            <th style="padding:8px;">İşlemler</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($haberler as $haber): ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:8px;"><?= e($haber['baslik']) ?></td>
                <td style="padding:8px;"><?= e($haber['kategori_ad']) ?></td>
                <td style="padding:8px;"><?= $haber['durum'] === 'yayinda' ? 'Yayında' : 'Taslak' ?></td>
                <td style="padding:8px;"><?= tarihFormatla($haber['olusturma_tarihi']) ?></td>
                <td style="padding:8px;">
                    <a href="haber_duzenle.php?id=<?= (int) $haber['id'] ?>">Düzenle</a> |
                    <a href="haber_sil.php?id=<?= (int) $haber['id'] ?>" onclick="return confirm('Emin misin?');">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($haberler)): ?>
            <tr><td colspan="5" style="padding:8px;">Henüz haber eklenmemiş.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
