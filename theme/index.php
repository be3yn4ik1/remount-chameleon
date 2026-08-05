<?php
/** Резервный шаблон (WP требует index.php в теме). Обычные записи используют single.php,
 * специальные страницы — свои page-*.php шаблоны; сюда WP попадёт только в нетипичном случае. */
if (!defined('ABSPATH')) exit;

get_header();
?>
<section class="article">
  <div class="container container--narrow">
    <div class="prose reveal">
      <?php if (have_posts()): while (have_posts()): the_post(); ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>
      <?php endwhile; else: ?>
        <p>Ничего не найдено.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
