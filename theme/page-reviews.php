<?php
/**
 * Template Name: Страница «Отзывы»
 * Разметка schema.org (LocalBusiness + AggregateRating + Review) собирается
 * автоматически из тех же данных, что выводятся на странице (ACF или дефолт).
 */
if (!defined('ABSPATH')) exit;

get_header();

$d = rp_get_data('reviews');

$hero_h1 = rp_field('hero_h1', $d['hero_h1'] ?? get_the_title());
$hero_lead = rp_field('hero_lead', $d['hero_lead'] ?? '');
$hero_image = rp_image_url('hero_image', rp_default_img($d['hero_image'] ?? ''));
$reviews = rp_repeater('reviews', $d['reviews'] ?? []);
$faq = rp_repeater('faq', $d['faq'] ?? []);

$rating_value = rp_option('rating_value', '4.9');
$reviews_count = rp_option('reviews_count', '500+');

$blog_links = [
    ['slug' => 'kompaniya-po-remontu-kvartir', 'image' => '2488.jpg', 'title' => 'Компания по ремонту квартир', 'excerpt' => 'Основные критерии выбора надёжного подрядчика и типичные ошибки при выборе компании.'],
    ['slug' => 'master-po-remontu-kvartir', 'image' => '28044.jpg', 'title' => 'Мастер по ремонту квартир', 'excerpt' => 'Кто работает над вашим проектом: квалификация и специализация мастеров «РемонтПрофи».'],
    ['slug' => 'professionalnyy-remont-kvartir', 'image' => '2150771507.jpg', 'title' => 'Профессиональный ремонт квартир', 'excerpt' => 'Комплексный подход под ключ, гарантия качества и команда опытных специалистов.'],
];
?>

<?php
// schema.org — собирается из тех же данных, что отображаются на странице.
$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'HomeAndConstructionBusiness',
    'name' => get_bloginfo('name') ?: 'РемонтПрофи',
    'telephone' => rp_option('phone', '+7 (977) 922-07-18'),
    'email' => rp_option('email', 'remont-proofi@ya.ru'),
    'areaServed' => 'Москва',
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => $rating_value,
        'reviewCount' => preg_replace('/\D/', '', $reviews_count) ?: '500',
        'bestRating' => '5',
        'worstRating' => '1',
    ],
    'review' => array_map(function ($r) {
        return [
            '@type' => 'Review',
            'author' => ['@type' => 'Person', 'name' => $r['name'] ?? ''],
            'reviewRating' => ['@type' => 'Rating', 'ratingValue' => (string) ($r['rating'] ?? 5), 'bestRating' => '5'],
            'reviewBody' => $r['text'] ?? '',
        ];
    }, $reviews),
];
add_action('wp_head', function () use ($schema) {
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
});
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
        <div class="rating-badge reveal">
          <span class="rating-badge__num"><?php echo esc_html($rating_value); ?></span>
          <?php echo rp_stars_row(5, 24); ?>
          <span class="rating-badge__count"><?php echo esc_html($reviews_count); ?> отзывов в Google</span>
        </div>
      </div>
      <div class="ph page-hero__media reveal" data-bg="<?php echo esc_url($hero_image); ?>" data-label="Фото · 900×620" aria-hidden="true"></div>
    </div>
  </div>
</section>

<?php echo do_shortcode('[stats_block]'); ?>

<!-- ============ REVIEWS GRID ============ -->
<section class="dark-block dark-block--reviews">
  <div class="container">
    <h2 class="h-heavy h-heavy--light">Что говорят наши клиенты</h2>
    <div class="rev-grid rev-grid--9">
      <?php foreach ($reviews as $r):
          $tag_link = $r['tag_link'] ?? '';
          $tag_href = '#';
          if ($tag_link) {
              $tag_href = is_numeric($tag_link) ? get_permalink((int) $tag_link) : rp_page_url(str_replace('.html', '', $tag_link));
          }
      ?>
      <figure class="rev reveal">
        <div class="rev__head"><span class="rev__ava" aria-hidden="true"><?php echo esc_html($r['initials'] ?? ''); ?></span><div><b><?php echo esc_html($r['name'] ?? ''); ?></b><span><?php echo esc_html($r['count_label'] ?? ''); ?></span></div></div>
        <div class="rev__meta"><?php echo rp_stars_row($r['rating'] ?? 5, 18); ?><span class="rev__ago"><?php echo esc_html($r['ago_label'] ?? ''); ?></span></div>
        <blockquote><?php echo esc_html($r['text'] ?? ''); ?></blockquote>
        <a class="rev__tag" href="<?php echo esc_url($tag_href); ?>"><?php echo esc_html($r['tag_label'] ?? ''); ?></a>
      </figure>
      <?php endforeach; ?>
    </div>
    <div class="rev-foot reveal">
      <a class="btn btn--primary btn--lg" href="<?php echo esc_url(home_url('/#contacts')); ?>">Оставить отзыв</a>
      <span class="rev-google"><span class="rev-google__g" aria-hidden="true">G</span> Смотреть больше<br>отзывов в Google</span>
    </div>
  </div>
</section>

<?php echo do_shortcode('[quiz_calculator]'); ?>

<?php echo do_shortcode('[promo_banner eyebrow="Присоединяйтесь к нашим довольным клиентам"]'); ?>

<!-- ============ FAQ ============ -->
<section class="faq2">
  <div class="container">
    <h2 class="h-heavy faq2__title">Частые вопросы об отзывах</h2>
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

<!-- ============ SEO TEXT ============ -->
<section class="article">
  <div class="container container--narrow">
    <div class="prose reveal">
    <h2>Отзывы о ремонте квартир от «РемонтПрофи»</h2>
    <p>За годы работы мы собрали сотни отзывов от клиентов, которым доверили ремонт своей квартиры — от косметического обновления студии до комплексного ремонта многокомнатных квартир под ключ.</p>
    <p>Мы намеренно публикуем не только пятизвёздочные отклики: среди отзывов есть и оценки в 4 звезды с конструктивными замечаниями. Такая честность помогает будущим клиентам сформировать реалистичные ожидания, а нам — становиться лучше.</p>
    <p>Если вы уже работали с «РемонтПрофи» — будем благодарны за отзыв. Если только выбираете подрядчика — прочитайте, что говорят те, кто уже прошёл этот путь, и оставьте заявку на бесплатный замер.</p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
