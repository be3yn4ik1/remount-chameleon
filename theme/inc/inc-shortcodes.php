<?php
/**
 * Шорткоды для блоков, которые повторяются на множестве страниц:
 * квиз-калькулятор, промо-баннер, полоса доверия, статистика, cta-box (блог -> услуга),
 * блок контактов, иконки мессенджеров.
 */

if (!defined('ABSPATH')) exit;

/** SVG-спрайт с иконками MAX / Telegram — используется messenger_icons и один раз на странице. */
function rp_icon_sprite() {
    static $printed = false;
    if ($printed) return '';
    $printed = true;
    return '<svg width="0" height="0" style="position:absolute;overflow:hidden" aria-hidden="true" focusable="false">
  <defs>
    <linearGradient id="g-max" x1="0" y1="534" x2="522" y2="0" gradientUnits="userSpaceOnUse">
      <stop offset="0.14" stop-color="#43C3FD"/><stop offset="0.4" stop-color="#3254ED"/><stop offset="0.87" stop-color="#8849E5"/>
    </linearGradient>
    <linearGradient id="g-tg" x1="0" y1="534" x2="522" y2="0" gradientUnits="userSpaceOnUse">
      <stop stop-color="#43C3FD"/><stop offset="0.735577" stop-color="#3254ED"/>
    </linearGradient>
  </defs>
  <symbol id="ic-max" viewBox="0 0 522 534">
    <rect width="522" height="534" rx="110" fill="url(#g-max)"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M264.009 425.816C232.495 425.816 217.85 421.216 192.394 402.814C176.292 423.516 125.303 439.694 123.079 412.015C123.079 391.236 118.479 373.677 113.265 354.508C107.054 330.892 100 304.592 100 266.485C100 175.471 174.682 107 263.165 107C351.725 107 421.116 178.845 421.116 267.328C421.414 354.443 351.123 425.351 264.009 425.816ZM265.312 185.669C222.221 183.445 188.637 213.272 181.199 260.044C175.065 298.765 185.953 345.92 195.231 348.374C199.678 349.447 210.873 340.4 217.85 333.422C229.388 341.393 242.823 346.18 256.801 347.301C301.451 349.448 339.603 315.456 342.601 270.855C344.346 226.16 309.968 188.303 265.312 185.746V185.669Z" fill="white"/>
  </symbol>
  <symbol id="ic-tg" viewBox="0 0 522 534">
    <rect width="522" height="534" rx="110" fill="url(#g-tg)"/>
    <path d="M70.2984 282.839L157.251 312.368L363.711 186.151C366.708 184.328 369.773 188.392 367.194 190.769L210.895 334.633L205.089 415.172C204.994 416.521 205.312 417.867 205.999 419.032C206.687 420.197 207.713 421.124 208.94 421.693C210.167 422.261 211.538 422.443 212.871 422.214C214.204 421.985 215.436 421.356 216.404 420.411L264.525 373.086L352.504 439.678C361.982 446.861 375.714 441.798 378.266 430.187L439.524 152.045C443.021 136.18 427.467 122.786 412.304 128.592L69.8664 259.724C59.1188 263.842 59.4158 279.14 70.2984 282.839Z" fill="white"/>
  </symbol>
</svg>';
}
add_action('wp_body_open', function () { echo rp_icon_sprite(); });

/** [messenger_icons] — иконки MAX + Telegram со ссылками из настроек. */
function rp_sc_messenger_icons($atts = []) {
    $tg = rp_option('telegram_url', 'https://t.me/remontprofi24');
    $max = rp_option('max_url', 'https://max.ru/u/f9LHodD0cOJIXJXasmfUkPbZ7O924XcH1OknEoYU3TlflYSq0G6fg62ngeo');
    ob_start(); ?>
    <a class="msgr" href="<?php echo esc_url($max); ?>" target="_blank" rel="noopener" aria-label="MAX"><svg aria-hidden="true"><use href="#ic-max"/></svg></a>
    <a class="msgr" href="<?php echo esc_url($tg); ?>" target="_blank" rel="noopener" aria-label="Telegram"><svg aria-hidden="true"><use href="#ic-tg"/></svg></a>
    <?php return ob_get_clean();
}
add_shortcode('messenger_icons', 'rp_sc_messenger_icons');

