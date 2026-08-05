<?php
/**
 * Общие функции вывода повторяющихся блоков разметки (карточки преимуществ,
 * этапы, карточки цен, FAQ, карточки блога). Используются в шаблонах
 * page-landing.php, page-hub-services.php, page-prices.php, page-about.php,
 * page-reviews.php — чтобы не дублировать разметку.
 *
 * Иконки SVG для карточек преимуществ и «что входит» фиксированы в коде
 * (подбираются циклически по индексу) — редактируются только заголовок и текст.
 */

if (!defined('ABSPATH')) exit;

const RP_FEATURE_ICONS = [
    '<path d="M6 3h9l3 3v15H6z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 12h6M9 16h6M9 8h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
    '<path d="M12 3l8 4v5c0 5-3.4 8-8 9-4.6-1-8-4-8-9V7z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
    '<path d="M8 21V4h5a4 4 0 1 1 0 8H8m0-3h9M6 15h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
    '<circle cx="9" cy="8" r="3" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="16" cy="9" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 20c0-3 2.5-5 5-5s5 2 5 5M14 20c0-2.5 1.8-4.3 4-4.3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
    '<path d="M3 21V10l9-6 9 6v11h-6v-7H9v7z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
    '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
];

/** Картинка карточки/шага: ACF-подполе image (массив) либо дефолтное имя файла (строка). */
function rp_row_image_url($row, $key = 'image') {
    if (empty($row[$key])) return '';
    if (is_array($row[$key])) {
        return $row[$key]['url'] ?? '';
    }
    return rp_default_img($row[$key]);
}

function rp_render_features($features) {
    foreach ($features as $i => $f) {
        $icon = RP_FEATURE_ICONS[$i % count(RP_FEATURE_ICONS)];
        ?>
        <article class="feat reveal">
          <div><h3><?php echo esc_html($f['title'] ?? ''); ?></h3><p><?php echo esc_html($f['text'] ?? ''); ?></p></div>
          <span class="feat__ic"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><?php echo $icon; ?></svg></span>
        </article>
        <?php
    }
}

/** «Что входит»: принимает либо простой массив строк, либо массив [['text'=>...]] из ACF. */
function rp_render_included_list($items) {
    foreach ($items as $item) {
        $text = is_array($item) ? ($item['text'] ?? '') : $item;
        if ($text === '') continue;
        echo '<li>' . esc_html($text) . "</li>\n";
    }
}

function rp_render_steps($steps) {
    $n = 1;
    $total = count($steps);
    foreach ($steps as $s) {
        $hl = ($n === $total) ? ' step2--hl' : '';
        ?>
        <li class="step2<?php echo $hl; ?> reveal"><div><h3><?php echo esc_html($s['title'] ?? ''); ?></h3><p><?php echo esc_html($s['text'] ?? ''); ?></p></div><span class="step2__n"><?php echo $n; ?></span></li>
        <?php
        $n++;
    }
}

function rp_render_pricing_cards($cards) {
    foreach ($cards as $i => $c) {
        $image = rp_row_image_url($c);
        $rows = $c['rows'] ?? [];
        ?>
        <article class="pcard reveal">
          <span class="pcard__ghost"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
          <div class="ph pcard__media" data-bg="<?php echo esc_url($image); ?>" data-label="Фото"></div>
          <div class="pcard__badges"><span><?php echo esc_html($c['badge1'] ?? ''); ?></span><span><?php echo esc_html($c['badge2'] ?? ''); ?></span></div>
          <h3><?php echo esc_html($c['title'] ?? ''); ?></h3>
          <span class="pcard__price"><?php echo esc_html($c['price'] ?? ''); ?></span>
          <ul class="pcard__rows">
            <?php foreach ($rows as $row): ?>
            <li><span><?php echo esc_html($row['label'] ?? ''); ?><?php if (!empty($row['note'])): ?><em><?php echo esc_html($row['note']); ?></em><?php endif; ?></span><b><?php echo esc_html($row['value'] ?? ''); ?></b></li>
            <?php endforeach; ?>
          </ul>
          <a class="btn btn--primary btn--block" href="<?php echo esc_url(home_url('/#contacts')); ?>">Заказать</a>
        </article>
        <?php
    }
}

function rp_render_faq($items) {
    foreach ($items as $item) {
        $q = $item['question'] ?? '';
        $a = $item['answer'] ?? '';
        if ($q === '') continue;
        ?>
        <details class="faq2__item reveal">
          <summary><?php echo esc_html($q); ?><span class="faq2__btn" aria-hidden="true"></span></summary>
          <div class="faq2__answer"><p><?php echo esc_html($a); ?></p></div>
        </details>
        <?php
    }
}

/**
 * Карточки блога: массив ID постов (ACF relationship) либо дефолтный массив
 * [['slug'=>..,'image'=>..,'title'=>..,'excerpt'=>..]] из кода.
 */
