<?php
/**
 * Template Name: Страница «Вопросы и ответы»
 * Агрегирует FAQ со всех посадочных страниц сайта (учитывая правки в ACF на каждой
 * из них) — сгруппировано по разделам меню, с быстрой навигацией и FAQPage-схемой.
 */
if (!defined('ABSPATH')) exit;

get_header();

$hero_h1 = get_the_title() ?: 'Вопросы и ответы';
$hero_lead_default = 'Собрали {total} вопросов и ответов со всех страниц сайта — по услугам, типам квартир и ценам. Не нашли ответ на свой вопрос? Позвоните нам или оставьте заявку — ответим лично.';
$hero_lead = rp_field('hero_lead', $hero_lead_default);
$hero_image = rp_image_url('hero_image', rp_default_img('2149385435.jpg'));

$groups = [
    'uslugi' => ['Вопросы об услугах', [
        'remont-pod-klyuch-v-moskve', 'kosmeticheskiy-remont-kvartiry', 'kapitalnyy-remont-kvartiry',
        'otdelka-kvartir-v-moskve', 'sovremennyy-remont-kvartiry', 'dizaynerskiy-remont-kvartiry',
        'dizayn-i-remont-kvartir-v-moskve', 'remont-elitnyh-kvartir', 'remont-vanny-v-kvartire',
        'remont-koridora-v-kvartire', 'uslugi-remonta-kvartir',
    ]],
    'tipy-kvartir' => ['Вопросы о типах квартир', [
        'remont-odnokomnatnoy-kvartiry', 'remont-dvuhkomnatnoy-kvartiry', 'remont-kvartiry-studii',
        'remont-trehkomnatnoy-kvartiry', 'remont-4-komnatnoy-kvartiry', 'remont-kvartiry-v-novostroyke',
        'remont-vtorichki-v-moskve',
    ]],
    'ceny' => ['Вопросы о ценах', ['ceny-remonta-kvartiry']],
];

$toc = [];
$sections = [];
$schema_entities = [];
$total = 0;

foreach ($groups as $group_id => [$group_title, $slugs]) {
    $blocks = [];
    foreach ($slugs as $slug) {
        $page = get_page_by_path($slug);
        if (!$page) continue;
        $label = rp_page_h1($page);
        $faq = rp_page_faq($page);
        if (empty($faq)) continue;
        $anchor = $group_id . '-' . $slug;
        $toc[] = ['anchor' => $anchor, 'label' => $label];
        $blocks[] = ['anchor' => $anchor, 'label' => $label, 'url' => get_permalink($page), 'faq' => $faq];
        foreach ($faq as $item) {
            if (empty($item['question'])) continue;
            $total++;
            $schema_entities[] = [
                '@type' => 'Question',
                'name' => wp_strip_all_tags($item['question']),
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => wp_strip_all_tags($item['answer'] ?? '')],
            ];
        }
    }
    if ($blocks) $sections[] = ['title' => $group_title, 'blocks' => $blocks];
}

$schema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $schema_entities];
add_action('wp_head', function () use ($schema) {
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
});
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
        <p class="page-hero__lead"><?php echo esc_html(str_replace('{total}', (string) $total, $hero_lead)); ?></p>
        <a class="btn btn--primary btn--lg" href="<?php echo esc_url(home_url('/#contacts')); ?>">Задать вопрос</a>
        <?php echo do_shortcode('[hero_trust]'); ?>
      </div>
      <div class="ph page-hero__media reveal" data-bg="<?php echo esc_url($hero_image); ?>" data-label="Фото · 900×620" aria-hidden="true"></div>
    </div>
  </div>
</section>

<!-- ============ TOC ============ -->
<section class="article">
  <div class="container">
    <div class="faq-toc reveal">
      <?php foreach ($toc as $t): ?>
        <a href="#<?php echo esc_attr($t['anchor']); ?>" class="faq-toc__chip"><?php echo esc_html($t['label']); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php foreach ($sections as $section): ?>
<section class="article" id="group-<?php echo esc_attr(sanitize_title($section['title'])); ?>">
  <div class="container">
    <h2 class="h-heavy pricing__title"><?php echo esc_html($section['title']); ?></h2>
    <?php foreach ($section['blocks'] as $block): ?>
    <div class="faq-group__block" id="<?php echo esc_attr($block['anchor']); ?>">
      <div class="faq-group__head">
        <h3><?php echo esc_html($block['label']); ?></h3>
        <a class="faq-group__link" href="<?php echo esc_url($block['url']); ?>">Смотреть страницу услуги →</a>
      </div>
      <div class="faq2__list">
        <?php rp_render_faq($block['faq']); ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endforeach; ?>

<?php echo do_shortcode('[quiz_calculator]'); ?>

<?php echo do_shortcode('[promo_banner eyebrow="Не нашли ответ на свой вопрос?"]'); ?>

<?php get_footer(); ?>
