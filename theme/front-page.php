<?php
/**
 * Template Name: Главная страница
 * (назначается автоматически хуком активации как «Страница на главной»)
 * ACF-группа group_rp_front_page — hero + заголовки разделов. Остальной контент
 * (карточки услуг, тарифы, отзывы, FAQ) — фиксирован в коде: это витрина сайта,
 * собранная из уже готовых страниц услуг/цен/отзывов.
 */
if (!defined('ABSPATH')) exit;

get_header();

$hero_title = rp_field('hero_title', 'Ремонт квартир под ключ');
$hero_subtitle = rp_field('hero_subtitle', 'Дизайнерский ремонт');
$hero_image = rp_image_url('hero_image', rp_default_img('802.jpg'));
$process_image = rp_image_url('process_image', rp_default_img('zakazremonta.jpg'));
$services_title = rp_field('services_title', 'Делаем быстро и качественно');
$pricing_title = rp_field('pricing_title', 'Стоимость услуг РемонтПрофи');
$reviews_title = rp_field('reviews_title', 'Отзывы клиентов');

$prices_d = rp_get_data('prices');
$pricing_cards = $prices_d['skolko_pcards'] ?? [];

$brands = ['BIHUI.png', 'Bergauf.png', 'DeWalt.jpg', 'Dufa.webp', 'EKF.png', 'Grohe-logo.png', 'Hansgrohe.jpg', 'Hilti.jpg', 'IEK.jpg', 'INGCO.png', 'Kermi.jpg', 'Kärcher.png', 'Legrand.png', 'Leica.png', 'Litokol.jpg', 'MAPEI.jpg', 'Makita.jpg', 'Metabo.png', 'Milwaukee.png', 'RUBI.png', 'Sika.png', 'Soudal.png', 'Stanley.png', 'Tarkett.jpg', 'Tesa.jpg', 'Uponor.svg', 'Viega_Logo.svg', 'ceresit.png', 'festool.webp', 'knauf.jpg', 'pattex-logo.webp', 'tytan.png', 'weber.png'];

function rp_brand_logos_list($brands, $hide_alt = false) {
    foreach ($brands as $file) {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $alt = $hide_alt ? '' : esc_attr($name);
        echo '<li class="clients__logo"><img src="' . esc_url(RP_THEME_URI . '/assets/img/brandlogo/' . $file) . '" alt="' . $alt . '" loading="lazy" decoding="async"></li>';
    }
}
?>

<!-- ============ HERO ============ -->
<section class="hero" id="hero">
  <div class="container hero__inner">
    <div class="hero__left">
      <h1 class="hero__title"><?php echo wp_kses_post(nl2br(esc_html($hero_title))); ?> <span class="accent">под ключ</span></h1>
      <p class="hero__typed"><span class="typed" id="typed"><?php echo esc_html($hero_subtitle); ?></span><span class="caret" id="caret">|</span></p>
      <div class="hero__cta-row">
        <a class="btn btn--primary btn--xl" href="#calc">Онлайн-расчёт ремонта</a>
        <span class="hero__hint">
          <svg viewBox="0 0 40 40" width="40" height="30" aria-hidden="true"><path d="M4 6c10 2 20 6 26 16" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="3 3"/><path d="M30 22l1-8-7 3" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          Это быстро<br>и удобно
        </span>
      </div>
    </div>

    <div class="hero__right">
      <div class="hero__panel">
        <div class="hero__badges">
          <div class="hbadge"><span class="hbadge__pre">от</span><b>4 900₽</b><span class="hbadge__post">за м²</span><span class="hbadge__tag">под ключ</span></div>
          <div class="hbadge"><span class="hbadge__pre">от</span><b>24 ч</b><span class="hbadge__post">выезд замерщика</span></div>
        </div>
        <div class="ph hero__photo" data-bg="<?php echo esc_url($hero_image); ?>" data-label="Фото интерьера · 900×620" aria-hidden="true"></div>
      </div>
    </div>
  </div>

  <!-- Clients -->
  <div class="container clients">
    <span class="clients__label">Бренды, с которыми<br>работаем</span>
    <div class="clients__marquee">
      <div class="clients__track">
        <ul class="clients__list"><?php rp_brand_logos_list($brands); ?></ul>
        <ul class="clients__list" aria-hidden="true"><?php rp_brand_logos_list($brands, true); ?></ul>
      </div>
    </div>
  </div>
</section>