function rp_render_blog_cards($items) {
    foreach ($items as $item) {
        if (is_numeric($item)) {
            $post = get_post((int) $item);
            if (!$post) continue;
            $url = get_permalink($post);
            $title = get_the_title($post);
            $excerpt = get_the_excerpt($post);
            $img = get_the_post_thumbnail_url($post, 'rp-card');
            if (!$img) {
                $hero = get_field('hero_image', $post->ID);
                $img = is_array($hero) ? ($hero['url'] ?? '') : '';
            }
        } else {
            $url = rp_post_url($item['slug']);
            $title = $item['title'];
            $excerpt = $item['excerpt'];
            $img = rp_default_img($item['image']);
        }
        ?>
        <a class="blog-card reveal" href="<?php echo esc_url($url); ?>">
          <div class="ph blog-card__media" data-bg="<?php echo esc_url($img); ?>" data-label="Фото · 480×300"></div>
          <div class="blog-card__body">
            <h3 class="blog-card__title"><?php echo esc_html($title); ?></h3>
            <p class="blog-card__excerpt"><?php echo esc_html($excerpt); ?></p>
            <div class="blog-card__foot">
              <span class="blog-card__read">Читать статью</span>
              <span class="blog-card__arrow" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18"><path d="M5 12h14m-6-6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
            </div>
          </div>
        </a>
        <?php
    }
}

const RP_STAR_PATH = 'M510.652 185.902a27.16 27.16 0 0 0-23.425-18.71l-147.774-13.419-58.433-136.77C276.71 6.98 266.898.494 255.996.494s-20.715 6.487-25.023 16.534l-58.434 136.746-147.797 13.418A27.21 27.21 0 0 0 1.34 185.902c-3.371 10.368-.258 21.739 7.957 28.907l111.7 97.96-32.938 145.09c-2.41 10.668 1.73 21.696 10.582 28.094 4.757 3.438 10.324 5.188 15.937 5.188 4.84 0 9.64-1.305 13.95-3.883l127.468-76.184 127.422 76.184c9.324 5.61 21.078 5.097 29.91-1.305a27.22 27.22 0 0 0 10.582-28.094l-32.937-145.09 111.699-97.94a27.22 27.22 0 0 0 7.98-28.927m0 0';

/** Одна звезда (заданный клиентом SVG). $fill — цвет заливки: золотой для оценки, серый для остатка до 5. */
function rp_star_svg($size = 18, $fill = '#ffc107') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 511.987 511" aria-hidden="true"><path fill="' . $fill . '" d="' . RP_STAR_PATH . '"/></svg>';
}

/** Строка из 5 звёзд для рейтинга $rating (1-5), $size — размер иконки в пикселях. */
function rp_stars_row($rating, $size = 18) {
    $rating = max(0, min(5, (int) round($rating)));
    $html = '<span class="rev__stars rev__stars--svg" aria-label="' . esc_attr($rating . ' из 5') . '">';
    for ($i = 0; $i < $rating; $i++) $html .= rp_star_svg($size, '#ffc107');
    for ($i = $rating; $i < 5; $i++) $html .= rp_star_svg($size, '#e3e7ee');
    $html .= '</span>';
    return $html;
}

/** Карточка услуги в каталоге хаба «Все услуги» (.svc). */
function rp_svc_card($title, $text, $image, $href) {
    $arrow = '<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    ?>
    <article class="svc reveal">
      <div class="ph svc__media" data-bg="<?php echo esc_url($image); ?>" data-label="Фото · 560×360"></div>
      <div class="svc__body">
        <div><h3><?php echo esc_html($title); ?></h3><p><?php echo esc_html($text); ?></p></div>
        <a class="svc__arrow" href="<?php echo esc_url($href); ?>" aria-label="<?php echo esc_attr($title); ?>"><?php echo $arrow; ?></a>
      </div>
    </article>
    <?php
}

/** Строки таблицы цен (страница «Цены»): с ссылкой или без. */
function rp_render_price_table_rows($rows, $with_link = true) {
    foreach ($rows as $row) {
        $note = !empty($row['note']) ? '<span class="price-table__note">' . esc_html($row['note']) . '</span>' : '';
        if ($with_link) {
            $href = '#';
            if (!empty($row['link'])) {
                $href = is_numeric($row['link']) ? get_permalink((int) $row['link']) : rp_page_url(str_replace('.html', '', $row['link']));
            }
            ?>
            <a class="price-table__row reveal" href="<?php echo esc_url($href); ?>">
              <span class="price-table__name"><?php echo esc_html($row['name']); ?><?php echo $note; ?></span>
              <span class="price-table__price"><?php echo esc_html($row['price']); ?></span>
            </a>
            <?php
        } else {
            ?>
            <div class="price-table__row reveal">
              <span class="price-table__name"><?php echo esc_html($row['name']); ?><?php echo $note; ?></span>
              <span class="price-table__price"><?php echo esc_html($row['price']); ?></span>
            </div>
            <?php
        }
    }
}
