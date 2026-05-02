<?php
$pageTitle = 'Finans Hesap Makinesi - EfinanS';
$metaDescription = 'EfinanS finans hesap makineleriyle döviz çevirici, basit faiz, bileşik faiz ve kredi taksit hesaplamaları yapın.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">Hesap araçları</p>
    <h1>Finans hesap makineleri</h1>
    <p>Döviz çevirici, faiz hesaplama ve kredi taksit araçları tarayıcı içinde hızlıca sonuç üretir.</p>
</section>

<section class="section calculator-grid">
    <article class="tool-card">
        <h2>Döviz Çevirici</h2>
        <label for="fxAmount">Tutar</label>
        <input id="fxAmount" type="number" step="0.01" value="100">
        <label for="fxRate">Kur</label>
        <input id="fxRate" type="number" step="0.0001" value="32.48">
        <button class="primary-button full" type="button" data-calc="fx">Hesapla</button>
        <output id="fxResult">Sonuç bekleniyor</output>
    </article>

    <article class="tool-card">
        <h2>Basit Faiz</h2>
        <label for="simplePrincipal">Ana para</label>
        <input id="simplePrincipal" type="number" value="10000">
        <label for="simpleRate">Yıllık faiz (%)</label>
        <input id="simpleRate" type="number" value="35">
        <label for="simpleYears">Vade (yıl)</label>
        <input id="simpleYears" type="number" value="1">
        <button class="primary-button full" type="button" data-calc="simple">Hesapla</button>
        <output id="simpleResult">Sonuç bekleniyor</output>
    </article>

    <article class="tool-card">
        <h2>Bileşik Faiz</h2>
        <label for="compoundPrincipal">Ana para</label>
        <input id="compoundPrincipal" type="number" value="10000">
        <label for="compoundRate">Yıllık faiz (%)</label>
        <input id="compoundRate" type="number" value="35">
        <label for="compoundYears">Vade (yıl)</label>
        <input id="compoundYears" type="number" value="3">
        <label for="compoundTimes">Yıllık dönem</label>
        <input id="compoundTimes" type="number" value="12">
        <button class="primary-button full" type="button" data-calc="compound">Hesapla</button>
        <output id="compoundResult">Sonuç bekleniyor</output>
    </article>

    <article class="tool-card">
        <h2>Kredi Taksiti</h2>
        <label for="loanAmount">Kredi tutarı</label>
        <input id="loanAmount" type="number" value="250000">
        <label for="loanRate">Aylık faiz (%)</label>
        <input id="loanRate" type="number" step="0.01" value="3.49">
        <label for="loanMonths">Vade (ay)</label>
        <input id="loanMonths" type="number" value="24">
        <button class="primary-button full" type="button" data-calc="loan">Hesapla</button>
        <output id="loanResult">Sonuç bekleniyor</output>
    </article>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
