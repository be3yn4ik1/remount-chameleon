<?php
/**
 * Подключение и настройка ACF Pro.
 *
 * ACF Pro считается уже установленным и активированным в системе (по условию задачи).
 * Все группы полей регистрируются локально через acf_add_local_field_group(), поэтому
 * тема не зависит от ручной синхронизации JSON — поля появляются сразу после активации темы.
 *
 * Редактировать в ACF можно только текст и картинки (см. README темы). SVG-иконки,
 * структура блоков и разметка — всегда в коде.
 */

if (!defined('ABSPATH')) exit;

if (!function_exists('acf_add_local_field_group')) {
    // ACF Pro не активен — тема продолжит работать на дефолтных данных из кода.
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p><strong>РемонтПрофи:</strong> для редактирования контента установите и активируйте плагин Advanced Custom Fields PRO. Без него сайт работает на текстах и картинках по умолчанию из кода темы.</p></div>';
    });
    return;
}

/* ---------------------------------------------------------------
 * Мелкие билдеры полей — чтобы не повторять одну и ту же структуру
 * (текст/картинка/повторитель «заголовок+текст») в каждой группе.
 * ------------------------------------------------------------- */

function rp_f_tab($label, $key) {
    return ['key' => $key, 'label' => $label, 'name' => '', 'type' => 'tab', 'placement' => 'top'];
}

function rp_f_text($key, $label, $name, $instructions = '') {
    return ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'text', 'instructions' => $instructions];
}

function rp_f_textarea($key, $label, $name, $instructions = '', $rows = 3) {
    return ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'textarea', 'rows' => $rows, 'instructions' => $instructions];
}

function rp_f_wysiwyg($key, $label, $name, $instructions = '') {
    return ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0, 'instructions' => $instructions];
}

function rp_f_image($key, $label, $name, $instructions = '') {
    return ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => $instructions ?: 'Если не загрузить — используется картинка по умолчанию из темы.'];
}

function rp_f_url($key, $label, $name, $instructions = '') {
    return ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'url', 'instructions' => $instructions];
}

function rp_f_number($key, $label, $name, $min = null, $max = null) {
    $f = ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'number'];
    if ($min !== null) $f['min'] = $min;
    if ($max !== null) $f['max'] = $max;
    return $f;
}

function rp_f_post_object($key, $label, $name, $post_type = ['post', 'page']) {
    return ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'post_object', 'post_type' => $post_type, 'return_format' => 'id', 'allow_null' => 1, 'ui' => 1];
}

/** Повторитель «заголовок + текст» (карточки преимуществ, этапы, пункты «что входит»). */
function rp_f_repeater_title_text($key, $label, $name, $title_label = 'Заголовок', $text_label = 'Текст', $with_title = true) {
    $sub_fields = [];
    if ($with_title) {
        $sub_fields[] = rp_f_text($key . '_title', $title_label, 'title');
    }
    $sub_fields[] = rp_f_textarea($key . '_text', $text_label, 'text', '', 2);
    return [
        'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'repeater',
        'layout' => 'block', 'button_label' => 'Добавить строку',
        'sub_fields' => $sub_fields,
    ];
}

/** Повторитель FAQ (вопрос/ответ). */
function rp_f_repeater_faq($key, $name = 'faq', $label = 'Вопросы и ответы') {
    return [
        'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'repeater',
        'layout' => 'block', 'button_label' => 'Добавить вопрос',
        'sub_fields' => [
            rp_f_text($key . '_q', 'Вопрос', 'question'),
            rp_f_textarea($key . '_a', 'Ответ', 'answer', '', 3),
        ],
    ];
}

/** Повторитель карточек цен (используется на услугах, «Все услуги», «Цены»). */
function rp_f_repeater_pricing($key, $name = 'pricing_cards', $label = 'Карточки цен') {
    return [
        'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'repeater',
        'layout' => 'block', 'button_label' => 'Добавить тариф',
        'sub_fields' => [
            rp_f_text($key . '_badge1', 'Бейдж 1 (срок)', 'badge1'),
            rp_f_text($key . '_badge2', 'Бейдж 2 (гарантия)', 'badge2'),
            rp_f_text($key . '_title', 'Название тарифа', 'title'),
            rp_f_text($key . '_price', 'Цена', 'price'),
            rp_f_image($key . '_image', 'Фото', 'image'),
            [
                'key' => $key . '_rows', 'label' => 'Строки сметы', 'name' => 'rows', 'type' => 'repeater',
                'layout' => 'table', 'button_label' => 'Добавить строку',
                'sub_fields' => [
                    rp_f_text($key . '_row_label', 'Пункт', 'label'),
                    rp_f_text($key . '_row_note', 'Примечание', 'note'),
                    rp_f_text($key . '_row_value', 'Значение', 'value'),
                ],
            ],
        ],
    ];
}

