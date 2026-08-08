<?php
/**
 * Template Name: Страница «О компании»
 * Четыре раздела-якоря: о-компании, мастера, без-посредников, проф.подход.
 */
if (!defined('ABSPATH')) exit;

get_header();

$d = rp_get_data('about');

$hero_h1 = rp_field('hero_h1', $d['hero_h1'] ?? get_the_title());
$hero_lead = rp_field('hero_lead', $d['hero_lead'] ?? '');
$hero_image = rp_image_url('hero_image', rp_default_img($d['hero_image'] ?? ''));

$o_text = rp_field('o_kompanii_text', $d['o_kompanii_text'] ?? '');
$features = rp_repeater('features', $d['features'] ?? []);
$mastera_text = rp_field('mastera_text', $d['mastera_text'] ?? '');
$bez_steps = rp_repeater('bez_posrednikov_steps', $d['bez_posrednikov_steps'] ?? []);
$process_image = rp_image_url('process_image', rp_default_img('2151037570.jpg'));
$pro_text = rp_field('professionalnyy_podhod_text', $d['professionalnyy_podhod_text'] ?? '');
$faq = rp_repeater('faq', $d['faq'] ?? []);

$blog_links = [
    ['slug' => 'kompaniya-po-remontu-kvartir', 'image' => '2488.jpg', 'title' => 'Компания по ремонту квартир', 'excerpt' => 'Основные критерии выбора надёжного подрядчика и типичные ошибки при выборе компании.'],
    ['slug' => 'master-po-remontu-kvartir', 'image' => '28044.jpg', 'title' => 'Мастер по ремонту квартир', 'excerpt' => 'Кто работает над вашим проектом: квалификация и специализация мастеров «РемонтПрофи».'],
    ['slug' => 'remont-kvartiry-bez-posrednikov', 'image' => '2151037570.jpg', 'title' => 'Ремонт квартиры без посредников', 'excerpt' => 'Почему собственная команда мастеров без субподряда — это контроль качества, сроков и бюджета.'],
];
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

<!-- ============ О КОМПАНИИ ============ -->
<section class="article" id="o-kompanii">
  <div class="container container--narrow">
    <div class="prose reveal">
    <h2>О компании</h2>
    <?php echo wp_kses_post($o_text); ?>
    </div>
  </div>
</section>

<!-- ============ FEATURES ============ -->
<section class="features">
  <div class="container features__grid">
    <?php rp_render_features($features); ?>
  </div>
</section>

<!-- ============ НАШИ МАСТЕРА ============ -->
<section class="article" id="mastera">
  <div class="container container--narrow">
    <div class="prose reveal">
    <h2>Наши мастера</h2>
    <?php echo wp_kses_post($mastera_text); ?>
    </div>
  </div>
</section>

<!-- ============ БЕЗ ПОСРЕДНИКОВ ============ -->
<section class="dark-block dark-block--process" id="bez-posrednikov">
  <div class="container">
    <h2 class="h-heavy h-heavy--light process__title">Без посредников</h2>
    <div class="process__wrap">
      <ol class="steps2">
        <?php rp_render_steps($bez_steps); ?>
      </ol>
      <div class="ph process__media" data-bg="<?php echo esc_url($process_image); ?>" data-label="Фото · 520×520"></div>
    </div>
  </div>
</section>

<!-- ============ ПРОФЕССИОНАЛЬНЫЙ ПОДХОД ============ -->
<section class="article" id="professionalnyy-podhod">
  <div class="container container--narrow">
    <div class="prose reveal">
    <h2>Профессиональный подход</h2>
    <?php echo wp_kses_post($pro_text); ?>
    </div>
  </div>
</section>

<?php echo do_shortcode('[quiz_calculator]'); ?>

<?php echo do_shortcode('[promo_banner eyebrow="Работаем по договору с гарантией 3 года"]'); ?>

<!-- ============ FAQ ============ -->
<section class="faq2">
  <div class="container">
    <h2 class="h-heavy faq2__title">Частые вопросы о компании</h2>
    <div class="faq2__list">
      <?php rp_render_faq($faq); ?>
    </div>
  </div>
</section>

<!-- ============ RELATED BLOG POSTS ============ -->
<section class="blog">
  <div class="container">
    <h2 class="h-heavy pricing__title">Читайте в <span class="accent">блоге</span></h2>
    <div class="blog-grid">
      <?php rp_render_blog_cards($blog_links); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
