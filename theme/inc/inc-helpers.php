<?php
/**
 * Хелперы «значение ACF, а если пусто — дефолт из кода».
 * Правило темы: картинки и тексты редактируются в ACF, всё остальное (SVG-иконки,
 * структура блоков) — только в коде.
 */

if (!defined('ABSPATH')) exit;

/**
 * Значение простого ACF-поля (текст/textarea/wysiwyg/select…) с дефолтом из кода.
 */
function rp_field($selector, $default = '', $post_id = null) {
    if ($post_id === null) {
        $post_id = get_the_ID();
    }
    if (!function_exists('get_field')) {
        return $default;
    }
    $value = get_field($selector, $post_id);
    if ($value === '' || $value === null || $value === false) {
        return $default;
    }
    return $value;
}

/** Экранированный вывод rp_field(). */
function rp_e($selector, $default = '', $post_id = null) {
    echo esc_html(rp_field($selector, $default, $post_id));
}

/** Вывод rp_field() как есть (для полей с HTML/wysiwyg). */
function rp_html($selector, $default = '', $post_id = null) {
    echo wp_kses_post(rp_field($selector, $default, $post_id));
}

/**
 * URL картинки: ACF Image field (может быть массивом или ID) -> URL,
 * либо переданный дефолтный URL, если поле не заполнено.
 */
function rp_image_url($selector, $default_url = '', $post_id = null) {
    if ($post_id === null) {
        $post_id = get_the_ID();
    }
    if (!function_exists('get_field')) {
        return $default_url;
    }
    $value = get_field($selector, $post_id);
    if (is_array($value) && !empty($value['url'])) {
        return $value['url'];
    }
    if (is_numeric($value)) {
        $url = wp_get_attachment_url($value);
        if ($url) return $url;
    }
    if (is_string($value) && $value !== '') {
        return $value;
    }
    return $default_url;
}

/**
 * Значение поля Options Page с дефолтом из кода.
 */
function rp_option($selector, $default = '') {
    return rp_field($selector, $default, 'option');
}

/**
 * Repeater-поле ACF: если пользователь заполнил хотя бы одну строку — берём его данные,
 * иначе отдаём массив по умолчанию из кода целиком (без слияния по строкам).
 */
function rp_repeater($selector, $default_rows = [], $post_id = null) {
    if ($post_id === null) {
        $post_id = get_the_ID();
    }
    if (!function_exists('get_field')) {
        return $default_rows;
    }
    $rows = get_field($selector, $post_id);
    if (is_array($rows) && count($rows) > 0) {
        return $rows;
    }
    return $default_rows;
}

/** URL картинки по умолчанию из темы (assets/img/remont/...). */
function rp_default_img($filename) {
    return RP_THEME_URI . '/assets/img/remont/' . ltrim($filename, '/');
}

/** tel: href из отображаемого номера телефона («+7 (977) 922-07-18» -> «tel:+79779220718»). */
function rp_tel_href($phone_display) {
    $digits = preg_replace('/[^0-9]/', '', $phone_display);
    return 'tel:+' . $digits;
}

/**
 * Ссылка на страницу темы по её "ключу" (slug), которым она была создана при активации.
 * Возвращает permalink, либо '#', если страница ещё не создана (тема только что установлена
 * без активации / страницу удалили).
 */
function rp_page_url($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page->ID);
    }
    return home_url('/' . $slug . '/');
}

/** Якорная ссылка на раздел страницы по её ключу. */
function rp_page_anchor($slug, $anchor) {
    return rp_page_url($slug) . '#' . $anchor;
}

/** Ссылка на статью блога по её slug (созданную при активации темы). */
function rp_post_url($slug) {
    $post = get_page_by_path($slug, OBJECT, 'post');
    if ($post) {
        return get_permalink($post->ID);
    }
    return home_url('/' . $slug . '/');
}

/** Ссылка на страницу "Блог" (посадочная страница записей). */
function rp_blog_url() {
    $page_for_posts = (int) get_option('page_for_posts');
    if ($page_for_posts) {
        return get_permalink($page_for_posts);
    }
    return home_url('/blog/');
}

/**
 * Загружает и кеширует файл данных по умолчанию из inc/data/{$key}.php.
 * Каждый такой файл возвращает PHP-массив (return [...]) с контентом,
 * которым была наполнена соответствующая страница на исходном сайте.
 */
function rp_get_data($key) {
    static $cache = [];
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }
    $path = RP_THEME_DIR . "/inc/data/{$key}.php";
    $data = file_exists($path) ? include $path : [];
    $cache[$key] = $data;
    return $data;
}

/** Дефолтные данные текущей посадочной страницы (page-landing.php) по её slug. */
function rp_landing_defaults($slug = null) {
    if ($slug === null) {
        $slug = get_post_field('post_name');
    }
    $all = rp_get_data('landing-pages');
    return $all[$slug] ?? [];
}

/** Дефолтные данные ЛЮБОЙ страницы темы по её шаблону (для агрегатора FAQ). */
function rp_page_defaults_by_template($post) {
    $template = get_page_template_slug($post->ID);
    $slug = $post->post_name;
    switch ($template) {
        case 'page-landing.php':
            return rp_get_data('landing-pages')[$slug] ?? [];
        case 'page-hub-services.php':
            return rp_get_data('hub');
        case 'page-prices.php':
            return rp_get_data('prices');
        case 'page-about.php':
            return rp_get_data('about');
        case 'page-reviews.php':
            return rp_get_data('reviews');
        default:
            return [];
    }
}

/** FAQ конкретной страницы (ACF-переопределение, иначе дефолт из кода) — для агрегатора «Вопросы и ответы». */
function rp_page_faq($post) {
    $post = get_post($post);
    if (!$post) return [];
    $defaults = rp_page_defaults_by_template($post);
    return rp_repeater('faq', $defaults['faq'] ?? [], $post->ID);
}

/** Дефолтные данные статьи блога (title/hero_lead/hero_image/content) по её slug. */
function rp_blog_defaults($slug = null) {
    if ($slug === null) {
        $slug = get_post_field('post_name');
    }
    static $indexed = null;
    if ($indexed === null) {
        $indexed = [];
        foreach (rp_get_data('blog-posts') as $post) {
            $indexed[$post['slug']] = $post;
        }
    }
    return $indexed[$slug] ?? [];
}

/** H1 страницы (ACF-переопределение, иначе дефолт из кода, иначе заголовок записи). */
function rp_page_h1($post) {
    $post = get_post($post);
    if (!$post) return '';
    $defaults = rp_page_defaults_by_template($post);
    return rp_field('hero_h1', $defaults['hero_h1'] ?? get_the_title($post), $post->ID);
}
