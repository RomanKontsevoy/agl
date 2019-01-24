<?php

/*

Template Name: Один альбом
Template Post Type: album


 */

?>

<?php

get_header();
global $post;
$page_id = $post->ID;
?>

    <!--One album-->
    <section>
        <div class="wrapper">
            <div class="album-page">
                <?php

                $args = array(
                    'post_type' => 'album',
                    'publish' => true,
                    'paged' => get_query_var('paged'),
                    'posts_per_page' => 1,
                    'p' => $page_id,
                );

                $q = query_posts($args);

                //                print_r($q);

                if (have_posts()) :
                while (have_posts()) :
                the_post();


                ?>
                <div class="time">
                    <?php
                    /* Вывод даты в произвольном формате */
                    $date_output = get_post_meta($page_id, '_date', 1);
                    $date_output = date("d.m.y", strtotime($date_output));
                    echo $date_output;
                    ?>
                </div>
                <h3 class="article-title"><?php echo the_title(); ?></h3>
                <div class="album-wrap">
                    <?php

                    $print_address = get_post_meta($page_id, 'album_youtube', true);
                    if ($print_address) {
                        ?>
                        <div class="album-video__wrap">
                            <?php

                            for ($i = 0; $i < count($print_address); $i++) {
                                $thisAddress = $print_address[$i];
                                ?>
                                <iframe class="album-video"
                                        src="https://www.youtube.com/embed/<?php echo $thisAddress; ?>"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    $gal = $post->post_content; // выводит шорткод галереи
                    add_filter('use_default_gallery_style', '__return_false');
                    /*
                    * Изменение вывода галереи через шоткод
                    * Смотреть функцию gallery_shortcode в http://wp-kama.ru/filecode/wp-includes/media.php
                    * $output = apply_filters( 'post_gallery', '', $attr );
                    */
                    //                    $output = apply_filters('post_gallery', '', $attr);

                    add_filter('post_gallery', 'my_gallery_output', 10, 2);
                    function my_gallery_output($output, $attr)
                    {

                        $ids_arr = explode(',', $attr['ids']);
                        $ids_arr = array_map('trim', $ids_arr);


                        $pictures = get_posts(array(
                            'posts_per_page' => -1,
                            'post__in' => $ids_arr,
                            'post_type' => 'attachment',
                            'orderby' => 'post__in',
                        ));

                        if (!$pictures) return 'Запрос вернул пустой результат.';

                        // Вывод
                        $out = '';

                        // Выводим каждую картинку из галереи
                        foreach ($pictures as $pic) {
                            $src = $pic->guid;
                            $t = esc_attr($pic->post_title);
                            $title = ($t && false === strpos($src, $t)) ? $t : '';

                            $caption = ($pic->post_excerpt != '' ? $pic->post_excerpt : $title);
                            /* -- data-fancybox выводится неправильно, вручную добавляется через js -- */
                            $out .= '<a class="album-item" data-fancybox=&#34;gallery&#34; href="' . esc_url($src) . '">
			                            <img src="' . $src . '" alt="' . $title . '" />
			                        </a>' .
                                ($caption ? "<span class='caption'>$caption</span>" : '');

                        }

                        return $out;
                    }



                    $gallery_shortcode = $gal;

                    print apply_filters('the_content', $gallery_shortcode);
                    endwhile;
                    endif;
                    wp_reset_postdata(); // нужно использовать каждый раз после запуска произвольного цикла. Т.е. в случаях, когда на странице есть дополнительный цикл WordPress с использованием глобальной переменной $post
                    ?>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </section>

<?php

get_footer();