<!-- ============ SERVICES (dark) ============ -->
<section class="dark-block" id="services">
  <div class="container">
    <div class="dark-block__head reveal">
      <h2 class="h-heavy h-heavy--light"><?php echo wp_kses_post(nl2br(esc_html($services_title))); ?></h2>
      <span class="brand__logo brand__logo--light">Ремонт<span>Профи</span></span>
    </div>

    <div class="svc-grid">
      <?php
      rp_svc_card('Косметический ремонт', 'Обновим квартиру: покраска, обои, полы — быстро и без пыли до потолка.', rp_default_img('kosmeticheski-remont.jpg'), rp_page_url('kosmeticheskiy-remont-kvartiry'));
      rp_svc_card('Капитальный ремонт', 'Полная замена коммуникаций, стяжка, штукатурка, электрика и сантехника.', rp_default_img('kapitalny-remont.jpg'), rp_page_url('kapitalnyy-remont-kvartiry'));
      rp_svc_card('Ремонт под ключ', 'Всё включено: черновой и чистовой этапы, материалы, вывоз мусора и уборка.', rp_default_img('remont-pod-kluch.jpg'), rp_page_url('remont-pod-klyuch-v-moskve'));
      rp_svc_card('Дизайнерский ремонт', 'Индивидуальный дизайн-проект, авторский надзор и подбор материалов.', rp_default_img('dizaynersky-remont.jpg'), rp_page_url('dizaynerskiy-remont-kvartiry'));
      rp_svc_card('Ремонт в новостройке', 'Ремонт с нуля от застройщика — от голых стен до готового жилья.', rp_default_img('remont-v-novostroyke.jpg'), rp_page_url('remont-kvartiry-v-novostroyke'));
      rp_svc_card('Отделка и черновые работы', 'Стены, полы, потолки и все черновые этапы под чистовую отделку.', rp_default_img('otdelka-chernovye-raboty.jpg'), rp_page_url('otdelka-kvartir-v-moskve'));
      ?>
    </div>
  </div>
</section>

<?php echo do_shortcode('[promo_banner eyebrow="Нет времени разбираться?"]'); ?>

<!-- ============ FEATURES ============ -->
<section class="features">
  <div class="container features__grid">
    <article class="feat reveal">
      <div><h3>Собственные бригады<br>и прорабы</h3><p>Специалисты под разные виды работ. Аккуратно, чисто и точно по графику, без субподряда.</p></div>
      <span class="feat__ic"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path d="M3 21V10l9-6 9 6v11h-6v-7H9v7z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></span>
    </article>
    <article class="feat reveal">
      <div><h3>12 лет опыта<br>ремонта</h3><p>Реализуем ремонт квартиры любого класса — от косметики до премиум под ключ.</p></div>
      <span class="feat__ic"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path d="M12 3l8 4v5c0 5-3.4 8-8 9-4.6-1-8-4-8-9V7z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></span>
    </article>
    <article class="feat reveal">
      <div><h3>Быстрый выезд<br>замерщика</h3><p>Приедем на объект в удобное время, снимем замеры и составим смету. Работаем быстро.</p></div>
      <span class="feat__ic"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span>
    </article>
  </div>
</section>

<!-- ============ PRICING ============ -->
<section class="pricing" id="pricing">
  <div class="container">
    <h2 class="h-heavy pricing__title"><?php echo esc_html($pricing_title); ?></h2>
    <div class="price-grid">
      <?php rp_render_pricing_cards($pricing_cards); ?>
    </div>
    <div class="price-extra">
      <article class="pcard pcard--extra reveal">
        <h3 class="pcard__extra-title">Дополнительно</h3>
        <ul class="pcard__rows pcard__rows--dark">
          <li><span>Демонтаж перегородок</span><b>от 350₽/м²</b></li>
          <li><span>Вывоз строительного мусора</span><b>от 4 500₽</b></li>
          <li><span>Электромонтажные работы</span><b>от 900₽/точка</b></li>
          <li><span>Сантехнические работы</span><b>от 1 500₽/точка</b></li>
          <li><span>Дизайн-проект «под ключ»</span><b>от 1 500₽/м²</b></li>
        </ul>
      </article>
      <div class="price-note reveal">
        <p>Цены примерные и актуальны для Москвы. Точную смету мастер подтвердит после бесплатного замера — стоимость фиксируется в договоре и не меняется в процессе работ.</p>
        <a class="btn btn--dark btn--lg" href="#calc">Рассчитать мой ремонт</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ PROCESS (dark) ============ -->
