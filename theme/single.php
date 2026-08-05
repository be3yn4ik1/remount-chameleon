<?php
/**
 * Одна статья блога. Заголовок/подзаголовок/фото шапки — из ACF (group_rp_post)
 * с откатом на данные, перенесённые при активации в post_content/meta.
 */
if (!defined('ABSPATH')) exit;

get_header();
while (have_posts()): the_post();

$bd = rp_blog_defaults();
$hero_lead = rp_field('hero_lead', $bd['hero_lead'] ?? get_the_excerpt());
$hero_image_default = get_the_post_thumbnail_url(get_the_ID(), 'rp-hero') ?: rp_default_img($bd['hero_image'] ?? '2104.jpg');
$hero_image = rp_image_url('hero_image', $hero_image_default);
?>

<!-- ============ PAGE HERO ============ -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Хлебные крошки">
      <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span class="breadcrumb__sep">/</span>
      <a href="<?php echo esc_url(rp_blog_url()); ?>">Блог</a><span class="breadcrumb__sep">/</span>
      <span class="breadcrumb__current"><?php the_title(); ?></span>
    </nav>
    <div class="page-hero__inner">
      <div class="page-hero__text reveal">
        <h1 class="h-heavy page-hero__title"><?php the_title(); ?></h1>
        <p class="page-hero__lead"><?php echo esc_html($hero_lead); ?></p>
      </div>
      <div class="ph page-hero__media reveal" data-bg="<?php echo esc_url($hero_image); ?>" data-label="Фото · 900×620" aria-hidden="true"></div>
    </div>
  </div>
</section>

<!-- ============ ARTICLE ============ -->
<section class="article">
  <div class="container container--narrow">
    <div class="prose reveal">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<div class="article-cta">
  <?php echo do_shortcode('[promo_banner]'); ?>
</div>

<?php endwhile; get_footer(); ?>
