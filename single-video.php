<?php
/*
Template Name: Одна проповедь
Template Post Type: video
 */
?>

<?php
get_header();
global $post;
$page_id = $post->ID;
?>
    <!--One preaching-->
    <section>
        <div class="wrapper">
            <div class="main-content__wrap video-content">
                <div class="main-content__left article-content">
                    <?php
                    $args = array(
                        'post_type' => 'video',
                        'publish' => true,
                        'paged' => get_query_var('paged'),
                        'posts_per_page' => 1,
                        'post' => $page_id
                    );
                    ?><br><?php
                    $q = query_posts($args);
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            ?>
                            <div class="time">
                                <?php
                                /* Вывод даты в произвольном формате */
                                $date_output = get_post_meta($page_id, '_date', 1);
                                $date_output = date("d.m.y", strtotime($date_output));
                                echo $date_output;
                                ?>
                                <br>
                            </div>
                            <h3 class="article-title">
                                <?php
                                echo get_post_meta($page_id, 'post_name', true);
                                ?>

                            </h3>
                            <p class="author">
                                <?php
                                echo get_post_meta($page_id, 'post_author', 1);
                                ?>
                            </p>
                            <div class="preaching-video">
                                <?php echo get_post_meta($page_id, 'video_iframe', true) ?>
                            </div>
                            <h4 class="article-subtitle">
                                <?php echo(get_post_meta($page_id, 'post_event', true)) ?>

                            </h4>
                            <div class="bible-part">
                                <?php echo(get_post_meta($page_id, 'excerpt_link', true)) ?>
                            </div>
                            <p class="excerpt">
                                <?php echo(get_post_meta($page_id, 'excerpt_text', true)) ?>
                            </p>
                        <?php
                            edit_post_link(__('Редактировать'));
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>

                </div>
                <div class="main-content__right">

                    <?php

                    $post_author = isset($_GET['post_author']) ? $_GET['post_author'] : '';
                    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
                    $action = isset($_GET['action']) ? $_GET['action'] : '';

                    $args = array(
                        'post_type' => 'video',
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
                        'posts_per_page' => 1
                    );
                    $videos = query_posts($args);
                    ?>

                    <?php if ($_GET && !empty($_GET)) { // если было передано что-то из формы
                        go_filter($videos); // запускаем функцию фильтрации
                    } ?>

                    <div class="schedule">

                        <h4>Другие проповеди</h4>
                        <div class="preachings-filter">
                            <form class="filter" action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="post">
                                <!-- action ссылается на первую страницу пагинации, чтобы при применении фильтра все работало нормально -->
                                <p>Проповедник</p>
                                <select name="post_author" class="author-selector">
                                    <option value="Все">Все</option>
                                    <?php $authors_arr = get_meta_values('post_author', 'video');
                                    foreach ($authors_arr as $author) { ?>
                                        <option value="<?php echo $author; ?>">
                                            <?php
                                            echo str_replace("Протоиерей", "Прот.", $author);
                                            ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="action" value="myfilter">
                                <input type="hidden" name="np" value="np">
                            </form>
                            <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/url.js"></script>
                        </div>
                        <div class="preach-list__wrap">

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
                                        $text = get_post_meta($post->ID, 'post_event', 1) . ". " . get_post_meta($post->ID, 'video_name', 1);
                                    } else {
                                        $text = get_post_meta($post->ID, 'post_name', 1);
                                    }
                                    echo $text;
                                    ?></a>
                                <p class="author"><?php
                                    echo get_post_meta($post->ID, 'post_author', 1);
                                    ?>
                                </p>
                            </div>
                        </div>
                        <?php
                            endwhile;
                            endif;
                        ?>
                        </div>
                        <a href="<?php echo get_home_url() . '/video'?>" class="btn-all-news">Все проповеди</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

get_footer();



