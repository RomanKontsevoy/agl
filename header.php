<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <title></title>
<!--    <title>Приход храмов преподобного Агапита Печерского и святителя Луки Крымского</title>-->
    <?php wp_head(); ?>

    <!--favicon [-->

    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon.ico">
    <link rel="icon" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-16x16.png">
    <link rel="icon" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-32x32.png">
    <link rel="icon" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-96x96.png">

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-180x180.png">

    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="msapplication-config" content="<?php echo get_template_directory_uri(); ?>/img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!--] favicon-->

    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i|Open+Sans:300,300i,400,400i,700,700i,800,800i&amp;subset=cyrillic-ext" rel="stylesheet">
</head>

<body>

<header>
    <div class="header-wrap">
        <div class="wrapper">
            <div class="icon-img"></div>
            <div class="icon-img"></div>
            <div class="header-info">
                <a href="<?php echo get_home_url(); ?>" class="parish-name">Приход храмов преподобного<br>
                    Агапита Печерского <span class="nw">и святителя</span><br>
                    Луки Крымского
                </a>
                <div class="chirch-name">Украинская Православная Церковь,<br>
                    Киевская Митрополия
                </div>
            </div>
        </div>
    </div>


    <div class="menu-main-menu-container">
        <div class="mobile-menu">
            <div id="burger">
                <div class="mobile-menu__nice"></div>
                <div class="mobile-menu__nice"></div>
                <div class="mobile-menu__nice"></div>
            </div>
        </div>
        <?php

        wp_nav_menu( array(
            'menu'            => '',              // (string) Название выводимого меню (указывается в админке при создании меню, приоритетнее
            // чем указанное местоположение theme_location - если указано, то параметр theme_location игнорируется)
            'container'       => '',           // (string) Контейнер меню. Обворачиватель ul. Указывается тег контейнера (по умолчанию в тег div)
            'container_class' => '',              // (string) class контейнера (div тега)
            'container_id'    => 'menu-main-menu',              // (string) id контейнера (div тега)
            'menu_class'      => 'menu',          // (string) class самого меню (ul тега)
            'menu_id'         => '',              // (string) id самого меню (ul тега)
            'echo'            => true,            // (boolean) Выводить на экран или возвращать для обработки
            'fallback_cb'     => 'wp_page_menu',  // (string) Используемая (резервная) функция, если меню не существует (не удалось получить)
            'before'          => '',              // (string) Текст перед <a> каждой ссылки
            'after'           => '',              // (string) Текст после </a> каждой ссылки
            'link_before'     => '',              // (string) Текст перед анкором (текстом) ссылки
            'link_after'      => '',              // (string) Текст после анкора (текста) ссылки
            'depth'           => 0,               // (integer) Глубина вложенности (0 - неограничена, 2 - двухуровневое меню)
            'walker'          => '',              // (object) Класс собирающий меню. Default: new Walker_Nav_Menu
            'theme_location'  => ''               // (string) Расположение меню в шаблоне. (указывается ключ которым было зарегистрировано меню в функции register_nav_menus)
        ) );
        ?>
    </div>

</header>