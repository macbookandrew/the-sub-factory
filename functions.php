<?php

// use a custom minified stylesheet and register but don’t enqueue style.css with all the theme information
function add_custom_css() {
    wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/style.min.css' );
    wp_dequeue_style( 'twentysixteen-style' );

    // get menu and form styling
    $custom_styles = '';

    // query arguments
    $style_args = array (
        'post_type'              => array( 'erm_menu', 'nf_sub' ),
        'posts_per_page'         => -1,
        'post_status'            => 'publish',
    );

    // query
    $style_query = new WP_Query( $style_args );

    // The Loop
    if ( $style_query->have_posts() ) {
        while ( $style_query->have_posts() ) {
            $style_query->the_post();

            // custom background color
            if ( get_field( 'background_color' ) ) {
                $custom_styles .= '.post-id-' . get_the_ID() . ' {background-color:' . get_field( 'background_color' ) . ';}' . "\n";
            }

            // custom background image
            if ( get_field( 'background_image' ) ) {
                $custom_styles .= '.post-id-' . get_the_ID() . ' {background-image: url(\'' . get_field( 'background_image' ) . '\');}' . "\n";
            }
        }
    }

    wp_add_inline_style( 'theme-style', $custom_styles );
}
add_action( 'wp_enqueue_scripts', 'add_custom_css', 50 );
