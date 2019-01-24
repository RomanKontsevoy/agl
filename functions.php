<?php

remove_filter('the_content', 'wpautop'); // Отключаем автоформатирование в полном посте
remove_filter('the_excerpt', 'wpautop'); // Отключаем автоформатирование в кратком(анонсе) посте
remove_filter('comment_text', 'wpautop'); // Отключаем автоформатирование в комментариях
remove_filter('the_content', 'wptexturize'); // Отключаем автоформатирование в полном посте
remove_filter('the_excerpt', 'wptexturize'); // Отключаем автоформатирование в кратком(анонсе) посте
remove_filter('comment_text', 'wptexturize'); // Отключаем автоформатирование в комментариях

// END ENQUEUE PARENT ACTION

include_once "php/albums_handle.php"; // перенаправление, на обработку раздела альбомов
include_once "php/shedule_handle.php"; // перенаправление, на обработку раздела расписания
include_once "php/video_handle.php"; // перенаправление, на обработку раздела видео
include_once "php/article_handle.php"; // перенаправление, на обработку раздела приходской газеты
include_once "php/news_handle.php"; // перенаправление, на обработку раздела новостей
include_once "php/enqueue.php"; // перенаправление, на добавление стилей и скриптов

add_filter('show_admin_bar', '__return_false'); // отключить админ-бар

function get_meta_values( $meta_key, $post_type = 'post' ) { // Формирует список не повторяющихся значений мета полей
    $posts = get_posts(
        array(
            'post_type'      => $post_type,
            'meta_key'       => $meta_key,
            'posts_per_page' => - 1,
        )
    );
    $meta_values = array();
    foreach ( $posts as $post ) {
        $meta_values[] = get_post_meta( $post->ID, $meta_key, true );
    }
    return array_unique( $meta_values );
}

function random() { // произвольное число
    return (float)rand()/(float)getrandmax();
}