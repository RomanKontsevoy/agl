<?php

/*

Template Name: Главная страница

 */

?>

<?php

get_header();

?>

    <!--Main page bacground-->
    <section class="head">
        <div class="main-bg"></div>
        <div class="pattern"></div>
    </section>

    <!--Main page content-->
    <section class="main">
        <div class="wrapper">
            <div class="homily">
                <?php
                $args = array(
                    'post_type' => 'video',
                    'publish' => true,
                    'paged' => false,
                    'posts_per_page' => 1,
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
                );
                $video = query_posts($args);
                if (have_posts()) :
                while (have_posts()) :
                the_post();
                ?>
                <div class="video">
                    <?php echo get_post_meta($post->ID, 'video_iframe', true) ?>
                </div>
                <div class="video-info">
                    <div class="homily-wrap">
                        <h4>
                            <?php echo get_post_meta($post->ID, 'video_type', 1) ?>
                        </h4>
                        <div class="homily-event">
                            <?php echo(get_post_meta($post->ID, 'post_event', true)) ?>
                        </div>
                        <div class="bible-part">
                            <?php echo(get_post_meta($post->ID, 'excerpt_link', true)) ?>
                        </div>
                        <div class="homily-title">
                            <?php echo get_post_meta($post->ID, 'post_name', true); ?>
                        </div>
                        <div class="author">
                            <?php echo get_post_meta($post->ID, 'post_author', 1); ?>
                        </div>
                    </div>
                    <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                    <a href="<?php echo get_home_url() . '/video' ?>" class="all-homilies">Все проповеди</a>
                </div>

            </div>
            <div class="main-content__wrap">
                <div class="main-content__left">
                    <div class="latest-updates">
                        <h4>Последние обновления
                        </h4>
                        <div class="latest-updates__wrap">

                            <?php
                            $args = array(
                                'post_type' => array(
                                    'video', 'article', 'album'
                                ),
                                'publish' => true,
                                'paged' => false,
                                'posts_per_page' => 2,
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
                            );
                            $video = query_posts($args);
                            if (have_posts()) :
                                while (have_posts()) :
                                    the_post();
                                    ?>

                                    <div class="latest-updates__item">
                                        <p class="date">
                                            <?php
                                            /* Вывод даты в произвольном формате */
                                            $date_output = get_post_meta($post->ID, '_date', 1);
                                            $date_output = date("d.m.y", strtotime($date_output));
                                            echo $date_output;
                                            ?>
                                        </p>
                                        <a href="<?php the_permalink() ?>" class="update-title"><?php
                                            $pt = $post->post_type;
                                            if ($pt === 'video' || $pt === 'article') {
                                                echo get_post_meta($post->ID, 'post_author', 1) . '. ' . get_post_meta($post->ID, 'post_name', 1);
                                            } else if ($pt === 'album') {
                                                echo 'Фотоальбом &laquo;' . esc_html(get_the_title()) . '&raquo;';
                                            }
                                            ?></a>
                                    </div>
                                    <!--                                    <div class="latest-updates__item">-->
                                    <!--                                        <p class="date">09.03.18</p>-->
                                    <!--                                        <a href="" class="update-title">Фотоальбом «Освящение приходского дома»</a>-->
                                    <!--                                    </div>-->

                                <?php
                                endwhile;
                            endif;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <div class="recent-news">
                        <h4>Новости</h4>
                        <?php
                        $args = array(
                            'post_type' => 'news',
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
                            'posts_per_page' => 4
                        );

                        $post_query = query_posts($args);
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
                        wp_reset_postdata();
                        ?>

                        <a href="<?php echo get_home_url() . '/news' ?>" class="btn-all-news">Все новости</a>
                    </div>
                </div>
                <div class="main-content__right">
                    <div class="schedule">
                        <h4>Расписание</h4>
                        <div>
                            <?php
                            $args = array(
                                'post_type' => 'shedule',
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
                                'posts_per_page' => 1
                            );

                            $post_query = query_posts($args);
                            if (have_posts()) : while (have_posts()) : the_post();
                                $week = get_post_meta($post->ID, "days", true);
                                setlocale(LC_ALL, 'ru_RU.UTF-8');
                                foreach ($week as $day) { ?>
                                    <div class="week-day__item">
                                        <div class="week-day__title">
                                            <div class="week-day__name"><?php echo $day['day'] ?></div>
                                            <div class="week-day__date"><?php
                                                echo strftime("%d.%m.%Y", strtotime($day["date"]))
                                                ?></div>
                                        </div>
                                        <?php foreach ($day["events"] as $event) { ?>
                                            <div class="right-side__item">
                                                <div class="time"><?php echo $event['time'] ?></div>
                                                <div class="event"><?php echo $event['text'] ?></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                            endwhile;
                            endif;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <div class="additional-info__wrap">
                        <div class="additional-info__item">
                            <div class="addition-img__wrap">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/addition-info.jpg" alt="">
                            </div>
                            <p>Каждое последнее воскресенье месяца <span class="nw">в храме</span> <span class="nw">прп. Агапита</span>
                                Печерского совершается детская Литургия — укороченное богослужение, на котором дети
                                учатся молиться и принимают полноценное участие <span class="nw">в службе.</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

get_footer();