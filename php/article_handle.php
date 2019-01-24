<?php

/*      create_article_post       */

add_action('init', 'create_article_post'); // Использовать функцию только внутри хука init

function create_article_post()
{
    $labels = array(
        'name' => 'Статьи',
        'singular_name' => 'Статью', // админ панель Добавить->Функцию
        'add_new' => 'Добавить статью',
        'add_new_item' => 'Добавить новую статью', // заголовок тега <title>
        'edit_item' => 'Редактировать статью',
        'new_item' => 'Новая статья',
        'all_items' => 'Все статьи',
        'view_item' => 'Просмотр статьи на сайте',
        'search_items' => 'Искать статью',
        'not_found' => 'Статью не найдено.',
        'not_found_in_trash' => 'В корзине нет статей.',
        'menu_name' => 'Статьи' // ссылка в меню в админке
    );
    $args = array(
        'labels' => $labels,
        'public' => true, // благодаря этому некоторые параметры можно пропустить
        'menu_icon' => 'dashicons-id-alt',
        'menu_position' => 6, // 4-9 — под «Записи»
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'taxonomies' => array('post_tag')
    );
    register_post_type('article', $args);
}

// подключаем функцию активации мета блока (my_extra_fields)
add_action('add_meta_boxes', 'my_article_extra_fields', 1);

function my_article_extra_fields()
{
    add_meta_box('extra_fields', 'Дополнительные поля', 'extra_article_fields_box_func', 'article', 'normal', 'high');
}

// код блока
function extra_article_fields_box_func($post)
{
    ?>
    <p style="display: none"><b>Заголовок статьи (оно же заголовок страницы) :</b>
        <textarea readonly type="text" id="post_name" name="extra[post_name]"
                  style="width:100%;height:50px;"><?php echo get_post_meta($post->ID, 'post_name', 1); ?></textarea>
    </p>
    <p><b>Автор:</b>
    <div>
        <select name="extra[post_author]" title="Новый автор вписывается в инпут, здесь можно выбрать уже публиковавшихся">
            <?php $authors_arr = get_meta_values('post_author', 'article');
            foreach ($authors_arr as $author) { ?>
                <option value="<?php echo $author; ?>">
                    <?php
                    echo str_replace("Протоиерей", "Прот.", $author);
                    ?></option>
            <?php } ?>
        </select>
        <textarea placeholder="Новый автор сюда, список имеющихся - в селекте" type="text" name="extra[post_author]"
                  style="width:100%;height:25px;"><?php echo get_post_meta($post->ID, 'post_author', 1); ?></textarea>
    </div>
    </p>
    <p><b>Дата публикации/выхода:</b>
        <input type="date" name="extra[_date]" value="<?php echo get_post_meta($post->ID, '_date', 1); ?>">
    </p>
    <p><b>Источник (если есть):</b>
        <input type="text" name="extra[source]" value="<?php echo get_post_meta($post->ID, 'source', 1); ?>">
    </p>
    <p><b>Ссылка на источник:</b>
        <input type="text" name="extra[source_link]" value="<?php echo get_post_meta($post->ID, 'source_link', 1); ?>">
    </p>
    <p><b>Подзаголовок (то же что событие в разделе проповеди):</b>
        <textarea type="text" name="extra[post_event]"
                  style="width:100%;height:50px;"><?php echo get_post_meta($post->ID, 'post_event', 1); ?></textarea>
    </p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>"/>
    <div class="btn-like" id="typogr">Подготовить текст для публикации</div>
    <i style="font-size: 10px; font-weight: normal;display: inline">Предварительно нужно переключить редактор на вкладку "Текст"</i>

    <script>
        jQuery(function ($) {
            var btns = $('#wp-content-media-buttons');
            console.log(btns);
            var info = $('<div></div>');
            info.css('display', 'inline-block');
            info.css('background', '#fff');
            info.css('box-shadow', '2px 2px 3px #ccc');
            info.css('width', '300px');
            info.css('line-height', 'normal');
            info.css('font-size', '10px');
            info.html('Для правильного вывода абзацов обязательно устанавливать выравнивание, как правило - по левому краю');
            btns.after(info);

            var attributes = ['post_author'];
            attributes.forEach(function (meta) {
                var videoSelect = document.querySelector('select[name="extra[' + meta + ']"]');
                var videoTextArea = document.querySelector('textarea[name="extra[' + meta + ']"]');
                videoSelect.addEventListener('input', function (e) {
                    videoTextArea.value = this.value.trim();
                });
            });
            var mainTitle = document.querySelector('input[name="post_title"]');
            var secondTitle = document.querySelector('#post_name');
            mainTitle.addEventListener('input', function () {
                var title = this.value;
                secondTitle.value = title;
                secondTitle.innerHTML = title;
            })

        })
    </script>
    <?php
}

// включаем обновление полей при сохранении
add_action('save_post', 'my_extra_article_fields_update', 0);

/* Сохраняем данные, при сохранении поста */
function my_extra_article_fields_update($post_id)
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

function go_article_filter($post_query)
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

function true_article_filter_function()
{
    $post_author = isset($_POST['post_author']) ? $_POST['post_author'] : '';
    $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
    $next_page = isset($_POST['next_page']) ? $_POST['next_page'] : 1;

    $args = array(
        'post_type' => 'article',
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


add_action('wp_ajax_myarticlefilter', 'true_article_filter_function');
add_action('wp_ajax_nopriv_myarticlefilter', 'true_article_filter_function');