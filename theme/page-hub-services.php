<?php
/**
 * Template Name: Хаб — Все услуги
 * Каталог всех услуг сгруппирован как в меню (по коду — не редактируется в ACF,
 * т.к. это список ссылок на другие страницы сайта). Hero/цены/FAQ/SEO — из ACF
 * с откатом на inc/data/hub.php.
 */
if (!defined('ABSPATH')) exit;

get_header();

$d = rp_get_data('hub');

$hero_h1 = rp_field('hero_h1', $d['hero_h1'] ?? get_the_title());
$hero_lead = rp_field('hero_lead', $d['hero_lead'] ?? '');
$hero_image = rp_image_url('hero_image', rp_default_img($d['hero_image'] ?? ''));
$pricing_cards = rp_repeater('pricing_cards', $d['pricing_cards'] ?? []);
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

<!-- ============ CATALOG: ПО ТИПУ РАБОТ ============ -->
<section class="article">
  <div class="container">
    <h2 class="h-heavy pricing__title">По типу <span class="accent">работ</span></h2>
    <div class="svc-grid">
      <?php
      rp_svc_card('Ремонт под ключ', 'Всё включено: черновой и чистовой этапы, материалы, вывоз мусора и уборка.', rp_default_img('remont-pod-kluch.jpg'), rp_page_url('remont-pod-klyuch-v-moskve'));
      rp_svc_card('Косметический ремонт', 'Обновим квартиру: покраска, обои, полы — быстро и без пыли до потолка.', rp_default_img('kosmeticheski-remont.jpg'), rp_page_url('kosmeticheskiy-remont-kvartiry'));
      rp_svc_card('Капитальный ремонт', 'Полная замена коммуникаций, стяжка, штукатурка, электрика и сантехника.', rp_default_img('kapitalny-remont.jpg'), rp_page_url('kapitalnyy-remont-kvartiry'));
      rp_svc_card('Отделка квартир', 'Стены, полы, потолки и все черновые этапы под чистовую отделку.', rp_default_img('otdelka-chernovye-raboty.jpg'), rp_page_url('otdelka-kvartir-v-moskve'));
      rp_svc_card('Современный ремонт', 'Актуальные стили и решения: минимализм, эко, лофт — без переплаты за лишний декор.', rp_default_img('908.jpg'), rp_page_url('sovremennyy-remont-kvartiry'));
      ?>
    </div>
  </div>
</section>

<!-- ============ CATALOG: ДИЗАЙН ============ -->
<section class="article">
  <div class="container">
    <h2 class="h-heavy pricing__title">Дизайн</h2>
    <div class="svc-grid">
      <?php
      rp_svc_card('Дизайнерский ремонт', 'Индивидуальный дизайн-проект, авторский надзор и подбор материалов.', rp_default_img('dizaynersky-remont.jpg'), rp_page_url('dizaynerskiy-remont-kvartiry'));
      rp_svc_card('Дизайн и ремонт', 'Проект и реализация одной командой — без нестыковок между дизайнером и бригадой.', rp_default_img('2148848676.jpg'), rp_page_url('dizayn-i-remont-kvartir-v-moskve'));
      rp_svc_card('Дизайн интерьера', 'Разработка стиля, планировки и 3D-визуализации будущего интерьера.', rp_default_img('802.jpg'), rp_post_url('dizayn-interyera-i-remont-kvartiry'));
      rp_svc_card('Ремонт элитных квартир', 'Премиальные материалы, эксклюзивные решения и полная конфиденциальность проекта.', rp_default_img('2150794692.jpg'), rp_page_url('remont-elitnyh-kvartir'));
      ?>
    </div>
  </div>
</section>

<!-- ============ CATALOG: ПОМЕЩЕНИЯ ============ -->
<section class="article">
  <div class="container">
    <h2 class="h-heavy pricing__title">Помещения</h2>
    <div class="svc-grid svc-grid--narrow">
      <?php
      rp_svc_card('Ремонт ванной', 'Гидроизоляция, плитка, сантехника — под ключ за 1–2 недели без остановки на полдома.', rp_default_img('2150790877.jpg'), rp_page_url('remont-vanny-v-kvartire'));
      rp_svc_card('Ремонт коридора', 'Практичная и износостойкая отделка прихожей и коридора без долгого простоя.', rp_default_img('2151037570.jpg'), rp_page_url('remont-koridora-v-kvartire'));
      ?>
    </div>
  </div>
</section>

<!-- ============ FEATURES ============ -->
<section class="features">
  <div class="container features__grid">
    <article class="feat reveal">
      <div><h3>Фиксированная смета</h3><p>Стоимость закрепляем в договоре после бесплатного замера — никаких доплат «по ходу дела».</p></div>
      <span class="feat__ic"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path d="M6 3h9l3 3v15H6z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 12h6M9 16h6M9 8h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span>
    </article>
    <article class="feat reveal">
      <div><h3>Поможем выбрать формат</h3><p>Не уверены, что нужно — косметика или капиталка? Бесплатно проконсультируем и подберём оптимальный вариант.</p></div>
      <span class="feat__ic"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="m15 9-4.5 1.5L9 15l4.5-1.5z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></span>
    </article>
    <article class="feat reveal">
      <div><h3>Честные цены за м²</h3><p>Работаем без посредников и субподряда — закупаем материалы напрямую у поставщиков.</p></div>
      <span class="feat__ic"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path d="M8 21V4h5a4 4 0 1 1 0 8H8m0-3h9M6 15h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
    </article>
  </div>
</section>

<!-- ============ PRICING ============ -->
<section class="pricing">
  <div class="container">
    <h2 class="h-heavy pricing__title">Ориентировочные цены <span class="accent">в Москве</span></h2>
    <div class="price-grid">
      <?php rp_render_pricing_cards($pricing_cards); ?>
    </div>
    <div class="price-extra" style="grid-template-columns:1fr">
      <div class="price-note reveal">
        <p>Точная стоимость зависит от выбранной услуги, площади и состояния квартиры. Откройте страницу нужного направления работ выше — там указаны детальные цены — или пройдите тест ниже, чтобы получить персональный расчёт.</p>
        <a class="btn btn--dark btn--lg" href="#calc">Рассчитать мой ремонт</a>
      </div>
    </div>
  </div>
</section>

<?php echo do_shortcode('[quiz_calculator]'); ?>

<?php echo do_shortcode('[promo_banner eyebrow="Не уверены, какая услуга подходит?"]'); ?>

<!-- ============ FAQ ============ -->
<section class="faq2">
  <div class="container">
    <h2 class="h-heavy faq2__title">Частые вопросы</h2>
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
