<?php

/*

Template Name: Архив публикаций

 */

?>

<?php

get_header();

?>

    <!--All preachings-->
    <section>

        <!--        <script src="--><?php //echo get_stylesheet_directory_uri(); ?><!--/js/url.js"></script>-->

        <div class="wrapper">
            <div class="main-content__right all-preachings">
                <div class="schedule">

                    <?php
                    $archive_post_type = 'article';
                    $post_author = isset($_GET['post_author']) ? $_GET['post_author'] : '';
                    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
                    $action = isset($_GET['action']) ? $_GET['action'] : '';
                    $next_page = isset($_GET['next_page']) ? $_GET['next_page'] : 1;

                    $args = array(
                        'post_type' => $archive_post_type,
                        'publish' => true,
                        'meta_key' => '_date',
                        'meta_query' => array(
                            'relation' => 'OR', // это нужно чтобы фильры правильно приклеивались
                            array(
                                'key' => '_date',
                                'compare' => 'EXISTS'
                            ),
                        ),
                        'orderby' => '_date', // сортировать по дате
                        'order' => 'DESC', // по убыванию (сначала - свежие посты)
//                        'paged' => get_query_var('paged'),
                        'paged' => $next_page,
                        'posts_per_page' => 8
                    );

                    /* качественный вывод из php на сайт */
                    /*echo '<pre>'.print_r(array_keys($_GET), true).'</pre>';
                    foreach($_GET as $key => $value){
                        echo $key .' = '.$value.'<br>';
                    };
                    */

                    $post_query = query_posts($args);
                    // print_r($videos);

                    if ($_GET && !empty($_GET)) { // если было передано что-то из формы
                        go_article_filter($post_query); // запускаем функцию фильтрации
                    }
                    ?>

                    <h4>проповеди</h4>
                    <div class="preachings-filter">
                        <!--                        <form class="filter" action="-->
                        <?php //echo get_pagenum_link();?><!--" method="get">-->
                        <form class="filter" action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="post">
                            <!-- action ссылается на первую страницу пагинации, чтобы при применении фильтра все работало нормально -->
                            <div class="author-selector__wrap">
                                <p>Автор</p>

                                <select name="post_author" class="author-selector"> <!-- Раздел - селектлист -->
                                    <option value="Все">Все</option>
                                    <?php $authors_arr = get_meta_values('post_author', 'article');
                                    foreach ($authors_arr as $author) { ?>
                                        <option value="<?php echo $author; ?>">
                                            <?php
                                            echo str_replace("Протоиерей", "Прот.", $author);
                                            ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="search-input__wrap">
                                <input type="text" name="keyword" class="search-input"
                                       value="<?php echo get_search_query(); ?>">
                                <button type="submit" class="search-btn"></button>
                            </div>
                            <input type="hidden" name="action" value="myarticlefilter">
                        </form>
                    </div>
                    <div class="preach-list__wrap">
                        <?php
                        if (have_posts()) : while (have_posts()) : the_post();
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
                            'current' => $next_page,
                            'show_all' => false, // показаны НЕ все страницы участвующие в пагинации
                            'end_size' => 0,     // количество страниц на концах
                            'mid_size' => 1,     // количество страниц вокруг текущей
                        );
                        add_filter('navigation_markup_template', 'my_navigation_template', 10, 2);
                        function my_navigation_template($template, $class)
                        {
                            return '<div class="pagination">%3$s</div>';
                        }

                        the_posts_pagination($args);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--    <script src="--><?php //echo get_stylesheet_directory_uri(); ?><!--/js/ajax.js?2"></script>-->
<?php
get_footer();