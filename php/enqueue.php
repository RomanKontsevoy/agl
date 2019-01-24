<?php
/* Подключение мета-тегов */

add_action('wp_head', 'head_seo_meta_tags');
function head_seo_meta_tags()
{

    // robots
    echo '<meta name="robots" content="index,nofollow" />';

    // для мобильников.
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';

}

/* Подключение мета-тегов */

/* Подключение стилей и скриптов */

function artbt_style()
{
    wp_enqueue_style('whitesquare-style', get_stylesheet_uri());
    wp_enqueue_style('reset', get_stylesheet_directory_uri() . '/css/reset.css');
    wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i|Open+Sans:300,300i,400,400i,700,700i,800,800i&amp;subset=cyrillic-ext');
    wp_enqueue_style('selectize', get_stylesheet_directory_uri() . '/css/selectize.css?4');
    wp_enqueue_style('style', get_stylesheet_directory_uri() . '/css/style.css?' . random());
    wp_enqueue_style('responsive', get_stylesheet_directory_uri() . '/css/responsive.css?'.random() );
}

add_action('wp_enqueue_scripts', 'artbt_style');

function header_scripts()
{
    wp_enqueue_script('jquery-my', get_template_directory_uri() . '/js/jquery.min.js', array(), null, false);
    wp_enqueue_script('url', get_template_directory_uri() . '/js/url.js', array(), null, false);
}

add_action('wp_enqueue_scripts', 'header_scripts');

/* adds defer to all js scripts */ /*
add_filter( 'clean_url', function( $url )
{
    if ( FALSE === strpos( $url, '.js' ) )
    { // not our file
        return $url;
    }
    // Must be a ', not "!
    return "$url' defer='defer";
}, 11, 1 );
*/

function footer_scripts()
{
    wp_enqueue_script('device', get_template_directory_uri() . '/js/device.js', array(), null, true);
    wp_enqueue_script('menu', get_template_directory_uri() . '/js/menu.js?2', array(), null, true);
    wp_enqueue_script('selectize', get_template_directory_uri() . '/js/selectize.min.js?2', array(), null, true);
    wp_enqueue_script('ajax', get_template_directory_uri() . '/js/ajax.js?' . random(), array(), null, true);
    wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js?' . random(), array(), null, true);
}

add_action('wp_enqueue_scripts', 'footer_scripts');

function admin_styles_function(){
    wp_enqueue_style('', get_stylesheet_directory_uri() . '/css/style-admin.css?'.random() );
}
add_action( 'admin_enqueue_scripts', 'admin_styles_function' );

function admin_scripts_function(){
   // wp_enqueue_script('typograph', get_template_directory_uri() . '/js/typograph.js', array('jquery-core'), null, true);
   // wp_enqueue_script('typograph_custom', get_template_directory_uri() . '/js/typograph.custom.js?' . random(), array(), null, true);
    wp_enqueue_script('optimize', get_template_directory_uri() . '/js/optimize_text.js?' . random(), array('jquery-core'), null, true);
}
add_action( 'admin_enqueue_scripts', 'admin_scripts_function' );

/* Подключение стилей и скриптов */