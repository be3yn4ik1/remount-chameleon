<?php
if (!defined('ABSPATH')) exit;
get_header();
?>
<section class="page-hero">
  <div class="container">
    <div class="page-hero__text reveal">
      <h1 class="h-heavy page-hero__title">Страница не найдена</h1>
      <p class="page-hero__lead">Такой страницы не существует или она была перемещена. Загляните на главную или воспользуйтесь картой сайта.</p>
      <a class="btn btn--primary btn--lg" href="<?php echo esc_url(home_url('/')); ?>">На главную</a>
      &nbsp;
      <a class="btn btn--dark btn--lg" href="<?php echo esc_url(rp_page_url('sitemap')); ?>">Карта сайта</a>
    </div>
  </div>
</section>
<?php get_footer(); ?>