/** [hero_trust] — полоса доверия под заголовком (рейтинг, гарантия, договор, замер). */
function rp_sc_hero_trust($atts = []) {
    $rating = rp_option('rating_value', '4.9');
    $warranty = rp_option('warranty_years', '3');
    ob_start(); ?>
    <div class="hero-trust">
      <span>★ <?php echo esc_html($rating); ?> Google</span>
      <span>Гарантия <?php echo esc_html($warranty); ?> года</span>
      <span>Договор с фиксированной ценой</span>
      <span>Бесплатный замер</span>
    </div>
    <?php return ob_get_clean();
}
add_shortcode('hero_trust', 'rp_sc_hero_trust');

/** [stats_block] — секция с 4 цифрами (лет на рынке, объектов, % рекомендуют, гарантия). */
function rp_sc_stats_block($atts = []) {
    $years = rp_option('years_on_market', '12');
    $objects = rp_option('objects_done', '500');
    $recommend = rp_option('clients_recommend', '98');
    $warranty = rp_option('warranty_years', '3');
    ob_start(); ?>
    <section class="stats">
      <div class="container stats__grid">
        <div class="stat reveal"><span class="stat__num"><?php echo esc_html($years); ?><span class="accent">+</span></span><span class="stat__label">лет на рынке</span></div>
        <div class="stat reveal"><span class="stat__num"><?php echo esc_html($objects); ?><span class="accent">+</span></span><span class="stat__label">сданных объектов</span></div>
        <div class="stat reveal"><span class="stat__num"><?php echo esc_html($recommend); ?><span class="accent">%</span></span><span class="stat__label">клиентов рекомендуют</span></div>
        <div class="stat reveal"><span class="stat__num"><?php echo esc_html($warranty); ?></span><span class="stat__label">года гарантии</span></div>
      </div>
    </section>
    <?php return ob_get_clean();
}
add_shortcode('stats_block', 'rp_sc_stats_block');

/**
 * [promo_banner eyebrow="..." title="Просто позвоните<br>или свяжитесь<br>удобным способом"]
 * CTA-баннер с телефоном и мессенджерами. Атрибуты необязательны.
 */
function rp_sc_promo_banner($atts = []) {
    $atts = shortcode_atts([
        'eyebrow' => 'Рассчитаем стоимость за пару минут',
        'title'   => 'Просто позвоните<br>или свяжитесь<br>удобным способом',
        'image'   => '',
    ], $atts, 'promo_banner');

    $phone = rp_option('phone', '+7 (977) 922-07-18');
    $hours = rp_option('work_hours', 'Ежедневно с 08:00 до 22:00');
    $image = $atts['image'] ?: rp_default_img('prosto-pozvonite.png');

    ob_start(); ?>
    <section class="promo">
      <div class="container">
        <div class="promo__box reveal">
          <div class="promo__content">
            <span class="promo__eyebrow"><?php echo esc_html($atts['eyebrow']); ?></span>
            <h2 class="h-heavy"><?php echo wp_kses_post($atts['title']); ?></h2>
            <div class="promo__card">
              <a class="promo__phone" href="<?php echo esc_attr(rp_tel_href($phone)); ?>"><?php echo esc_html($phone); ?></a>
              <div class="promo__card-row">
                <span class="hdr__hours"><b class="tag-online">Online</b> <?php echo esc_html($hours); ?></span>
                <span class="online online--dark"><i></i>Задайте вопрос,<br>мы онлайн</span>
                <span class="msgr-row"><?php echo do_shortcode('[messenger_icons]'); ?></span>
              </div>
            </div>
          </div>
          <div class="ph promo__media" data-bg="<?php echo esc_url($image); ?>" data-label="Фото мастера · 620×520"></div>
        </div>
      </div>
    </section>
    <?php return ob_get_clean();
}
add_shortcode('promo_banner', 'rp_sc_promo_banner');

