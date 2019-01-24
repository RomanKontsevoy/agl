<?php

/* create_album_post */

add_action( 'init', 'true_register_products' ); // Использовать функцию только внутри хука init

function true_register_products() {
    $labels = array(
        'name' => 'Альбомы',
        'singular_name' => 'Альбом', // админ панель Добавить->Функцию
        'add_new' => 'Добавить альбом',
        'add_new_item' => 'Добавить новый альбом', // заголовок тега <title>
        'edit_item' => 'Редактировать альбом',
        'new_item' => 'Новый альбом',
        'all_items' => 'Все альбомы',
        'view_item' => 'Просмотр альбомов на сайте',
        'search_items' => 'Искать альбомы',
        'not_found' =>  'Альбомов не найдено.',
        'not_found_in_trash' => 'В корзине нет альбомов.',
        'menu_name' => 'Альбомы' // ссылка в меню в админке
    );
    $args = array(
        'labels' => $labels,
        'public' => true, // благодаря этому некоторые параметры можно пропустить
        'menu_icon' => 'dashicons-format-gallery', // иконка корзины
        'menu_position' => 4, // 4-9 — под «Записи»
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'thumbnail'),
        'taxonomies' => array('post_tag')
    );
    register_post_type('album',$args);
}

// подключаем функцию активации мета блока (my_extra_fields)
add_action('add_meta_boxes', 'my_album_extra_fields', 1);

function my_album_extra_fields()
{
    add_meta_box('extra_fields', 'Дополнительные поля', 'extra_album_fields_box_func', 'album', 'normal', 'high');
}

// код блока
function extra_album_fields_box_func($post)
{
    ?>
    <p><b>Дата события:</b>
        <input type="date" name="extra[_date]" value="<?php echo get_post_meta($post->ID, '_date', 1); ?>">
    </p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>"/>

    <script>
        jQuery(function ($) {
            var btns = $('#wp-content-media-buttons');
            console.log(btns);
            var info = $('<div></div>');
            info.css('display', 'inline-block');
            info.css('background', '#fff');
            info.css('box-shadow', '2px 2px 3px #ccc');
            info.css('width', '180px');
            info.css('line-height', 'normal');
            info.css('font-size', '10px');
            info.html('Для добавления галереи: "Добавить медиафайл" -> "Создать галерею"');
            btns.after(info);
        })
    </script>

    <?php
}

// включаем обновление полей при сохранении
add_action('save_post', 'my_extra_album_fields_update', 0);

/* Сохраняем данные, при сохранении поста */
function my_extra_album_fields_update($post_id)
{
    // базовая проверка
    if (
        empty($_POST['extra'])
        || !wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__)
        || wp_is_post_autosave($post_id)
        || wp_is_post_revision($post_id)
    )
        return false;

    // Все ОК! Теперь, нужно сохранить/удалить данные
    $_POST['extra'] = array_map('trim', $_POST['extra']);
    foreach ($_POST['extra'] as $key => $value) {
        if (empty($value)) {
            delete_post_meta($post_id, $key); // удаляем поле если значение пустое
            continue;
        }

        update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
    }
    return $post_id;
}

include_once "custom_multiple.php"; // перенаправление, на создание дополнительного множественного поля
