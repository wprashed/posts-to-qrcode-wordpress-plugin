<?php
/*
Plugin Name: Posts To QR Code
Plugin URI: https://github.com/safanbd/posts-to-qrcode-wordpress-plugin
Description: Display QR Code under ever posts
Version: 1.0
Author: Bappy
Author URI: https://github.com/safanbd
License: GPLv2 or later
Text Domain: posts-to-qrcode
Domain Path: /languages/
*/

function pqrc_load_textdomain() {
    load_plugin_textdomain( 'posts-to-qrcode', false, dirname( __FILE__ ) . "/languages" );
}

function pqrc_display_qr_code( $content ) {
    $current_post_id    = get_the_ID();
    $current_post_title = get_the_title( $current_post_id );
    $current_post_url   = urlencode( get_the_permalink( $current_post_id ) );
    $current_post_type  = get_post_type( $current_post_id );

    // Post Type Check

    $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );
    if ( in_array( $current_post_type, $excluded_post_types ) ) {
        return $content;
    }

    //Dimension Hook
    $dimension = apply_filters( 'pqrc_qrcode_dimension', '185x185' );

    //Image Attributes
    $image_attributes = apply_filters('pqrc_image_attributes',null);

    $image_src = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $current_post_url );
    $content   .= sprintf( "<div class='qrcode'><img %s  src='%s' alt='%s' /></div>",$image_attributes, $image_src, $current_post_title );

    return $content;
}

add_filter( 'the_content', 'pqrc_display_qr_code' );