/** [contacts_block] — блок с адресом/телефоном/почтой/часами/мессенджерами (секция «Наши контакты»). */
function rp_sc_contacts_block($atts = []) {
    $phone = rp_option('phone', '+7 (977) 922-07-18');
    $email = rp_option('email', 'remont-proofi@ya.ru');
    $address = rp_option('address', '129000, Москва, ул. Примерная, д. 1');
    $hours = rp_option('work_hours', 'Ежедневно с 08:00 до 22:00');
    ob_start(); ?>
    <section class="contacts" id="contacts">
      <div class="container">
        <h2 class="h-heavy contacts__title"><?php echo esc_html(rp_field('contacts_title', 'Наши контакты', get_option('page_on_front'))); ?></h2>
        <div class="contacts__grid reveal">
          <div class="ph contacts__map" data-bg="placeholder" data-label="Карта · 900×520"></div>
          <div class="contacts__info">
            <p class="contacts__addr"><?php echo wp_kses_post(nl2br(esc_html($address))); ?></p>
            <a class="contacts__phone" href="<?php echo esc_attr(rp_tel_href($phone)); ?>"><?php echo esc_html($phone); ?></a>
            <a class="contacts__email" href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
            <span class="hdr__hours"><b class="tag-online">Online</b> <?php echo esc_html($hours); ?></span>
            <div class="contacts__msgr">
              <span class="online online--dark"><i></i>Задайте вопрос,<br>мы онлайн</span>
              <?php echo do_shortcode('[messenger_icons]'); ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php return ob_get_clean();
}
add_shortcode('contacts_block', 'rp_sc_contacts_block');

/**
 * [cta_box title="..." text="..." link="123" link_text="Смотреть цены"]
 * Врезка в статье блога со ссылкой на посадочную страницу услуги.
 * link — ID страницы (post_object) либо прямой URL в атрибуте url.
 */
function rp_sc_cta_box($atts = []) {
    $atts = shortcode_atts([
        'title'     => '',
        'text'      => '',
        'link'      => 0,
        'url'       => '',
        'link_text' => 'Смотреть цены',
    ], $atts, 'cta_box');

    $url = $atts['url'];
    if (!$url && $atts['link']) {
        $url = get_permalink((int) $atts['link']);
    }
    if (!$url) $url = home_url('/');

    ob_start(); ?>
    <div class="cta-box reveal">
      <p><?php if ($atts['title']): ?><strong><?php echo esc_html($atts['title']); ?></strong> <?php endif; echo esc_html($atts['text']); ?></p>
      <a class="btn btn--primary" href="<?php echo esc_url($url); ?>"><?php echo esc_html($atts['link_text']); ?></a>
    </div>
    <?php return ob_get_clean();
}
add_shortcode('cta_box', 'rp_sc_cta_box');

/**
 * [quiz_calculator] — квиз-калькулятор стоимости ремонта. Разметка идентична на всех
 * страницах, поведение обеспечивает assets/js/main.js (без изменений).
 */
