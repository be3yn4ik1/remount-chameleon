<?php
/**
 * Общий футер сайта + кнопка «Онлайн-расчёт» (FAB), ведущая на калькулятор.
 * Если на текущей странице свой калькулятор (шаблоны с квизом) — ведём на локальный
 * якорь #calc, иначе — на калькулятор главной страницы.
 */
if (!defined('ABSPATH')) exit;

$rp_templates_with_quiz = ['page-landing.php', 'page-hub-services.php', 'page-prices.php', 'page-about.php', 'page-reviews.php', 'page-faq.php'];
$rp_fab_href = (is_front_page() || is_page_template($rp_templates_with_quiz)) ? '#calc' : home_url('/#calc');
?>
  </main>

  <!-- ============ FOOTER ============ -->
  <footer class="foot">
    <div class="container foot__inner">
      <div class="foot__brand">
        <span class="brand__logo">Ремонт<span>Профи</span></span>
        <p>Ремонт квартир под ключ любого объёма по Москве и области</p>
        <a class="foot__sitemap" href="<?php echo esc_url(rp_page_url('sitemap')); ?>">Карта сайта</a>
      </div>
      <nav class="foot__col" aria-label="Услуги">
        <span class="foot__title">Услуги</span>
        <a href="<?php echo esc_url(rp_page_url('remont-pod-klyuch-v-moskve')); ?>">Под ключ</a>
        <a href="<?php echo esc_url(rp_page_url('kosmeticheskiy-remont-kvartiry')); ?>">Косметический</a>
        <a href="<?php echo esc_url(rp_page_url('kapitalnyy-remont-kvartiry')); ?>">Капитальный</a>
        <a href="<?php echo esc_url(rp_page_url('dizaynerskiy-remont-kvartiry')); ?>">Дизайнерский</a>
      </nav>
      <nav class="foot__col" aria-label="Типы квартир">
        <span class="foot__title">Квартиры</span>
        <a href="<?php echo esc_url(rp_page_url('remont-odnokomnatnoy-kvartiry')); ?>">Однокомнатная</a>
        <a href="<?php echo esc_url(rp_page_url('remont-dvuhkomnatnoy-kvartiry')); ?>">Двухкомнатная</a>
        <a href="<?php echo esc_url(rp_page_url('remont-kvartiry-v-novostroyke')); ?>">Новостройка</a>
        <a href="<?php echo esc_url(rp_page_url('remont-kvartiry-studii')); ?>">Студия</a>
      </nav>
      <nav class="foot__col" aria-label="Цены">
        <span class="foot__title">Цены</span>
        <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'kalkulyator')); ?>">Калькулятор</a>
        <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'skolko-stoit')); ?>">Сколько стоит</a>
        <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'cena-za-m2')); ?>">Цена за м²</a>
        <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'nedorogo')); ?>">Недорого</a>
      </nav>
      <nav class="foot__col" aria-label="Блог">
        <span class="foot__title">Блог</span>
        <a href="<?php echo esc_url(rp_blog_url()); ?>">Все статьи</a>
        <a href="<?php echo esc_url(rp_post_url('chernovoy-remont-kvartir')); ?>">Черновой ремонт</a>
        <a href="<?php echo esc_url(rp_post_url('remont-komnaty-v-kvartire')); ?>">Ремонт комнаты</a>
        <a href="<?php echo esc_url(rp_post_url('remont-novostroyki-pod-klyuch')); ?>">Новостройка под ключ</a>
      </nav>
    </div>
  </footer>

</div><!-- /.page -->

<a class="fab" href="<?php echo esc_url($rp_fab_href); ?>" aria-label="Онлайн-расчёт">
  <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm1 4h8M8 11h2m3 0h3m-8 4h2m3 0h3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
