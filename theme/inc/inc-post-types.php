<?php
/**
 * Дополнительные настройки контента.
 * Отдельные типы записей не нужны — блог использует стандартный post,
 * все остальные разделы — page с разными шаблонами (WordPress сам находит
 * их по комментарию "Template Name:" в начале файла).
 */

if (!defined('ABSPATH')) exit;

add_image_size('rp-hero', 900, 620, true);
add_image_size('rp-card', 480, 300, true);

/** Категория блога по умолчанию — используется при импорте статей в inc-activation.php. */
function rp_default_blog_category_id() {
    $term = get_term_by('slug', 'remont-kvartir', 'category');
    if ($term) return $term->term_id;
    $created = wp_insert_term('Ремонт квартир', 'category', ['slug' => 'remont-kvartir']);
    return is_wp_error($created) ? 1 : $created['term_id'];
}
