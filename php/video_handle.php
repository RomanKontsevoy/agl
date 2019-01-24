<?php

/*      create_video_post       */

add_action('init', 'create_video_post'); // Использовать функцию только внутри хука init

function create_video_post()
{
    $labels = array(
        'name' => 'Видео',
        'singular_name' => 'Видео', // админ панель Добавить->Функцию
        'add_new' => 'Добавить видео',
        'add_new_item' => 'Добавить новое видео', // заголовок тега <title>
        'edit_item' => 'Редактировать видео',
        'new_item' => 'Новое видео',
        'all_items' => 'Все видео',
        'view_item' => 'Просмотр видео на сайте',
        'search_items' => 'Искать видео',
        'not_found' => 'Видео не найдено.',
        'not_found_in_trash' => 'В корзине нет видео.',
        'menu_name' => 'Видео' // ссылка в меню в админке
    );
    $args = array(
        'labels' => $labels,
        'public' => true, // благодаря этому некоторые параметры можно пропустить
        'menu_icon' => 'dashicons-format-video', // иконка корзины
        'menu_position' => 5, // 4-9 — под «Записи»
        'has_archive' => true,
//        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'supports' => array('title', 'thumbnail'),
        'taxonomies' => array('post_tag')
    );
    register_post_type('video', $args);
}

// подключаем функцию активации мета блока (my_extra_fields)
add_action('add_meta_boxes', 'my_extra_fields', 1);

function my_extra_fields()
{
    add_meta_box('extra_fields', 'Дополнительные поля', 'extra_fields_box_func', 'video', 'normal', 'high');
}

// код блока
function extra_fields_box_func($post)
{
    ?>
    <p style="display: none"><b>Название видеозаписи (оно же заголовок страницы) :</b>
        <textarea type="text" name="extra[post_name]"
                  style="width:100%;height:50px;"><?php echo get_post_meta($post->ID, 'post_name', 1); ?></textarea>
    </p>
    <p><b>Автор:</b>
    <div>
        <select name="extra[post_author]">
            <?php $authors_arr = get_meta_values('post_author', 'video');
            foreach ($authors_arr as $author) { ?>
                <option value="<?php echo $author; ?>">
                    <?php
                    echo str_replace("Протоиерей", "Прот.", $author);
                    ?></option>
            <?php } ?>
        </select>
        <textarea type="text" name="extra[post_author]"
                  style="width:100%;height:25px;"><?php echo get_post_meta($post->ID, 'post_author', 1); ?></textarea>
    </div>

    </p>
    <p><b>Дата произнесения/выхода:</b>
        <input type="date" name="extra[_date]" value="<?php echo get_post_meta($post->ID, '_date', 1); ?>">
    </p>
    <p><b>Событие, день церковного календаря и т. д.:</b>
        <textarea type="text" name="extra[post_event]"
                  style="width:100%;height:50px;"><?php echo get_post_meta($post->ID, 'post_event', 1); ?></textarea>
    </p>
    <p><b>Код для вставки видео</b> (iframe с сайта YouTube):
        <textarea type="text" name="extra[video_iframe]"
                  style="width:100%;height:50px;"><?php echo get_post_meta($post->ID, 'video_iframe', 1); ?></textarea>
    </p>
    <p><b>Ссылка на отрывок из Писания, которого касается проповедь:</b>
        <textarea type="text" name="extra[excerpt_link]"
                  style="width:100%;height:25px;"><?php echo get_post_meta($post->ID, 'excerpt_link', 1); ?></textarea>
    </p>
    <p><b>Тип видеозаписи (проповедь, лекция, беседа и т. д.):</b>
    <div>
        <select name="extra[video_type]">
            <?php $types_arr = get_meta_values('video_type', 'video');
            foreach ($types_arr as $type) { ?>
                <option value="<?php echo $type; ?>"
                ><?php echo $type; ?></option>
            <?php } ?>
        </select>
        <textarea type="text" name="extra[video_type]"
                  style="width:100%;height:25px;"><?php echo get_post_meta($post->ID, 'video_type', 1) ?></textarea>
    <script>
        var attributes = ['video_type', 'post_author'];
        attributes.forEach(function (meta) {
            var videoSelect = document.querySelector('select[name="extra[' + meta + ']"]');
            var videoTextArea = document.querySelector('textarea[name="extra[' + meta + ']"]');
            videoSelect.addEventListener('input', function (e) { // запускает срабатывание события submit формы после выбора в селекте нового автора
                videoTextArea.value = this.value.trim();
            });
        });
        var mainTitle = document.querySelector('input[name="post_title"]');
        var secondTitle = document.querySelector('textarea[name="extra[post_name]"]');
        mainTitle.addEventListener('input', function () {
            var title = $(this).val();
            console.log(title);
            secondTitle.val(title);
            secondTitle.html(title);
            console.log(secondTitle);
        })
    </script>
    </div>

    </p>
    <p><b>Отрывок из Писания, которого касается проповедь:</b>
        <textarea type="text" name="extra[excerpt_text]"
                  style="width:100%;height:100px;"><?php echo get_post_meta($post->ID, 'excerpt_text', 1); ?></textarea>
    </p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>"/>
    <div class="btn-like" id="typogr">Подготовить текст для публикации</div>
    <?php
}

