<?php
/**
 * Общая шапка сайта: <head>, мега-меню.
 * Ссылки меню собраны вручную (а не через wp_nav_menu), т.к. структура — сложное
 * многоколоночное мега-меню, которое проще и надёжнее держать в коде.
 */
if (!defined('ABSPATH')) exit;

$phone = rp_option('phone', '+7 (977) 922-07-18');
$hours = rp_option('work_hours', 'Ежедневно с 08:00 до 22:00');
?><!DOCTYPE html>
<html lang="ru" <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#2C5EAD">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">Перейти к содержимому</a>

<div class="page">

  <!-- ============ HEADER ============ -->
  <header class="hdr" id="hdr">
    <div class="container hdr__top">
      <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="РемонтПрофи — на главную">
        <span class="brand__logo">Ремонт<span>Профи</span></span>
        <span class="brand__slogan">делаем ремонт под ключ</span>
      </a>

      <p class="hdr__tagline">Ремонт квартир под ключ любого объёма<br>по Москве и области</p>

      <div class="hdr__contact">
        <div class="messengers" aria-label="Мессенджеры">
          <span class="online"><i></i>Задайте вопрос,<br>мы онлайн</span>
          <?php echo do_shortcode('[messenger_icons]'); ?>
        </div>
        <div class="hdr__phone-wrap">
          <a class="hdr__phone" href="<?php echo esc_attr(rp_tel_href($phone)); ?>"><?php echo esc_html($phone); ?></a>
          <span class="hdr__hours"><b class="tag-online">Online</b> <?php echo esc_html($hours); ?></span>
        </div>
      </div>

      <button class="burger" id="burger" aria-label="Открыть меню" aria-expanded="false" aria-controls="nav">
        <span></span><span></span><span></span>
      </button>
    </div>

    <nav class="nav" id="nav" aria-label="Главное меню">
      <div class="container nav__inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav__link<?php echo is_front_page() ? ' is-active' : ''; ?>">Главная</a>

        <div class="nav__item has-drop">
          <button class="nav__link nav__toggle" aria-expanded="false">Услуги
            <svg class="nav__caret" viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="drop">
            <div class="drop__col">
              <span class="drop__title">По типу работ</span>
              <a href="<?php echo esc_url(rp_page_url('remont-pod-klyuch-v-moskve')); ?>">Ремонт под ключ</a>
              <a href="<?php echo esc_url(rp_page_url('kosmeticheskiy-remont-kvartiry')); ?>">Косметический ремонт</a>
              <a href="<?php echo esc_url(rp_page_url('kapitalnyy-remont-kvartiry')); ?>">Капитальный ремонт</a>
              <a href="<?php echo esc_url(rp_page_url('otdelka-kvartir-v-moskve')); ?>">Отделка квартир</a>
              <a href="<?php echo esc_url(rp_page_url('sovremennyy-remont-kvartiry')); ?>">Современный ремонт</a>
            </div>
            <div class="drop__col">
              <span class="drop__title">Дизайн</span>
              <a href="<?php echo esc_url(rp_page_url('dizaynerskiy-remont-kvartiry')); ?>">Дизайнерский ремонт</a>
              <a href="<?php echo esc_url(rp_page_url('dizayn-i-remont-kvartir-v-moskve')); ?>">Дизайн и ремонт</a>
              <a href="<?php echo esc_url(rp_post_url('dizayn-interyera-i-remont-kvartiry')); ?>">Дизайн интерьера</a>
              <a href="<?php echo esc_url(rp_page_url('remont-elitnyh-kvartir')); ?>">Ремонт элитных квартир</a>
            </div>
            <div class="drop__col">
              <span class="drop__title">Помещения</span>
              <a href="<?php echo esc_url(rp_page_url('remont-vanny-v-kvartire')); ?>">Ремонт ванной</a>
              <a href="<?php echo esc_url(rp_page_url('remont-koridora-v-kvartire')); ?>">Ремонт коридора</a>
              <a href="<?php echo esc_url(rp_page_url('uslugi-remonta-kvartir')); ?>">Все услуги</a>
            </div>
          </div>
        </div>

        <div class="nav__item has-drop">
          <button class="nav__link nav__toggle" aria-expanded="false">Типы квартир
            <svg class="nav__caret" viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="drop">
            <div class="drop__col">
              <span class="drop__title">По комнатам</span>
              <a href="<?php echo esc_url(rp_page_url('remont-odnokomnatnoy-kvartiry')); ?>">Однокомнатная</a>
              <a href="<?php echo esc_url(rp_page_url('remont-dvuhkomnatnoy-kvartiry')); ?>">Двухкомнатная</a>
              <a href="<?php echo esc_url(rp_page_url('remont-trehkomnatnoy-kvartiry')); ?>">Трёхкомнатная</a>
              <a href="<?php echo esc_url(rp_page_url('remont-4-komnatnoy-kvartiry')); ?>">Четырёхкомнатная</a>
              <a href="<?php echo esc_url(rp_page_url('remont-kvartiry-studii')); ?>">Квартира-студия</a>
            </div>
            <div class="drop__col">
              <span class="drop__title">По типу дома</span>
              <a href="<?php echo esc_url(rp_page_url('remont-kvartiry-v-novostroyke')); ?>">В новостройке</a>
              <a href="<?php echo esc_url(rp_page_url('remont-vtorichki-v-moskve')); ?>">Вторичное жильё</a>
            </div>
          </div>
        </div>

        <div class="nav__item has-drop">
          <button class="nav__link nav__toggle" aria-expanded="false">Цены
            <svg class="nav__caret" viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="drop">
            <div class="drop__col">
              <span class="drop__title">Расчёт стоимости</span>
              <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'kalkulyator')); ?>">Калькулятор ремонта</a>
              <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'skolko-stoit')); ?>">Сколько стоит ремонт</a>
              <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'cena-za-m2')); ?>">Цена за 1 м²</a>
            </div>
            <div class="drop__col">
              <span class="drop__title">Выгодные цены</span>
              <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'srednyaya-stoimost')); ?>">Средняя стоимость</a>
              <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'nedorogo')); ?>">Недорогой ремонт</a>
              <a href="<?php echo esc_url(rp_page_anchor('ceny-remonta-kvartiry', 'pod-klyuch')); ?>">Стоимость под ключ</a>
            </div>
          </div>
        </div>

        <div class="nav__item has-drop">
          <button class="nav__link nav__toggle" aria-expanded="false">Компания
            <svg class="nav__caret" viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="drop drop--narrow">
            <div class="drop__col">
              <span class="drop__title">О нас</span>
              <a href="<?php echo esc_url(rp_page_anchor('o-kompanii', 'o-kompanii')); ?>">О компании</a>
              <a href="<?php echo esc_url(rp_page_anchor('o-kompanii', 'mastera')); ?>">Наши мастера</a>
              <a href="<?php echo esc_url(rp_page_anchor('o-kompanii', 'bez-posrednikov')); ?>">Без посредников</a>
              <a href="<?php echo esc_url(rp_page_anchor('o-kompanii', 'professionalnyy-podhod')); ?>">Профессиональный подход</a>
            </div>
            <div class="drop__col">
              <span class="drop__title">Разделы</span>
              <a href="<?php echo esc_url(rp_page_url('uslugi-remonta-kvartir')); ?>">Услуги</a>
              <a href="<?php echo esc_url(rp_page_url('ceny-remonta-kvartiry')); ?>">Цены</a>
              <a href="<?php echo esc_url(rp_page_url('otzyvy-o-remonte-kvartir')); ?>">Отзывы</a>
              <a href="<?php echo esc_url(rp_page_url('voprosy-i-otvety')); ?>">Вопросы и ответы</a>
            </div>
          </div>
        </div>

        <a href="<?php echo esc_url(rp_blog_url()); ?>" class="nav__link">Блог</a>
        <a href="<?php echo esc_url(is_front_page() ? '#contacts' : home_url('/#contacts')); ?>" class="nav__link">Контакты</a>
      </div>
    </nav>
  </header>
  <div class="nav-overlay" id="navOverlay" hidden></div>

  <main id="main">
