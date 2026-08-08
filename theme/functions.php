<?php
/**
 * РемонтПрофи theme bootstrap.
 */

if (!defined('ABSPATH')) exit;

define('RP_THEME_VERSION', '1.0.0');
define('RP_THEME_DIR', get_template_directory());
define('RP_THEME_URI', get_template_directory_uri());

/* ---------------------------------------------------------------
 * Theme setup
 * ------------------------------------------------------------- */
function rp_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('customize-selective-refresh-widgets');

    register_nav_menus([
        'primary' => 'Главное меню (не используется — меню собрано вручную в header.php)',
    ]);
}
add_action('after_setup_theme', 'rp_theme_setup');

/* ---------------------------------------------------------------
 * Assets
 * ------------------------------------------------------------- */
function rp_enqueue_assets() {
    wp_enqueue_style('rp-style', RP_THEME_URI . '/assets/css/style.css', [], RP_THEME_VERSION);
    wp_enqueue_script('rp-main', RP_THEME_URI . '/assets/js/main.js', [], RP_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'rp_enqueue_assets');

/* ---------------------------------------------------------------
 * Includes
 * ------------------------------------------------------------- */
require_once RP_THEME_DIR . '/inc/inc-helpers.php';
require_once RP_THEME_DIR . '/inc/inc-render.php';
require_once RP_THEME_DIR . '/inc/inc-acf.php';
require_once RP_THEME_DIR . '/inc/inc-shortcodes.php';
require_once RP_THEME_DIR . '/inc/inc-post-types.php';
require_once RP_THEME_DIR . '/inc/inc-activation.php';
require_once RP_THEME_DIR . '/inc/inc-cleanup.php';

/* ---------------------------------------------------------------
 * Misc
 * ------------------------------------------------------------- */

// noindex, nofollow — сайт закрытый/демонстрационный, как и исходный статический макет.
function rp_noindex() {
    echo '<meta name="robots" content="noindex, nofollow">' . "\n";
}
add_action('wp_head', 'rp_noindex', 1);

// Отключаем автогенерацию нескольких размеров картинок — на сайте используются оригиналы.
add_filter('big_image_size_threshold', '__return_false');