<section class="dark-block dark-block--process" id="process">
  <div class="container">
    <h2 class="h-heavy h-heavy--light process__title">Заказать ремонт легко</h2>
    <div class="process__wrap">
      <ol class="steps2">
        <li class="step2 reveal"><div><h3>Обращение</h3><p>Оставляете заявку на сайте или звоните нам</p></div><span class="step2__n">1</span></li>
        <li class="step2 reveal"><div><h3>Оцениваем объём</h3><p>Выезжаем на замер и осматриваем объект</p></div><span class="step2__n">2</span></li>
        <li class="step2 reveal"><div><h3>Подбираем решение</h3><p>Смета, материалы и график под ваш бюджет</p></div><span class="step2__n">3</span></li>
        <li class="step2 reveal"><div><h3>Фиксируем цену</h3><p>Договор с фиксированной сметой и сроком</p></div><span class="step2__n">4</span></li>
        <li class="step2 reveal"><div><h3>Делаем ремонт</h3><p>Работаем по графику и шлём фотоотчёты</p></div><span class="step2__n">5</span></li>
        <li class="step2 step2--hl reveal"><div><h3>Сдаём объект</h3><p>Принимаете готовую квартиру и гарантию</p></div><span class="step2__n">6</span></li>
      </ol>
      <div class="ph process__media" data-bg="<?php echo esc_url($process_image); ?>" data-label="Фото · 520×520"></div>
    </div>
  </div>
</section>

<?php echo do_shortcode('[quiz_calculator]'); ?>

<!-- ============ FAQ ============ -->
<section class="faq2" id="faq">
  <div class="container">
    <h2 class="h-heavy faq2__title">Отвечаем на популярные вопросы</h2>
    <div class="faq2__list">
      <?php rp_render_faq([
          ['question' => 'Как заказать ремонт квартиры?', 'answer' => 'Оставьте заявку на сайте или позвоните нам. Мы согласуем удобное время, бесплатно выедем на замер, составим смету и заключим договор с фиксированной ценой.'],
          ['question' => 'Сколько стоят ваши услуги?', 'answer' => 'Стоимость зависит от класса ремонта и площади: косметический — от 4 900 ₽/м², под ключ — от 12 000 ₽/м². Точную смету мастер рассчитает на замере.'],
          ['question' => 'Может ли измениться цена в процессе ремонта?', 'answer' => 'Нет. Стоимость фиксируется в договоре после замера. Дополнительные работы возможны только по вашему согласованию и оформляются приложением к смете.'],
          ['question' => 'Какая гарантия на выполненные работы?', 'answer' => 'Официальная гарантия ' . rp_option('warranty_years', '3') . ' года на все виды работ. В течение срока бесплатно устраним любые недочёты, возникшие по нашей вине.'],
      ]); ?>
    </div>
  </div>
</section>

<!-- ============ REVIEWS (dark) ============ -->
<section class="dark-block dark-block--reviews" id="reviews">
  <div class="container">
    <h2 class="h-heavy h-heavy--light"><?php echo esc_html($reviews_title); ?></h2>
    <div class="rev-grid">
      <figure class="rev reveal">
        <div class="rev__head"><span class="rev__ava" aria-hidden="true">АК</span><div><b>Анна Ковалёва</b><span>3 отзыва</span></div></div>
        <div class="rev__stars" aria-label="5 из 5">★★★★★ <span>3 месяца назад</span></div>
        <blockquote>Делали ремонт под ключ в двушке. Уложились ровно в смету и сдали даже раньше срока. Отдельное спасибо прорабу за фотоотчёты. Рекомендую!</blockquote>
      </figure>
      <figure class="rev reveal">
        <div class="rev__head"><span class="rev__ava" aria-hidden="true">ДМ</span><div><b>Дмитрий Морозов</b><span>5 отзывов</span></div></div>
        <div class="rev__stars" aria-label="5 из 5">★★★★★ <span>4 месяца назад</span></div>
        <blockquote>Заказывал капитальный ремонт в новостройке. Цена не менялась по ходу работ, всё чётко по договору. Ребята вежливые, работу выполнили качественно.</blockquote>
      </figure>
      <figure class="rev reveal">
        <div class="rev__head"><span class="rev__ava" aria-hidden="true">ЕС</span><div><b>Елена Смирнова</b><span>1 отзыв</span></div></div>
        <div class="rev__stars" aria-label="5 из 5">★★★★★ <span>полгода назад</span></div>
        <blockquote>Дизайнерский ремонт в трёшке — результат превзошёл ожидания. Помогли с проектом и подобрали материалы. Будем рекомендовать вас знакомым, спасибо!</blockquote>
      </figure>
    </div>
    <div class="rev-foot reveal">
      <a class="btn btn--primary btn--lg" href="#contacts">Оставить отзыв</a>
      <a class="rev-google" href="<?php echo esc_url(rp_page_url('otzyvy-o-remonte-kvartir')); ?>"><span class="rev-google__g" aria-hidden="true">G</span> Смотреть больше<br>отзывов в Google</a>
    </div>
  </div>
</section>

<?php echo do_shortcode('[contacts_block]'); ?>

<?php get_footer(); ?>
