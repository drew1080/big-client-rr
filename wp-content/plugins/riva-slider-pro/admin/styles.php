<?php

/*
  Load the admin styles
*/
function riva_slider_pro_admin_styles() {
    
    global $post_type;
    
    $info = riva_slider_pro_info();
    $permalink = $info[ 'permalink' ];
    
    if ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) == true
        || riva_slider_pro_uri( $permalink[ 'new_slideshow' ] ) == true
        || riva_slider_pro_uri( $permalink[ 'global_settings' ] ) == true
        || ( riva_slider_pro_uri( 'post.php' ) == true && $post_type == 'post' )
        || ( riva_slider_pro_uri( 'post-new.php') == true && $post_type == 'post' ) ) {
        
        // Register styles
        wp_register_style( 'rs-pro-admin', riva_slider_pro_path( 'styles/' ) .'rs.pro.admin.css', false, $info[ 'version' ] );
        
        // Load styles
        wp_enqueue_style( 'colors' );
        wp_enqueue_style( 'widgets' );
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'rs-pro-admin' );
      
    }
    
    elseif ( riva_slider_pro_uri( 'post.php' ) == true && $post_type == 'page'
        ||  riva_slider_pro_uri( 'post-new.php' ) == true && $post_type == 'page' ) {
        
        // Register styles
        wp_register_style( 'rs-pro-admin', riva_slider_pro_path( 'styles/' ) .'rs.pro.admin.css', false, $info[ 'version' ] );
        
        // Load styles
        wp_enqueue_style( 'rs-pro-admin' );
        
    }
    
    elseif ( riva_slider_pro_uri( 'upload' ) == true ) {
        
        wp_register_style( 'rs-pro-admin-thickbox', riva_slider_pro_path( 'styles/' ) .'rs.pro.admin.thickbox.css', false, $info[ 'version' ] );
        wp_enqueue_style( 'rs-pro-admin-thickbox' );
        
    }
    
}

add_action( 'admin_enqueue_scripts', 'riva_slider_pro_admin_styles' );

?>