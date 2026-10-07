<?php
require_once __DIR__ . '/../includes/auth.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    // Önce haberin görselini diskten de silelim ki gereksiz dosya birikmesin
    $stmt = $db->prepare("SELECT resim FROM haberler WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $haber = $stmt->fetch();

    if ($haber && $haber['resim']) {
        $dosyaYolu = UPLOAD_DIR . $haber['resim'];
        if (file_exists($dosyaYolu)) {
            unlink($dosyaYolu);
        }
    }

    $db->prepare("DELETE FROM haberler WHERE id = :id")->execute(['id' => $id]);
}

header('Location: haberler.php');
exit;
