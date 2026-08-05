<?php
/**
 * Template Name: Посадочная — услуга / тип квартиры
 * Используется для всех страниц раздела «Услуги» и «Типы квартир» (18 страниц).
 * Контент — из ACF (если заполнено) с откатом на данные по умолчанию из
 * inc/data/landing-pages.php (перенесены из готового сайта).
 */
if (!defined('ABSPATH')) exit;

get_header();

$d = rp_landing_defaults();

$hero_h1 = rp_field('hero_h1', $d['hero_h1'] ?? get_the_title());
$hero_lead = rp_field('hero_lead', $d['hero_lead'] ?? '');
$hero_image = rp_image_url('hero_image', rp_default_img($d['hero_image'] ?? ''));

$features = rp_repeater('features', $d['features'] ?? []);
$included_default = array_map(function ($t) { return ['text' => $t]; }, $d['included'] ?? []);
$included = rp_repeater('included', $included_default);

$process_title = rp_field('process_title', $d['process_title'] ?? 'Как проходит ремонт');
$steps = rp_repeater('steps', $d['steps'] ?? []);
$process_image = rp_image_url('process_image', rp_default_img($d['process_image'] ?? ''));

$pricing_title = rp_field('pricing_title', $d['pricing_title'] ?? 'Цены');
$pricing_cards = rp_repeater('pricing_cards', $d['pricing_cards'] ?? []);
$ncards = count($pricing_cards);

$faq = rp_repeater('faq', $d['faq'] ?? []);

$blog_default = $d['blog_links'] ?? [];
$blog_links = rp_repeater('blog_links', $blog_default);

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

<!-- ============ FEATURES ============ -->
<section class="features">
  <div class="container features__grid">
    <?php rp_render_features($features); ?>
  </div>
</section>

<?php echo do_shortcode('[stats_block]'); ?>

<!-- ============ WHAT'S INCLUDED ============ -->
<section class="article">
  <div class="container container--narrow">
    <div class="prose reveal">
    <h2>Что входит в услугу</h2>
    <ul>
      <?php rp_render_included_list($included); ?>
    </ul>
    </div>
  </div>
</section>

<!-- ============ PROCESS (dark) ============ -->
<section class="dark-block dark-block--process">
  <div class="container">
    <h2 class="h-heavy h-heavy--light process__title"><?php echo esc_html($process_title); ?></h2>
    <div class="process__wrap">
      <ol class="steps2">
        <?php rp_render_steps($steps); ?>
      </ol>
      <div class="ph process__media" data-bg="<?php echo esc_url($process_image); ?>" data-label="Фото · 520×520"></div>
    </div>
  </div>
</section>

<!-- ============ PRICING ============ -->
<section class="pricing">
  <div class="container">
    <h2 class="h-heavy pricing__title"><?php echo esc_html($pricing_title); ?> <span class="accent">в Москве</span></h2>
    <div class="price-grid price-grid--<?php echo esc_attr($ncards); ?>">
      <?php rp_render_pricing_cards($pricing_cards); ?>
    </div>
    <div class="price-extra" style="grid-template-columns:1fr">
      <div class="price-note reveal">
        <p>Цены примерные и актуальны для Москвы. Точную смету мастер подтвердит после бесплатного замера — стоимость фиксируется в договоре и не меняется в процессе работ.</p>
        <a class="btn btn--dark btn--lg" href="#calc">Рассчитать мой ремонт</a>
      </div>
    </div>
  </div>
</section>

<?php echo do_shortcode('[quiz_calculator]'); ?>

<?php echo do_shortcode('[promo_banner]'); ?>

<!-- ============ FAQ ============ -->
<section class="faq2">
  <div class="container">
    <h2 class="h-heavy faq2__title">Частые вопросы</h2>
    <div class="faq2__list">
      <?php rp_render_faq($faq); ?>
    </div>
  </div>
</section>

<!-- ============ RELATED BLOG POSTS ============ -->
<?php if (!empty($blog_links)): ?>
<section class="blog">
  <div class="container">
    <h2 class="h-heavy pricing__title">Читайте в <span class="accent">блоге</span></h2>
    <div class="blog-grid">
      <?php rp_render_blog_cards($blog_links); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ SEO TEXT ============ -->
<?php if ($seo_text): ?>
<section class="article">
  <div class="container container--narrow">
    <div class="prose reveal">
      <?php echo wp_kses_post($seo_text); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