// включаем обновление полей при сохранении
add_action('save_post', 'my_extra_fields_update', 0);

/* Сохраняем данные, при сохранении поста */
function my_extra_fields_update($post_id)
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

/* Фильтрация */

function go_filter($post_query)
{ // наша функция
    $args = array(); // подготовим массив
    global $wp_query; // нужно заглобалить текущую выборку постов

    if ($_GET['keyword'] != '') { // если передано поле "Ключевое слово"
//        $args['s'] = $_GET['keyword']; // пишем значение в ключ "s" условий выборки, обратите внимание это уже не произвольное поле для meta_query, будет работать как обычный поиск + остальные условия
        $args['meta_query'][] = array(
            'relation' => 'OR', // нужно, чтобы срабатывал хотя бы один вариант поискового запроса
            array(
                'key' => 'post_author', // вариант названия пользовательского поля
                'value' => $_GET['keyword'], // присвоить свойству значение, введенное в форму на сайте
                'compare' => 'LIKE', // тип сравнение "содержится в свойстве"
            ),
            array(
                'key' => 'post_event', // вариант названия пользовательского поля
                'value' => $_GET['keyword'], // присвоить свойству значение, введенное в форму на сайте
                'compare' => 'LIKE', // тип сравнение "содержится в свойстве"
            ),
            array(
                'key' => 'post_name', // вариант названия пользовательского поля
                'value' => $_GET['keyword'], // присвоить свойству значение, введенное в форму на сайте
                'compare' => 'LIKE', // тип сравнение "содержится в свойстве"
            ),
        );

    }

    if ($_GET['post_author'] != '') { // если передана фильтрация по автору
        $v_a = $_GET['post_author'];
        if ($v_a === 'Все') $v_a = '';
        $args['meta_query'][] = array( // пишем условия в meta_query
            'key' => 'post_author', // название произвольного поля
            'value' => $v_a, // переданное значение произвольного поля
//            'type' => 'varchar' // тип поля, нужно указывать чтобы быстрее работало, у нас здесь число
//            'type' => 'numeric', // тип поля - число
            'compare' => 'LIKE' // тип сравнения IN, т.е. значения поля комнат должно быть одним из значений элементов массива
        );
    }


    $args['orderby'] = '_date'; // сортировать по дате

    $post_query = query_posts(array_merge($wp_query->query, $args)); // сшиваем текущие условия выборки стандартного цикла wp с новым массивом переданным из формы и фильтруем
}

// Хороший расширенный вариант этого же фильтра: https://opttour.ru/web/wordpress/sortirovka-postov-po-date-po-zagolovku-po-date-izmeneniy/

/*------------AJAX--------------*/

