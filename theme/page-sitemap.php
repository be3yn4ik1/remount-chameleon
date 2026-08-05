<?php
/**
 * Template Name: Карта сайта
 * Разделы «Услуги», «Типы квартир», «Компания», «Цены» — по фиксированному списку
 * (порядок как в меню). Раздел «Блог» — все опубликованные записи через WP_Query.
 */
if (!defined('ABSPATH')) exit;

get_header();

function rp_sitemap_links($slugs) {
    foreach ($slugs as $slug => $label) {
        $page = get_page_by_path($slug);
        if (!$page) continue;
        echo '<li><a href="' . esc_url(get_permalink($page)) . '">' . esc_html($label) . '</a></li>';
    }
}

$uslugi = [
    'remont-pod-klyuch-v-moskve' => 'Ремонт под ключ в Москве',
    'kosmeticheskiy-remont-kvartiry' => 'Косметический ремонт квартиры',
    'kapitalnyy-remont-kvartiry' => 'Капитальный ремонт квартиры',
    'dizaynerskiy-remont-kvartiry' => 'Дизайнерский ремонт квартиры',
    'otdelka-kvartir-v-moskve' => 'Отделка квартир',
    'sovremennyy-remont-kvartiry' => 'Современный ремонт квартиры',
    'dizayn-i-remont-kvartir-v-moskve' => 'Дизайн и ремонт квартир',
    'remont-elitnyh-kvartir' => 'Ремонт элитных квартир',
    'remont-vanny-v-kvartire' => 'Ремонт ванной',
    'remont-koridora-v-kvartire' => 'Ремонт коридора',
    'uslugi-remonta-kvartir' => 'Все услуги',
];
$tipy = [
    'remont-odnokomnatnoy-kvartiry' => 'Ремонт однокомнатной квартиры',
    'remont-dvuhkomnatnoy-kvartiry' => 'Ремонт двухкомнатной квартиры',
    'remont-kvartiry-studii' => 'Ремонт квартиры-студии',
    'remont-trehkomnatnoy-kvartiry' => 'Ремонт трёхкомнатной квартиры',
    'remont-4-komnatnoy-kvartiry' => 'Ремонт четырёхкомнатной квартиры',
    'remont-kvartiry-v-novostroyke' => 'Ремонт квартиры в новостройке',
    'remont-vtorichki-v-moskve' => 'Ремонт вторичного жилья',
];
$kompaniya = [
    'o-kompanii' => 'О компании',
    'otzyvy-o-remonte-kvartir' => 'Отзывы клиентов',
    'voprosy-i-otvety' => 'Вопросы и ответы',
];
$ceny = ['ceny-remonta-kvartiry' => 'Все цены на ремонт'];
?>

<!-- ============ PAGE HERO ============ -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Хлебные крошки">
      <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span class="breadcrumb__sep">/</span>
      <span class="breadcrumb__current">Карта сайта</span>
    </nav>
    <div class="page-hero__text reveal">
      <h1 class="h-heavy page-hero__title">Карта сайта</h1>
      <p class="page-hero__lead">Все опубликованные страницы «РемонтПрофи»: услуги, типы квартир, цены и блог о ремонте квартир в Москве.</p>
    </div>
  </div>
</section>

<section class="sitemap">
  <div class="container">
    <div class="sitemap-grid">

      <div class="sitemap-col reveal">
        <span class="sitemap-col__title">Главная</span>
        <ul><li><a class="is-home" href="<?php echo esc_url(home_url('/')); ?>">Ремонт квартир под ключ в Москве</a></li></ul>
      </div>

      <div class="sitemap-col reveal">
        <span class="sitemap-col__title">Компания</span>
        <ul><?php rp_sitemap_links($kompaniya); ?></ul>
      </div>

      <div class="sitemap-col reveal">
        <span class="sitemap-col__title">Услуги</span>
        <ul><?php rp_sitemap_links($uslugi); ?></ul>
      </div>

      <div class="sitemap-col reveal">
        <span class="sitemap-col__title">Типы квартир</span>
        <ul><?php rp_sitemap_links($tipy); ?></ul>
      </div>

      <div class="sitemap-col reveal">
        <span class="sitemap-col__title">Цены</span>
        <ul><?php rp_sitemap_links($ceny); ?></ul>
      </div>

      <div class="sitemap-col reveal">
        <span class="sitemap-col__title">Блог</span>
        <ul>
          <li><a href="<?php echo esc_url(rp_blog_url()); ?>">Все статьи</a></li>
          <?php
          $q = new WP_Query(['post_type' => 'post', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'no_found_rows' => true]);
          while ($q->have_posts()): $q->the_post();
              echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
          endwhile;
          wp_reset_postdata();
          ?>
        </ul>
      </div>

    </div>
  </div>
</section>

<?php get_footer(); ?>
