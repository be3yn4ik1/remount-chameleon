<?php
/**
 * Отключение лишнего в WordPress: блочный редактор (Гутенберг), проверки
 * обновлений, RSS-ленты, XML-RPC, эмодзи-скрипты и служебные ссылки в <head>.
 * Сайт статический по содержанию (контент правится через ACF), эти вещи не нужны.
 */

if (!defined('ABSPATH')) exit;

/* ---------------------------------------------------------------
 * Гутенберг (блочный редактор) — везде классический редактор
 * ------------------------------------------------------------- */
add_filter('use_block_editor_for_post', '__return_false', 100);
add_filter('use_block_editor_for_post_type', '__return_false', 100);
add_filter('use_widgets_block_editor', '__return_false');
remove_theme_support('core-block-patterns');

// Стили блочного редактора на фронтенде не нужны — их не грузим.
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}, 100);

/* ---------------------------------------------------------------
 * Проверки обновлений (ядро, плагины, темы) — не запрашиваем и не показываем
 * ------------------------------------------------------------- */
add_filter('auto_update_core', '__return_false');
add_filter('auto_update_plugin', '__return_false');
add_filter('auto_update_theme', '__return_false');
add_filter('pre_site_transient_update_core', '__return_null');
add_filter('pre_site_transient_update_plugins', '__return_null');
add_filter('pre_site_transient_update_themes', '__return_null');
remove_action('admin_init', '_maybe_update_core');
remove_action('admin_init', '_maybe_update_plugins');
remove_action('admin_init', '_maybe_update_themes');
add_filter('plugins_auto_update_enabled', '__return_false');

/* ---------------------------------------------------------------
 * RSS/Atom-ленты — на сайте не используются, редиректим на главную
 * ------------------------------------------------------------- */
function rp_disable_feed() {
    wp_redirect(home_url('/'), 301);
    exit;
}
add_action('do_feed', 'rp_disable_feed', 1);
add_action('do_feed_rdf', 'rp_disable_feed', 1);
add_action('do_feed_rss', 'rp_disable_feed', 1);
add_action('do_feed_rss2', 'rp_disable_feed', 1);
add_action('do_feed_atom', 'rp_disable_feed', 1);
add_action('do_feed_rss2_comments', 'rp_disable_feed', 1);
add_action('do_feed_atom_comments', 'rp_disable_feed', 1);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

/* ---------------------------------------------------------------
 * XML-RPC — не используется, потенциальная точка для брутфорса
 * ------------------------------------------------------------- */
add_filter('xmlrpc_enabled', '__return_false');

/* ---------------------------------------------------------------
 * Служебные ссылки в <head> и версия WordPress
 * ------------------------------------------------------------- */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('template_redirect', 'rest_output_link_header', 11);
add_filter('the_generator', '__return_empty_string');

/* ---------------------------------------------------------------
 * Эмодзи-скрипты и стили (лишний JS/CSS на каждой странице)
 * ------------------------------------------------------------- */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

/* ---------------------------------------------------------------
 * Комментарии не используются на сайте — убираем из меню админки
 * ------------------------------------------------------------- */
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
add_action('init', function () {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
});
