<?php

// use a custom minified stylesheet and register but don’t enqueue style.css with all the theme information
function add_custom_css() {
    wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/style.min.css' );
    wp_dequeue_style( 'twentysixteen-style' );

    wp_add_inline_style( 'theme-style', $custom_styles );
}
add_action( 'wp_enqueue_scripts', 'add_custom_css', 50 );
