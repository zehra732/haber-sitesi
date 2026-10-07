<?php
function e(?string $deger): string
{
    return htmlspecialchars($deger ?? '', ENT_QUOTES, 'UTF-8');
}
function tarihFormatla(string $tarih): string
{
    $aylar = [
        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan', 5 => 'Mayıs', 6 => 'Haziran',
        7 => 'Temmuz', 8 => 'Ağustos', 9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
    ];
    $zaman = strtotime($tarih);
    $gun = date('d', $zaman);
    $ay = $aylar[(int) date('n', $zaman)];
    $yil = date('Y', $zaman);
    $saat = date('H:i', $zaman);
    return "{$gun} {$ay} {$yil}, {$saat}";
}
function metniKisalt(string $metin, int $uzunluk = 150): string
{
    if (mb_strlen($metin) <= $uzunluk) {
        return $metin;
    }
    return mb_substr($metin, 0, $uzunluk) . '...';
}
function slugOlustur(string $metin): string
{
    $harfler = ['ç','Ç','ğ','Ğ','ı','İ','ö','Ö','ş','Ş','ü','Ü'];
    $karsilik = ['c','c','g','g','i','i','o','o','s','s','u','u'];
    $metin = str_replace($harfler, $karsilik, $metin);
    $metin = strtolower($metin);
    $metin = preg_replace('/[^a-z0-9]+/', '-', $metin);
    return trim($metin, '-');
}
function resimYukle(array $dosya): ?string
{
    if (empty($dosya['name']) || $dosya['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp'];
    $uzanti = strtolower(pathinfo($dosya['name'], PATHINFO_EXTENSION));

    if (!in_array($uzanti, $izinliUzantilar, true)) {
        throw new RuntimeException('Sadece JPG, PNG veya WEBP formatında görsel yükleyebilirsin.');
    }

    if ($dosya['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Görsel boyutu 5 MB\'ı geçemez.');
    }

    $yeniAd = uniqid('haber_', true) . '.' . $uzanti;
    $hedefYol = UPLOAD_DIR . $yeniAd;

    if (!move_uploaded_file($dosya['tmp_name'], $hedefYol)) {
        throw new RuntimeException('Görsel yüklenirken bir hata oluştu.');
    }

    return $yeniAd;
}
