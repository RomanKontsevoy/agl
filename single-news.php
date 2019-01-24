<?php

/*

Template Name: Одна новость
Template Post Type: news

 */

?>

<?php

get_header();
global $post;
$page_post = $post;
//print_r($post);
$page_id = $post->ID;
//echo $page_id ? $page_id : 'no ID';
?>

    <section>
        <div class="wrapper">
            <div class="main-content__wrap">
                <div class="main-content__left article-content">
                    <?php
                    $args = array(
                        'post_type' => 'news',
                        'publish' => true,
                        'paged' => get_query_var('paged'),
                        'posts_per_page' => 1,
                        'post' => $page_id
                    );
                    $q = query_posts($args);
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            ?>
                            <div class="time-author__wrap">

                                <div class="time">
                                    <?php
                                    /* Вывод даты в произвольном формате */
                                    $date_output = get_post_meta($page_id, '_date', 1);
                                    $date_output = date("d.m.y", strtotime($date_output));
                                    echo $date_output;
                                    ?>
                                </div>


                                <?php if (get_post_meta($page_id, 'post_author', 1)) { ?>
                                    <p class="author">
                                        <?php
                                        echo get_post_meta($page_id, 'post_author', 1);
                                        ?>
                                    </p>
                                <?php } ?>
                            </div>
                            <h3 class="article-title">
                                <?php echo get_the_title($page_id); ?></h3>
                            <div class="article-content">
                                <?php
                                $content = $page_post->post_content;
                                $content = apply_filters('the_content', $content);
                                $content = str_replace(']]>', ']]&gt;', $content);
                                echo $content;
                                ?>
                            </div>
                            <?php
                            edit_post_link(__('Редактировать'));
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>

                </div>
                <!--            <div class="main-content__left article-content">-->
                <!--                <div class="time">17.02.18</div>-->
                <!--                <h3 class="article-title">Блаженнейший Митрополит Онуфрий благословил паству <span class="nw">на особый</span> молитвенный подвиг ради мира в Украине</h3>-->
                <!--                <div class="article-img">-->
                <!--                    <img src="-->
                <?php //echo get_stylesheet_directory_uri(); ?><!--/img/article-img.jpg" alt="">-->
                <!--                </div>-->
                <!--                <p>Архипастырь четвертый год подряд благословляет паству на великопостный молитвенный подвиг ради наступления мира в стране.</p>-->
                <!--                <p>Блаженнейший Митрополит Онуфрий благословил всем верующим Украинской Православной Церкви в течение всего периода Великого поста в дополнение к своему молитвенному правилу читать из Псалтири по одной кафизме в день. Об этом сообщает Информационно-просветительский отдел УПЦ.</p>-->
                <!--                <h4 class="article-subtitle">Подзаголовок</h4>-->
                <!--                <p>«Верным нашей Святой Украинской Православной Церкви в течение всего Великого поста благословляется читать из Псалтири по одной кафизме в день о мире в нашем Украинском государстве и благословении нашего народа», — призвал Блаженнейший владыка в своем Великопостным послании.</p>-->
                <!--                <p>«Если мы все духовно потрудимся в дни Святого Великого поста, Господь благословит каждого из нас и весь наш боголюбивый Украинский народ», — отметил Предстоятель.</p>-->
                <!--            </div>-->
                <div class="main-content__right">
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
                        'paged' => false,
                        'posts_per_page' => 8,
                        'post__not_in' => array( $page_id )
                    );
                    $news = query_posts($args);
                    ?>

                    <div class="schedule">
                        <h4>Другие новости</h4>
                        <?php
                        if (have_posts()) : while (have_posts()) : the_post();
                            ?>
                            <div class="preach-list__item news-side">
                                <div class="time"><?php
                                    /* Вывод даты в произвольном формате */
                                    $date_output = get_post_meta($post->ID, '_date', 1);
                                    $date_output = date("d.m.y", strtotime($date_output));
                                    echo $date_output;
                                    ?>
                                </div>
                                <div class="preachings-list">
                                    <a
                                            href="<?php the_permalink() ?>"
                                            class="event update-title"><?php
                                        $text;
                                        if (get_post_meta($post->ID, 'post_event', 1)) {
                                            $text = get_post_meta($post->ID, 'post_event', 1) . ". " . get_post_meta($post->ID, 'post_name', 1);
                                        } else {
                                            $text = get_post_meta($post->ID, 'post_name', 1);
                                        }
                                        echo $text;
                                        ?></a>
                                </div>

                            </div>

                        <?php
                        endwhile;
                        endif;
                        ?>
                        <a href="<?php echo get_home_url() . '/news' ?>" class="btn-all-news">Все новости</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

get_footer();
