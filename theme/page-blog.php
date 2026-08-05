<?php
/**
 * Template Name: Блог (список статей)
 * Назначьте эту страницу в Настройки → Чтение → «Страница записей» (это делает
 * хук активации автоматически). Выводит все статьи блога плиткой + пагинация.
 */
if (!defined('ABSPATH')) exit;

get_header();

$paged = max(1, get_query_var('paged'), get_query_var('page'));
$q = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 12,
    'paged' => $paged,
]);
?>

<!-- ============ BLOG HERO ============ -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Хлебные крошки">
      <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span class="breadcrumb__sep">/</span>
      <span class="breadcrumb__current">Блог</span>
    </nav>
    <div class="page-hero__text reveal">
      <h1 class="h-heavy page-hero__title">Блог</h1>
      <p class="page-hero__lead">Статьи о ремонте квартир в Москве: виды работ, цены и советы от мастеров «РемонтПрофи».</p>
    </div>
  </div>
</section>

<!-- ============ BLOG GRID ============ -->
<section class="blog">
  <div class="container">
    <div class="blog-grid">
      <?php if ($q->have_posts()): while ($q->have_posts()): $q->the_post();
          $img = get_the_post_thumbnail_url(get_the_ID(), 'rp-card');
          if (!$img) {
              $hero = get_field('hero_image');
              $img = is_array($hero) ? ($hero['url'] ?? '') : '';
          }
      ?>
        <a class="blog-card reveal" href="<?php the_permalink(); ?>">
          <div class="ph blog-card__media" data-bg="<?php echo esc_url($img); ?>" data-label="Фото · 480×300"></div>
          <div class="blog-card__body">
            <h3 class="blog-card__title"><?php the_title(); ?></h3>
            <p class="blog-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
            <div class="blog-card__foot">
              <span class="blog-card__read">Читать статью</span>
              <span class="blog-card__arrow" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18"><path d="M5 12h14m-6-6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
            </div>
          </div>
        </a>
      <?php endwhile; else: ?>
        <p>Пока нет опубликованных статей.</p>
      <?php endif; ?>
    </div>

    <?php
    $links = paginate_links([
        'total' => $q->max_num_pages,
        'current' => $paged,
        'type' => 'array',
    ]);
    if ($links): ?>
    <nav class="rp-pagination" aria-label="Страницы блога">
      <?php foreach ($links as $link) echo wp_kses_post($link); ?>
    </nav>
    <?php endif;
    wp_reset_postdata();
    ?>
  </div>
</section>

<?php get_footer(); ?>