function true_filter_function()
{
    $post_author = isset($_POST['post_author']) ? $_POST['post_author'] : '';
    $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
    $next_page = isset($_POST['next_page']) ? $_POST['next_page'] : 1;

    $args = array(
        'post_type' => 'video',
        'publish' => true,
        'meta_key' => '_date',
        'meta_query' => array(
            'relation' => 'AND', // это нужно чтобы фильры правильно приклеивались
            array(
                'key' => '_date',
                'compare' => 'EXISTS'
            ),
        ),
        'orderby' => '_date', // сортировка по дате у нас будет в любом случае (но вы можете изменить/доработать это)
        'order' => 'DESC', // ASC или DESC
        'paged' => $next_page,
        'posts_per_page' => 8
    );

    if (isset($_POST['post_author'])) { // если передана фильтрация по автору
        $v_a = $_POST['post_author'];
        if ($v_a === 'Все') $v_a = '';
        $args['meta_query'][] = array( // пишем условия в meta_query
            'key' => 'post_author', // название произвольного поля
            'value' => $v_a, // переданное значение произвольного поля
//            'type' => 'varchar' // тип поля, нужно указывать чтобы быстрее работало, у нас здесь число
//            'type' => 'numeric', // тип поля - число
            'compare' => 'LIKE' // тип сравнения IN, т.е. значения поля комнат должно быть одним из значений элементов массива
        );
    }

    if ($_POST['keyword'] != '') { // если передано поле "Ключевое слово"
//        $args['s'] = $_POST['keyword']; // пишем значение в ключ "s" условий выборки, обратите внимание это уже не произвольное поле для meta_query, будет работать как обычный поиск + остальные условия
        $args['meta_query'][] = array(
            'relation' => 'OR', // нужно, чтобы срабатывал хотя бы один вариант поискового запроса
            array(
                'key' => 'post_author', // вариант названия пользовательского поля
                'value' => $_POST['keyword'], // присвоить свойству значение, введенное в форму на сайте
                'compare' => 'LIKE', // тип сравнения "содержится в свойстве"
            ),
            array(
                'key' => 'post_event', // вариант названия пользовательского поля
                'value' => $_POST['keyword'], // присвоить свойству значение, введенное в форму на сайте
                'compare' => 'LIKE', // тип сравнения "содержится в свойстве"
            ),
            array(
                'key' => 'post_name', // вариант названия пользовательского поля
                'value' => $_POST['keyword'], // присвоить свойству значение, введенное в форму на сайте
                'compare' => 'LIKE', // тип сравнения "содержится в свойстве"
            )
        );
    }

    if (isset($_POST['np'])) {
        $args['posts_per_page'] = 8;
    }

    query_posts($args);
    if (have_posts()) : while (have_posts()) : the_post();
        global $post;
        ?>
        <div class="preach-list__item news-side">
            <div class="time">
                <?php
                /* Вывод даты в произвольном формате */
                $date_output = get_post_meta($post->ID, '_date', 1);
                $date_output = date("d.m.y", strtotime($date_output));
                echo $date_output;
                ?>

            </div>
            <div class="preachings-list">
                <a href="<?php the_permalink() ?>"
                   class="event update-title"><?php
                    if (get_post_meta($post->ID, 'post_event', 1)) {
                        echo get_post_meta($post->ID, 'post_event', 1) . ". ";
                        echo get_post_meta($post->ID, 'post_name', 1);
                    } else {
                        echo get_post_meta($post->ID, 'post_name', 1);
                    }
                    ?></a>
                <p class="author">
                    <?php
                    echo get_post_meta($post->ID, 'post_author', 1);
                    ?>
                </p>
            </div>
        </div>
    <?php
    endwhile;
    else :
        echo "<p style='text-align: center;margin-top: 20px;'>К сожалению, ничего не найдено...</p>";
    endif;

    $args = array(
        'prev_next' => true,  // выводить ли боковые ссылки "предыдущая/следующая страница".
        'prev_text' => __(''),
        'next_text' => __(''),
        'add_args' => false, // Массив аргументов (переменных запроса), которые нужно добавить к ссылкам.
        'add_fragment' => '',     // Текст который добавиться ко всем ссылкам.
        'screen_reader_text' => __(''),
        'before_page_number' => '',
        'after_page_number' => '',
        'show_all' => false, // показаны НЕ все страницы участвующие в пагинации
        'end_size' => 0,     // количество страниц на концах
        'mid_size' => 1,     // количество страниц вокруг текущей
    );

    if (isset($_POST['next_page'])) { // если передан номер следующей страницы из пагинации
        $args['current'] = $_POST['next_page'];
    }

    add_filter('navigation_markup_template', 'my_navigation_template', 10, 2);
    function my_navigation_template($template, $class)
    {
        return '<div class="pagination">%3$s</div>';
    }

    $pagination = isset($_POST['np']) ? false : get_the_posts_pagination($args);

    echo str_replace(admin_url('admin-ajax.php'), get_pagenum_link(), $pagination);
    die();
}


add_action('wp_ajax_myfilter', 'true_filter_function');
add_action('wp_ajax_nopriv_myfilter', 'true_filter_function');