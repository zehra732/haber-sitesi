
</main>
<?php
$sssListesi = [
        'Bu sitede hangi kategoriler var?' =>
                'Şu an sitemizde şu kategoriler bulunuyor: ' . implode(', ', array_map(fn($k) => $k['ad'], $tumKategoriler)) . '. Üst menüden istediğin kategoriye tıklayarak o kategoriye ait haberleri görebilirsin.',

        'En güncel haberlere nasıl ulaşırım?' =>
                'Anasayfada en yeni haberler en üstte listelenir. Ayrıca sayfanın en üstündeki kırmızı "Son Dakika" şeridinde en son eklenen haberler kayarak görüntülenir.',

        '"Görüntülenme" sayısı ne anlama geliyor?' =>
                'Bir haberin kaç kez açıldığını gösteren sayaçtır. Bir haberi her açtığında bu sayı bir artar.',

        'Haberlere yorum yapabilir miyim?' =>
                'Şu an için sitede yorum sistemi bulunmuyor, sadece haberleri okuyabilirsin.',

        'Siteye üye olmam gerekiyor mu?' =>
                'Hayır, haberleri okumak için herhangi bir üyelik veya giriş gerekmiyor, site tamamen herkese açık.',

        'Bu site kim tarafından yönetiliyor?' =>
                'Bu site bir staj/portfolyo projesi olarak PHP ve MySQL kullanılarak geliştirilmiştir.',
];
?>
<button class="sss-widget-buton" onclick="sssPanelAc()">💬</button>

<div class="sss-widget-panel" id="sssPanel">
    <div class="sss-widget-baslik">
        <span>Yardımcı</span>
        <button onclick="sssPanelKapat()">✕</button>
    </div>
    <div class="sss-widget-govde" id="sssGovde">
        <?php foreach ($sssListesi as $soru => $cevap): ?>
            <button class="sss-soru-btn" onclick="sssCevapGoster('<?= e(addslashes($soru)) ?>')"><?= e($soru) ?></button>
        <?php endforeach; ?>
    </div>
</div>

<script>
    const sssVeri = <?= json_encode($sssListesi, JSON_UNESCAPED_UNICODE) ?>;

    function sssPanelAc() {
        document.getElementById('sssPanel').classList.add('acik');
    }

    function sssPanelKapat() {
        document.getElementById('sssPanel').classList.remove('acik');
    }

    function sssSorulariGoster() {
        const govde = document.getElementById('sssGovde');
        govde.innerHTML = '';
        for (const soru in sssVeri) {
            const btn = document.createElement('button');
            btn.className = 'sss-soru-btn';
            btn.textContent = soru;
            btn.onclick = () => sssCevapGoster(soru);
            govde.appendChild(btn);
        }
    }

    function sssCevapGoster(soru) {
        const govde = document.getElementById('sssGovde');
        govde.innerHTML = `
        <span class="sss-cevap-geri" onclick="sssSorulariGoster()">← Sorulara dön</span>
        <p class="sss-cevap-metin"><strong>${soru}</strong></p>
        <p class="sss-cevap-metin">${sssVeri[soru]}</p>
    `;
    }
</script>
<footer class="site-footer">
    <div class="konteyner">
        &copy; <?= date('Y') ?> <?= SITE_ADI ?>. Tüm hakları saklıdır.
    </div>
    <small style="opacity: 0.7;">
        <strong>Yasal Uyarı:</strong> Bu sitedeki haberler ve görseller yazılım portföy çalışması amacıyla kurgulanmıştır. Gerçeği yansıtmamaktadır.
    </small>
</footer>
</body>
</html>
