<?php
/**
 * При активации темы автоматически создаются все страницы и записи сайта
 * (если их ещё нет — повторная активация ничего не дублирует), назначаются
 * шаблоны, страница на главной и страница записей (блог).
 */

if (!defined('ABSPATH')) exit;

/** Список страниц: slug => [title, template]. */
function rp_activation_pages_list() {
    return [
        'glavnaya' => ['Главная', 'front-page.php'],

        // Услуги
        'remont-pod-klyuch-v-moskve' => ['Ремонт под ключ в Москве', 'page-landing.php'],
        'kosmeticheskiy-remont-kvartiry' => ['Косметический ремонт квартиры', 'page-landing.php'],
        'kapitalnyy-remont-kvartiry' => ['Капитальный ремонт квартиры', 'page-landing.php'],
        'otdelka-kvartir-v-moskve' => ['Отделка квартир в Москве', 'page-landing.php'],
        'sovremennyy-remont-kvartiry' => ['Современный ремонт квартиры', 'page-landing.php'],
        'dizaynerskiy-remont-kvartiry' => ['Дизайнерский ремонт квартиры', 'page-landing.php'],
        'dizayn-i-remont-kvartir-v-moskve' => ['Дизайн и ремонт квартир', 'page-landing.php'],
        'remont-elitnyh-kvartir' => ['Ремонт элитных квартир', 'page-landing.php'],
        'remont-vanny-v-kvartire' => ['Ремонт ванной', 'page-landing.php'],
        'remont-koridora-v-kvartire' => ['Ремонт коридора', 'page-landing.php'],
        'uslugi-remonta-kvartir' => ['Все услуги', 'page-hub-services.php'],

        // Типы квартир
        'remont-odnokomnatnoy-kvartiry' => ['Ремонт однокомнатной квартиры', 'page-landing.php'],
        'remont-dvuhkomnatnoy-kvartiry' => ['Ремонт двухкомнатной квартиры', 'page-landing.php'],
        'remont-kvartiry-studii' => ['Ремонт квартиры-студии', 'page-landing.php'],
        'remont-trehkomnatnoy-kvartiry' => ['Ремонт трёхкомнатной квартиры', 'page-landing.php'],
        'remont-4-komnatnoy-kvartiry' => ['Ремонт четырёхкомнатной квартиры', 'page-landing.php'],
        'remont-kvartiry-v-novostroyke' => ['Ремонт квартиры в новостройке', 'page-landing.php'],
        'remont-vtorichki-v-moskve' => ['Ремонт вторичного жилья', 'page-landing.php'],

        // Цены
        'ceny-remonta-kvartiry' => ['Цены на ремонт квартир', 'page-prices.php'],

        // Компания
        'o-kompanii' => ['О компании', 'page-about.php'],
        'otzyvy-o-remonte-kvartir' => ['Отзывы клиентов', 'page-reviews.php'],
        'voprosy-i-otvety' => ['Вопросы и ответы', 'page-faq.php'],

        // Служебные
        'sitemap' => ['Карта сайта', 'page-sitemap.php'],
        'blog' => ['Блог', 'page-blog.php'],
    ];
}

function rp_activation_create_pages() {
    $created_ids = [];
    foreach (rp_activation_pages_list() as $slug => [$title, $template]) {
        $existing = get_page_by_path($slug);
        if ($existing) {
            $created_ids[$slug] = $existing->ID;
            if (get_page_template_slug($existing->ID) !== $template) {
                update_post_meta($existing->ID, '_wp_page_template', $template);
            }
            continue;
        }
        $id = wp_insert_post([
            'post_title' => $title,
            'post_name' => $slug,
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_content' => '',
            'comment_status' => 'closed',
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', $template);
            $created_ids[$slug] = $id;
        }
    }
    return $created_ids;
}

function rp_activation_create_posts() {
    $category_id = rp_default_blog_category_id();
    $posts = rp_get_data('blog-posts');
    $created = 0;
    foreach ($posts as $p) {
        $existing = get_page_by_path($p['slug'], OBJECT, 'post');
        if ($existing) continue;
        $id = wp_insert_post([
            'post_title' => $p['title'],
            'post_name' => $p['slug'],
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_content' => $p['content'],
            'post_excerpt' => $p['meta_description'] ?? '',
            'post_category' => [$category_id],
            'comment_status' => 'closed',
        ], true);
        if (!is_wp_error($id)) $created++;
    }
    return $created;
}

function rp_activation_run() {
    if (!function_exists('acf_add_local_field_group')) {
        // Без ACF Pro активация всё равно создаёт страницы/записи — сайт откроется
        // на дефолтных текстах и картинках из кода, ACF-редактирование появится
        // после установки плагина.
    }

    $pages = rp_activation_create_pages();
    rp_activation_create_posts();

    if (!empty($pages['glavnaya'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $pages['glavnaya']);
    }
    if (!empty($pages['blog'])) {
        update_option('page_for_posts', $pages['blog']);
    }

    flush_rewrite_rules();
    update_option('rp_theme_activated', 1);
}
add_action('after_switch_theme', 'rp_activation_run');

/**
 * ACF регистрирует свои локальные группы полей на хуке acf/init, который срабатывает
 * позже after_switch_theme — на случай гонки хуков дополнительно прогоняем создание
 * страниц один раз через admin_init, если оно почему-то не выполнилось при активации.
 */
add_action('admin_init', function () {
    if (get_option('rp_theme_activated')) return;
    if (!current_user_can('manage_options')) return;
    rp_activation_run();
});
