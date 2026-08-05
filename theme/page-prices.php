<?php
/**
 * Template Name: Страница «Цены»
 * Единая справочная страница по расценкам (без ссылок на блог — сознательно,
 * см. историю правок исходного сайта).
 */
if (!defined('ABSPATH')) exit;

get_header();

$d = rp_get_data('prices');

$hero_h1 = rp_field('hero_h1', $d['hero_h1'] ?? get_the_title());
$hero_lead = rp_field('hero_lead', $d['hero_lead'] ?? '');
$hero_image = rp_image_url('hero_image', rp_default_img($d['hero_image'] ?? ''));

$skolko_pcards = rp_repeater('skolko_pcards', $d['skolko_pcards'] ?? []);
$price_per_m2 = rp_repeater('price_per_m2', $d['price_per_m2'] ?? []);
$price_per_m2_extra = rp_repeater('price_per_m2_extra', $d['price_per_m2_extra'] ?? []);
$avg_cost = rp_repeater('avg_cost', $d['avg_cost'] ?? []);
$nedorogo_feat = rp_repeater('nedorogo_feat', $d['nedorogo_feat'] ?? []);
$pod_klyuch = rp_repeater('pod_klyuch', $d['pod_klyuch'] ?? []);
$faq = rp_repeater('faq', $d['faq'] ?? []);
$seo_text = rp_field('seo_text', $d['seo_text'] ?? '');
?>

<!-- ============ PAGE HERO ============ -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Хлебные крошки">
      <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span class="breadcrumb__sep">/</span>
      <span class="breadcrumb__current"><?php echo esc_html($hero_h1); ?></span>
    </nav>
    <div class="page-hero__inner">
      <div class="page-hero__text reveal">
        <h1 class="h-heavy page-hero__title"><?php echo esc_html($hero_h1); ?></h1>
        <p class="page-hero__lead"><?php echo esc_html($hero_lead); ?></p>
        <a class="btn btn--primary btn--lg" href="<?php echo esc_url(home_url('/#contacts')); ?>">Заказать ремонт</a>
        <?php echo do_shortcode('[hero_trust]'); ?>
      </div>
      <div class="ph page-hero__media reveal" data-bg="<?php echo esc_url($hero_image); ?>" data-label="Фото · 900×620" aria-hidden="true"></div>
    </div>
  </div>
</section>

<?php echo do_shortcode('[stats_block]'); ?>

<!-- ============ KALKULYATOR ============ -->
<div id="kalkulyator"></div>
<?php echo do_shortcode('[quiz_calculator]'); ?>

<!-- ============ SKOLKO STOIT ============ -->
<section class="pricing" id="skolko-stoit">
  <div class="container">
    <h2 class="h-heavy pricing__title">Сколько стоит <span class="accent">ремонт квартиры</span></h2>
    <div class="price-grid">
      <?php rp_render_pricing_cards($skolko_pcards); ?>
    </div>
    <div class="price-extra" style="grid-template-columns:1fr">
      <div class="price-note reveal">
        <p>Это ориентировочные тарифы по уровню отделки. Ниже — расценки по конкретным видам услуг за м² и типовые суммы по площади квартиры.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ CENA ZA M2 ============ -->
<section class="article" id="cena-za-m2">
  <div class="container container--narrow">
    <h2 class="h-heavy pricing__title">Цена за <span class="accent">1 м²</span></h2>
    <p style="color:var(--ink-soft);margin-bottom:20px">Расценки по видам работ — от самой доступной до премиальной услуги. Нажмите на строку, чтобы открыть подробную страницу услуги с полным составом работ.</p>
    <div class="price-table">
      <?php rp_render_price_table_rows($price_per_m2); ?>
    </div>
    <p style="color:var(--ink-soft);margin:20px 0 8px;font-size:14px">Точечные услуги считаются не за м², а за помещение целиком:</p>
    <div class="price-table">
      <?php rp_render_price_table_rows($price_per_m2_extra); ?>
    </div>
  </div>
</section>

<!-- ============ SREDNYAYA STOIMOST ============ -->
<section class="article" id="srednyaya-stoimost">
  <div class="container container--narrow">
    <h2 class="h-heavy pricing__title">Средняя <span class="accent">стоимость</span></h2>
    <p style="color:var(--ink-soft);margin-bottom:20px">Ориентировочная вилка «от эконом-класса до люкс» для квартир разной площади — от косметического ремонта (от 4 900 ₽/м²) до эксклюзивного дизайнерского (от 25 000 ₽/м²).</p>
    <div class="price-table">
      <?php rp_render_price_table_rows($avg_cost, false); ?>
    </div>
  </div>
</section>

<!-- ============ NEDOROGO ============ -->
<section class="features" id="nedorogo">
  <div class="container">
    <h2 class="h-heavy pricing__title" style="margin-bottom:20px">Недорогой <span class="accent">ремонт</span></h2>
    <div class="features__grid">
      <?php rp_render_features($nedorogo_feat); ?>
    </div>
  </div>
</section>

<!-- ============ POD KLYUCH ============ -->
<section class="pricing" id="pod-klyuch">
  <div class="container">
    <h2 class="h-heavy pricing__title">Стоимость ремонта <span class="accent">под ключ</span></h2>
    <div class="price-grid price-grid--3">
      <?php rp_render_pricing_cards($pod_klyuch); ?>
    </div>
    <div class="price-extra" style="grid-template-columns:1fr">
      <div class="price-note reveal">
        <p>Подробнее о том, что входит в ремонт под ключ на каждом этапе — на странице услуги.</p>
        <a class="btn btn--dark btn--lg" href="<?php echo esc_url(rp_page_url('remont-pod-klyuch-v-moskve')); ?>">Смотреть услугу «Под ключ»</a>
      </div>
    </div>
  </div>
</section>

<?php echo do_shortcode('[promo_banner eyebrow="Назовём точную цену после замера"]'); ?>

<!-- ============ FAQ ============ -->
<section class="faq2">
  <div class="container">
    <h2 class="h-heavy faq2__title">Частые вопросы о ценах</h2>
    <div class="faq2__list">
      <?php rp_render_faq($faq); ?>
    </div>
  </div>
</section>

<?php if ($seo_text): ?>
<!-- ============ SEO TEXT ============ -->
<section class="article">
  <div class="container container--narrow">
    <div class="prose reveal">
      <?php echo wp_kses_post($seo_text); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