function rp_sc_quiz_calculator($atts = []) {
    $phone = rp_option('phone', '+7 (977) 922-07-18');
    ob_start(); ?>
    <section class="quiz" id="calc">
      <div class="container">
        <div class="quiz__box reveal">
          <div class="quiz__intro">
            <span class="promo__eyebrow">Бесплатный расчёт за 30 секунд</span>
            <h2 class="h-heavy">Рассчитайте свой ремонт</h2>
            <p>Ответьте на 4 коротких вопроса — покажем примерную стоимость ремонта вашей квартиры в Москве.</p>
          </div>

          <form class="quiz__panel" id="quizForm" novalidate>
            <div class="quiz__progress"><span class="quiz__progress-bar" id="quizProgress" style="width:20%"></span></div>

            <div class="quiz__step is-active" data-step="1">
              <h3>Какой тип ремонта вам нужен?</h3>
              <div class="quiz__options" data-field="type">
                <button type="button" class="quiz__option" data-value="cosmetic">Косметический</button>
                <button type="button" class="quiz__option" data-value="capital">Капитальный</button>
                <button type="button" class="quiz__option" data-value="turnkey">Под ключ</button>
                <button type="button" class="quiz__option" data-value="design">Дизайнерский</button>
              </div>
            </div>

            <div class="quiz__step" data-step="2">
              <h3>Сколько комнат в квартире?</h3>
              <div class="quiz__options" data-field="rooms">
                <button type="button" class="quiz__option" data-value="1">1 комната</button>
                <button type="button" class="quiz__option" data-value="2">2 комнаты</button>
                <button type="button" class="quiz__option" data-value="3">3 комнаты</button>
                <button type="button" class="quiz__option" data-value="4">4 и более</button>
              </div>
            </div>

            <div class="quiz__step" data-step="3">
              <h3>Какая площадь квартиры?</h3>
              <div class="quiz__options" data-field="area">
                <button type="button" class="quiz__option" data-value="30">до 30 м²</button>
                <button type="button" class="quiz__option" data-value="50">30–50 м²</button>
                <button type="button" class="quiz__option" data-value="70">50–70 м²</button>
                <button type="button" class="quiz__option" data-value="100">70–100 м²</button>
                <button type="button" class="quiz__option" data-value="120">более 100 м²</button>
              </div>
            </div>

            <div class="quiz__step" data-step="4">
              <h3>Текущее состояние квартиры?</h3>
              <div class="quiz__options" data-field="condition">
                <button type="button" class="quiz__option" data-value="shell">Черновая отделка / новостройка</button>
                <button type="button" class="quiz__option" data-value="old">Требует полного обновления</button>
                <button type="button" class="quiz__option" data-value="good">Хорошее состояние, нужна косметика</button>
              </div>
            </div>

            <div class="quiz__step" data-step="5">
              <h3>Куда прислать расчёт?</h3>
              <p class="quiz__hint">Покажем вилку цены и свяжемся, чтобы уточнить детали и подтвердить точную смету.</p>
              <div class="quiz__form">
                <input type="text" name="name" id="quizName" placeholder="Ваше имя" autocomplete="name" required>
                <input type="tel" name="phone" id="quizPhone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
                <button type="submit" class="btn btn--primary btn--lg btn--block">Показать расчёт</button>
              </div>
            </div>

            <div class="quiz__step" data-step="result">
              <div class="quiz__result">
                <span class="quiz__result-label">Примерная стоимость вашего ремонта</span>
                <span class="quiz__result-price" id="quizResultPrice">—</span>
                <p>Спасибо<span id="quizResultName"></span>! Это предварительный расчёт. Наш менеджер свяжется с вами в ближайшее время, чтобы уточнить детали и подтвердить точную смету.</p>
                <a class="btn btn--dark btn--lg" href="<?php echo esc_attr(rp_tel_href($phone)); ?>">Позвонить сейчас</a>
              </div>
            </div>

            <div class="quiz__nav">
              <button type="button" class="quiz__back" id="quizBack" hidden>← Назад</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <?php return ob_get_clean();
}
add_shortcode('quiz_calculator', 'rp_sc_quiz_calculator');

/** [faq_list items="landing"] — рендер списка вопросов-ответов ACF-репитера текущей страницы. */
function rp_render_faq_list($rows) {
    if (empty($rows)) return;
    foreach ($rows as $row) {
        $q = $row['question'] ?? '';
        $a = $row['answer'] ?? '';
        if (!$q) continue;
        ?>
        <details class="faq2__item reveal">
          <summary><?php echo esc_html($q); ?><span class="faq2__btn" aria-hidden="true"></span></summary>
          <div class="faq2__answer"><p><?php echo esc_html($a); ?></p></div>
        </details>
        <?php
    }
}
