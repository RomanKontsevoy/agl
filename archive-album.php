<?php

/*

Template Name: Галерея

 */

?>

<?php

get_header();

?>

    <!--Gallery-->
    <section>
        <div class="wrapper">
            <div class="gallery-page">
                <h3 class="gallery-title">Галерея</h3>
                <div class="gallery-wrap">
                    <?php
                    $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $args = array(
                        'post_type' => 'album',
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
                        'paged' => $current_page,
                        'posts_per_page' => 10
                    );

                    /* качественный вывод из php на сайт */
                    /*echo '<pre>'.print_r(array_keys($_GET), true).'</pre>';
                    foreach($_GET as $key => $value){
                        echo $key .' = '.$value.'<br>';
                    };
                    */


                    $albums = query_posts($args);
                    if (have_posts()) : while (have_posts()) : the_post();
                        $image = get_post_gallery_images( $post )[0];
                        ?>
                        <a href="<?php the_permalink() ?>" class="gallery-item">
                            <div class="gallery-img">
                                <img src="<?php echo $image; ?>"
                                     alt="">
                            </div>
                            <div class="gallery-item__info">
                                <div class="time">
                                    <?php
                                    /* Вывод даты в произвольном формате */
                                    $date_output = get_post_meta($post->ID, '_date', 1);
                                    $date_output = date("d.m.y", strtotime($date_output));
                                    echo $date_output;
                                    ?>
                                </div>
                                <div class="gallery-info__text"><?php echo the_title(); ?></div>
                            </div>
                        </a>

                    <?php
                    endwhile;
                    endif;
                    ?>



                </div>
                <?php

                $args = array(
                    'show_all'     => false, // показаны все страницы участвующие в пагинации
                    'end_size'     => 1,     // количество страниц на концах
                    'mid_size'     => 1,     // количество страниц вокруг текущей
                    'prev_next'    => true,  // выводить ли боковые ссылки "предыдущая/следующая страница".
                    'prev_text'    => __(''),
                    'next_text'    => __(''),
                    'add_args'     => false, // Массив аргументов (переменных запроса), которые нужно добавить к ссылкам.
                    'add_fragment' => '',     // Текст который добавиться ко всем ссылкам.
                    'screen_reader_text' => __( 'Posts navigation' ),
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
    </section>

<?php

get_footer();



