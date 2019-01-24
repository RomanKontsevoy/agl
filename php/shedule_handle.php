<?php

/* create_shedule_post */

add_action( 'init', 'true_shed_register_products' ); // Использовать функцию только внутри хука init

function true_shed_register_products() {
    $labels = array(
        'name' => 'Расписания',
        'singular_name' => 'Расписание', // админ панель Добавить->Функцию
        'add_new' => 'Добавить расписание',
        'add_new_item' => 'Добавить расписание', // заголовок тега <title>
        'edit_item' => 'Редактировать расписание',
        'new_item' => 'Новое расписание',
        'all_items' => 'Все расписания',
        'view_item' => 'Просмотр расписания на сайте',
        'search_items' => 'Искать расписание',
        'not_found' =>  'Расписаний не найдено.',
        'not_found_in_trash' => 'В корзине нет расписаний.',
        'menu_name' => 'Расписания' // ссылка в меню в админке
    );
    $args = array(
        'labels' => $labels,
        'public' => true, // благодаря этому некоторые параметры можно пропустить
        'menu_icon' => 'dashicons-list-view', // иконка корзины
        'menu_position' => 7, // 4-9 — под «Записи»
        'has_archive' => true,
        'supports' => array( 'title', 'thumbnail'),
        'taxonomies' => array('post_tag')
    );
    register_post_type('shedule',$args);
}

// подключаем функцию активации мета блока (my_extra_fields)
add_action('add_meta_boxes', 'my_shedule_extra_fields', 1);

function my_shedule_extra_fields()
{
    add_meta_box('extra_fields', 'Общая информация', 'extra_shedule_fields_box_func', 'shedule', 'normal', 'high');
}

// код блока
function extra_shedule_fields_box_func($post)
{
    ?>
    <p><b>Дата воскресения:</b>
        <input type="date" name="extra[_date]" required value="<?php echo get_post_meta($post->ID, '_date', 1); ?>">

    </p>

    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>"/>
    <?php
}

// включаем обновление полей при сохранении
add_action('save_post', 'my_extra_shedule_fields_update', 0);

/* Сохраняем данные, при сохранении поста */
function my_extra_shedule_fields_update($post_id)
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

include_once "custom_shedule_multiple.php"; // перенаправление, на создание дополнительного множественного поля
