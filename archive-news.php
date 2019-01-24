<?php

/*

Template Name: Архив новостей

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
                <div class="schedule recent-news">

                    <?php
                    $archive_post_type = 'news';
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


                    ?>

                    <h4>новости</h4>
                    <div class="preach-list__wrap">
                        <?php
                        if (have_posts()) : while (have_posts()) : the_post();
                            ?>
                            <div class="new-wrap">
                                <div class="new-image">
                                    <img src="<?php
                                    $media = get_attached_media('image', $post->ID);
                                    $media = array_shift($media);
                                    $image_url = $media->guid;
                                    echo $image_url;
                                    ?>" alt="">
                                </div>
                                <div class="new-details">
                                    <div class="date">
                                        <?php
                                        /* Вывод даты в произвольном формате */
                                        $date_output = get_post_meta($post->ID, '_date', 1);
                                        $date_output = date("d.m.y", strtotime($date_output));
                                        echo $date_output;
                                        ?>
                                    </div>
                                    <a href="<?php the_permalink($post->ID) ?>" class="new-title"><?php
                                        if (get_post_meta($post->ID, 'post_event', 1)) {
                                            echo get_post_meta($post->ID, 'post_event', 1) . ". ";
                                            echo get_post_meta($post->ID, 'post_name', 1);
                                        } else {
                                            echo get_post_meta($post->ID, 'post_name', 1);
                                        }
                                        ?></a>
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