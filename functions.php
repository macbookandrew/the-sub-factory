<?php

// use a custom minified stylesheet and register but don’t enqueue style.css with all the theme information
function add_custom_css() {
    wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/style.min.css' );
    wp_dequeue_style( 'twentysixteen-style' );

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
        $background_color = '#' . get_theme_mod( 'background_color' );
        $main_text_color = get_theme_mod( 'main_text_color' );
        $page_background_color = get_theme_mod( 'page_background_color' );
        $background_color_array = hex2rgb( $background_color );
        $background_color_rgb = $background_color_array['red'] . ',' . $background_color_array['green'] . ',' . $background_color_array['blue'];
        $main_text_color_array = hex2rgb( $main_text_color );
        $main_text_color_rgb = $main_text_color_array['red'] . ',' . $main_text_color_array['green'] . ',' . $main_text_color_array['blue'];

        $custom_styles = '.erm_menu, .erm_product_desc {color:' . esc_attr( $page_background_color ) . ';}
        .erm_menu:before {background-color: rgba(' . $background_color_rgb . ',0.5);}
        .erm_menu:not(.type-erm_menu) {border-top-color: ' . $background_color . ';}
        .ninja-forms-cont {background-color: ' . $background_color . ';}
        .erm_product:nth-child(odd) {background-color: rgba(' . $main_text_color_rgb . ', 0.3);}
        ';

        // loop over posts
        while ( $style_query->have_posts() ) {
            $style_query->the_post();

            // custom background color
            if ( get_field( 'background_color' ) ) {
                $custom_styles .= '.post-id-' . get_the_ID() . ' {background-color:' . get_field( 'background_color' ) . ';}
                .post-id-' . get_the_ID() . ':before {display:none;}' . "\n";
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

// convert hex to rgb
function hex2rgb( $colour ) {
    if ( $colour[0] == '#' ) {
        $colour = substr( $colour, 1 );
    }
    if ( strlen( $colour ) == 6 ) {
        list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
    } elseif ( strlen( $colour ) == 3 ) {
        list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
    } else {
        return false;
    }
    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}