/** Повторитель строк «Цена за м²» / «Средняя стоимость» и т.п. */
function rp_f_repeater_price_row($key, $name, $label, $with_link = true) {
    $sub = [
        rp_f_text($key . '_name', 'Название', 'name'),
        rp_f_text($key . '_note', 'Примечание', 'note'),
        rp_f_text($key . '_price', 'Цена', 'price'),
    ];
    if ($with_link) {
        $sub[] = rp_f_post_object($key . '_link', 'Ссылка на страницу услуги', 'link', ['page']);
    }
    return ['key' => $key, 'label' => $label, 'name' => $name, 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить строку', 'sub_fields' => $sub];
}

/* ---------------------------------------------------------------
 * Options Page — общие настройки сайта (телефон, почта, мессенджеры,
 * цифры доверия). Показываются через шорткоды на каждой странице.
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Настройки сайта',
            'menu_title' => 'Настройки сайта',
            'menu_slug'  => 'rp-options',
            'capability' => 'manage_options',
            'icon_url'   => 'dashicons-admin-settings',
            'redirect'   => false,
        ]);
    }

    acf_add_local_field_group([
        'key' => 'group_rp_options',
        'title' => 'Настройки сайта',
        'fields' => [
            rp_f_tab('Контакты', 'tab_rp_opt_contacts'),
            rp_f_text('field_rp_phone', 'Телефон', 'phone', 'В формате +7 (977) 922-07-18'),
            rp_f_text('field_rp_email', 'Email', 'email'),
            rp_f_text('field_rp_address', 'Адрес', 'address'),
            rp_f_text('field_rp_hours', 'Часы работы', 'work_hours'),

            rp_f_tab('Мессенджеры', 'tab_rp_opt_msgr'),
            rp_f_url('field_rp_telegram', 'Ссылка на Telegram', 'telegram_url'),
            rp_f_url('field_rp_max', 'Ссылка на MAX', 'max_url'),

            rp_f_tab('Доверие и рейтинг', 'tab_rp_opt_trust'),
            rp_f_text('field_rp_rating', 'Рейтинг (Google)', 'rating_value'),
            rp_f_text('field_rp_years', 'Лет на рынке', 'years_on_market'),
            rp_f_text('field_rp_objects', 'Сданных объектов', 'objects_done'),
            rp_f_text('field_rp_recommend', 'Клиентов рекомендуют, %', 'clients_recommend'),
            rp_f_text('field_rp_warranty', 'Лет гарантии', 'warranty_years'),
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'rp-options']]],
    ]);
});

/* ---------------------------------------------------------------
 * Главная страница
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_front_page',
        'title' => 'Главная страница',
        'fields' => [
            rp_f_tab('Первый экран (Hero)', 'tab_rp_home_hero'),
            rp_f_text('field_rp_home_hero_title', 'Заголовок', 'hero_title'),
            rp_f_textarea('field_rp_home_hero_sub', 'Подзаголовок', 'hero_subtitle', '', 3),
            rp_f_image('field_rp_home_hero_img', 'Фото в первом экране', 'hero_image'),

            rp_f_tab('Блок: Услуги', 'tab_rp_home_services'),
            rp_f_text('field_rp_home_services_title', 'Заголовок блока', 'services_title'),

            rp_f_tab('Блок: Как заказать', 'tab_rp_home_process'),
            rp_f_image('field_rp_home_process_img', 'Фото в блоке «Заказать ремонт легко»', 'process_image'),

            rp_f_tab('Блок: Цены', 'tab_rp_home_pricing'),
            rp_f_text('field_rp_home_pricing_title', 'Заголовок блока', 'pricing_title'),

            rp_f_tab('Блок: Отзывы', 'tab_rp_home_reviews'),
            rp_f_text('field_rp_home_reviews_title', 'Заголовок блока', 'reviews_title'),

            rp_f_tab('Блок: Контакты', 'tab_rp_home_contacts'),
            rp_f_text('field_rp_home_contacts_title', 'Заголовок блока', 'contacts_title'),
        ],
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
    ]);
});

/* ---------------------------------------------------------------
 * Посадочная страница услуги / типа квартиры (page-landing.php)
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_landing',
        'title' => 'Посадочная страница',
        'fields' => [
            rp_f_tab('Hero', 'tab_rp_land_hero'),
            rp_f_text('field_rp_land_h1', 'Заголовок (H1)', 'hero_h1'),
            rp_f_textarea('field_rp_land_lead', 'Подзаголовок', 'hero_lead', '', 4),
            rp_f_image('field_rp_land_hero_img', 'Фото в шапке', 'hero_image'),

            rp_f_tab('Преимущества', 'tab_rp_land_features'),
            rp_f_repeater_title_text('field_rp_land_features', 'Карточки преимуществ', 'features'),

            rp_f_tab('Что входит', 'tab_rp_land_included'),
            rp_f_repeater_title_text('field_rp_land_included', 'Пункты списка', 'included', 'Пункт', 'Пункт', false),

            rp_f_tab('Этапы работ', 'tab_rp_land_process'),
            rp_f_text('field_rp_land_process_title', 'Заголовок блока этапов', 'process_title'),
            rp_f_repeater_title_text('field_rp_land_steps', 'Этапы', 'steps'),
            rp_f_image('field_rp_land_process_img', 'Фото этапов', 'process_image'),

            rp_f_tab('Цены', 'tab_rp_land_pricing'),
            rp_f_text('field_rp_land_pricing_title', 'Заголовок блока цен', 'pricing_title'),
            rp_f_repeater_pricing('field_rp_land_pricing', 'pricing_cards', 'Тарифы'),

            rp_f_tab('FAQ', 'tab_rp_land_faq'),
            rp_f_repeater_faq('field_rp_land_faq'),

            rp_f_tab('Блог', 'tab_rp_land_blog'),
            [
                'key' => 'field_rp_land_blog_links', 'label' => 'Статьи блога (до 3)', 'name' => 'blog_links',
                'type' => 'relationship', 'post_type' => ['post'], 'filters' => ['search'],
                'max' => 3, 'return_format' => 'id',
            ],

            rp_f_tab('SEO-текст', 'tab_rp_land_seo'),
            rp_f_wysiwyg('field_rp_land_seo', 'Текст в конце страницы', 'seo_text'),
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-landing.php']]],
    ]);
});

/* ---------------------------------------------------------------
 * Хаб «Все услуги»
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_hub',
        'title' => 'Страница «Все услуги»',
        'fields' => [
            rp_f_tab('Hero', 'tab_rp_hub_hero'),
            rp_f_text('field_rp_hub_h1', 'Заголовок (H1)', 'hero_h1'),
            rp_f_textarea('field_rp_hub_lead', 'Подзаголовок', 'hero_lead', '', 4),
            rp_f_image('field_rp_hub_hero_img', 'Фото в шапке', 'hero_image'),

            rp_f_tab('Цены', 'tab_rp_hub_pricing'),
            rp_f_repeater_pricing('field_rp_hub_pricing', 'pricing_cards', 'Тарифы'),

            rp_f_tab('FAQ', 'tab_rp_hub_faq'),
            rp_f_repeater_faq('field_rp_hub_faq'),

            rp_f_tab('SEO-текст', 'tab_rp_hub_seo'),
            rp_f_wysiwyg('field_rp_hub_seo', 'Текст в конце страницы', 'seo_text'),
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-hub-services.php']]],
    ]);
});

/* ---------------------------------------------------------------
 * Страница «Цены»
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_prices',
        'title' => 'Страница «Цены»',
        'fields' => [
            rp_f_tab('Hero', 'tab_rp_price_hero'),
            rp_f_text('field_rp_price_h1', 'Заголовок (H1)', 'hero_h1'),
            rp_f_textarea('field_rp_price_lead', 'Подзаголовок', 'hero_lead', '', 4),
            rp_f_image('field_rp_price_hero_img', 'Фото в шапке', 'hero_image'),

            rp_f_tab('Сколько стоит', 'tab_rp_price_skolko'),
            rp_f_repeater_pricing('field_rp_price_skolko', 'skolko_pcards', 'Тарифы (обзор)'),

            rp_f_tab('Цена за м²', 'tab_rp_price_m2'),
            rp_f_repeater_price_row('field_rp_price_m2', 'price_per_m2', 'Расценки по услугам'),
            rp_f_repeater_price_row('field_rp_price_m2_extra', 'price_per_m2_extra', 'Расценки за помещение целиком'),

            rp_f_tab('Средняя стоимость', 'tab_rp_price_avg'),
            rp_f_repeater_price_row('field_rp_price_avg', 'avg_cost', 'Стоимость по площади квартиры', false),

            rp_f_tab('Недорогой ремонт', 'tab_rp_price_nedorogo'),
            rp_f_repeater_title_text('field_rp_price_nedorogo', 'Карточки', 'nedorogo_feat'),

            rp_f_tab('Под ключ', 'tab_rp_price_podklyuch'),
            rp_f_repeater_pricing('field_rp_price_podklyuch', 'pod_klyuch', 'Тарифы под ключ'),

            rp_f_tab('FAQ', 'tab_rp_price_faq'),
            rp_f_repeater_faq('field_rp_price_faq'),

            rp_f_tab('SEO-текст', 'tab_rp_price_seo'),
            rp_f_wysiwyg('field_rp_price_seo', 'Текст в конце страницы', 'seo_text'),
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-prices.php']]],
    ]);
});

/* ---------------------------------------------------------------
 * Страница «О компании»
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_about',
        'title' => 'Страница «О компании»',
        'fields' => [
            rp_f_tab('Hero', 'tab_rp_about_hero'),
            rp_f_text('field_rp_about_h1', 'Заголовок (H1)', 'hero_h1'),
            rp_f_textarea('field_rp_about_lead', 'Подзаголовок', 'hero_lead', '', 4),
            rp_f_image('field_rp_about_hero_img', 'Фото в шапке', 'hero_image'),

            rp_f_tab('О компании', 'tab_rp_about_o'),
            rp_f_wysiwyg('field_rp_about_o_text', 'Текст раздела', 'o_kompanii_text'),
            rp_f_repeater_title_text('field_rp_about_features', 'Карточки преимуществ', 'features'),

            rp_f_tab('Наши мастера', 'tab_rp_about_mastera'),
            rp_f_wysiwyg('field_rp_about_mastera_text', 'Текст раздела', 'mastera_text'),

            rp_f_tab('Без посредников', 'tab_rp_about_bez'),
            rp_f_repeater_title_text('field_rp_about_bez_steps', 'Пункты', 'bez_posrednikov_steps'),

            rp_f_tab('Профессиональный подход', 'tab_rp_about_pro'),
            rp_f_wysiwyg('field_rp_about_pro_text', 'Текст раздела', 'professionalnyy_podhod_text'),

            rp_f_tab('FAQ', 'tab_rp_about_faq'),
            rp_f_repeater_faq('field_rp_about_faq'),
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-about.php']]],
    ]);
});

/* ---------------------------------------------------------------
 * Страница «Отзывы»
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_reviews',
        'title' => 'Страница «Отзывы»',
        'fields' => [
            rp_f_tab('Hero', 'tab_rp_rev_hero'),
            rp_f_text('field_rp_rev_h1', 'Заголовок (H1)', 'hero_h1'),
            rp_f_textarea('field_rp_rev_lead', 'Подзаголовок', 'hero_lead', '', 4),
            rp_f_image('field_rp_rev_hero_img', 'Фото в шапке', 'hero_image'),

            rp_f_tab('Отзывы', 'tab_rp_rev_list'),
            [
                'key' => 'field_rp_reviews_list', 'label' => 'Отзывы', 'name' => 'reviews', 'type' => 'repeater',
                'layout' => 'block', 'button_label' => 'Добавить отзыв',
                'sub_fields' => [
                    rp_f_text('field_rp_rev_initials', 'Инициалы (для аватара)', 'initials'),
                    rp_f_text('field_rp_rev_name', 'Имя', 'name'),
                    rp_f_text('field_rp_rev_count', 'Подпись под именем', 'count_label'),
                    rp_f_number('field_rp_rev_rating', 'Оценка (1-5)', 'rating', 1, 5),
                    rp_f_text('field_rp_rev_ago', 'Дата (текстом)', 'ago_label'),
                    rp_f_textarea('field_rp_rev_text', 'Текст отзыва', 'text', '', 3),
                    rp_f_text('field_rp_rev_tag', 'Название услуги (бейдж)', 'tag_label'),
                    rp_f_post_object('field_rp_rev_tag_link', 'Ссылка на услугу', 'tag_link', ['page']),
                ],
            ],

            rp_f_tab('FAQ', 'tab_rp_rev_faq'),
            rp_f_repeater_faq('field_rp_rev_faq'),
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-reviews.php']]],
    ]);
});

/* ---------------------------------------------------------------
 * Страница «Вопросы и ответы» (сама страница — только hero;
 * содержимое агрегируется динамически со всех остальных страниц)
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_faq_page',
        'title' => 'Страница «Вопросы и ответы» — шапка',
        'fields' => [
            rp_f_textarea('field_rp_faq_lead', 'Подзаголовок (можно использовать {total} — число вопросов)', 'hero_lead', '', 4),
            rp_f_image('field_rp_faq_hero_img', 'Фото в шапке', 'hero_image'),
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-faq.php']]],
    ]);
});

/* ---------------------------------------------------------------
 * Запись блога
 * ------------------------------------------------------------- */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_rp_post',
        'title' => 'Статья блога — шапка',
        'fields' => [
            rp_f_image('field_rp_post_hero_img', 'Фото в шапке статьи', 'hero_image'),
            rp_f_textarea('field_rp_post_lead', 'Подзаголовок под заголовком', 'hero_lead', '', 3),
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'post']]],
    ]);
});
