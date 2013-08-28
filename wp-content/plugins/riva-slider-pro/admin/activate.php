<?php

function riva_slider_pro_activate() {
    
    // Establish important variables
    $info = riva_slider_pro_info();
    $auto_increment = array( 1, 0 );
    $options = riva_slider_pro_options( 'edit', $auto_increment[ 0 ] );
    $image_options = riva_slider_pro_options( 'image' );
    
    // Create custom user capabilities
    if ( function_exists( 'riva_slider_pro_capabilities' ) )
        riva_slider_pro_capabilities();
    
    if ( get_option( $info[ 'shortname' ] .'_slideshows' ) == false ) {
        
        // Create blank array
        $slideshow = array();
        
        // Push the values
        foreach ( $options as $value ) {
            if ( isset( $value[ 'std' ] ) && $value[ 'type' ] == 'checkbox' ) {
                $std = array( $value[ 'id' ] => '' );
                $slideshow = array_merge( $slideshow, $std );            
            } elseif ( isset( $value[ 'std' ] ) && $value[ 'id' ] != 'edit_section' ) {
                $std = array( $value[ 'id' ] => $value[ 'std' ] );
                $slideshow = array_merge( $slideshow, $std );
            }
        }
        
        // Image source value
        $slideshow[ 'image-source' ] = 'this_panel';
        
        // Add the ID
        $slideshow = array_merge( array( 'index' => $auto_increment[ 0 ] ), $slideshow );
        $slideshow[ 'name' ] = __( 'Slideshow One', 'riva_slider_pro' );
        $slideshow[ 'desc' ] = __( 'This is the first slideshow created when you activated the plugin.', 'riva_slider_pro' );
        $slideshow[ 'images' ] = array();
        $slideshows = array( $slideshow );
        
        // Sanitize variables
        $slideshows = array_map( 'esc_sql', $slideshows );
        $auto_increment[ 0 ] = intval( $auto_increment[ 0 ]+1 );
        $auto_increment[ 1 ] = intval( $auto_increment[ 1 ]+1);
        $auto_increment = array_map( 'esc_sql', $auto_increment );
        
        // Global settings
        $options = riva_slider_pro_options( 'global-settings' );
        
        // Create initial array
        $settings = array();
        
        // Add the settings
        foreach ( $options as $value ) {
            if ( isset( $value[ 'std' ] ) ) {
                $option = array( $value[ 'id' ] => $value[ 'std' ] );
                $settings = array_merge( $settings, $option );
            }
        }
        
        // MD5 tag for dynamic CSS file
        $md5 = md5( mt_rand() );
        
        // Sanitize array
        $settings = array_map( 'esc_sql', $settings );
        
        // Create the options
        if ( riva_slider_pro_check_mu() == true ) {
            $blogs = riva_slider_pro_mu_blog_ids();
            foreach ( $blogs as $blog ) {
                add_blog_option( $blog, $info[ 'shortname' ] .'_slideshows', $slideshows );
                add_blog_option( $blog, $info[ 'shortname' ] .'_auto_increment', $auto_increment );
                add_blog_option( $blog, $info[ 'shortname' ] .'_global_settings', $settings );
                add_blog_option( $blog, $info[ 'shortname' ] .'_update_check', true );
                add_blog_option( $blog, $info[ 'shortname' ] .'_serialcode', true );
                add_blog_option( $blog, $info[ 'shortname' ] .'_dynamic_css', $md5 );
                add_blog_option( $blog, $info[ 'shortname' ] .'_version', RIVA_VERSION );
            }
        }
        else {
            add_option( $info[ 'shortname' ] .'_slideshows', $slideshows );
            add_option( $info[ 'shortname' ] .'_auto_increment', $auto_increment );
            add_option( $info[ 'shortname' ] .'_global_settings', $settings );
            add_option( $info[ 'shortname' ] .'_update_check', true );
            add_option( $info[ 'shortname' ] .'_serialcode', true );
            add_option( $info[ 'shortname' ] .'_dynamic_css', $md5 );
            add_option( $info[ 'shortname' ] .'_version', RIVA_VERSION );
        }
        
        // Create the CSS file
        if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
            riva_slider_pro_dynamic_css();
        
    }
    
    else {
        
        // Get slideshows
        $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
        
        // Update function for any changes
        if ( function_exists( 'riva_slider_pro_update_changes' ) )
            $slideshows = riva_slider_pro_update_changes( $slideshows, $options, $image_options );
        
        // Save changes
        $slideshows = array_map( 'esc_sql', $slideshows );
        $slideshows = array_map( 'stripslashes_deep', $slideshows );
        update_option( $info[ 'shortname' ] .'_slideshows', $slideshows );
        
    }
    
}



/*
    Function that creates options for a new MU blog
*/
function riva_slider_pro_mu_new_blog( $blog_id ) {
    
    // Establish important variables
    $info = riva_slider_pro_info();
    $auto_increment = array( 1, 0 );
    $options = riva_slider_pro_options( 'edit', $auto_increment[ 0 ] );
    $image_options = riva_slider_pro_options( 'image' );
    
    // Create blank array
    $slideshow = array();
    
    // Push the values
    foreach ( $options as $value ) {
        if ( isset( $value[ 'std' ] ) && $value[ 'type' ] == 'checkbox' ) {
            $std = array( $value[ 'id' ] => '' );
            $slideshow = array_merge( $slideshow, $std );            
        } elseif ( isset( $value[ 'std' ] ) && $value[ 'id' ] != 'edit_section' ) {
            $std = array( $value[ 'id' ] => $value[ 'std' ] );
            $slideshow = array_merge( $slideshow, $std );
        }
    }
    
    // Image source value
    $slideshow[ 'image-source' ] = 'this_panel';
    
    // Add the ID
    $slideshow = array_merge( array( 'index' => $auto_increment[ 0 ] ), $slideshow );
    $slideshow[ 'name' ] = __( 'Slideshow One', 'riva_slider_pro' );
    $slideshow[ 'desc' ] = __( 'This is the first slideshow created when you activated the plugin.', 'riva_slider_pro' );
    $slideshow[ 'images' ] = array();
    $slideshows = array( $slideshow );
    
    // Sanitize variables
    $slideshows = array_map( 'esc_sql', $slideshows );
    $auto_increment[ 0 ] = intval( $auto_increment[ 0 ]+1 );
    $auto_increment[ 1 ] = intval( $auto_increment[ 1 ]+1);
    $auto_increment = array_map( 'esc_sql', $auto_increment );
    
    // Global settings
    $options = riva_slider_pro_options( 'global-settings' );
    
    // Create initial array
    $settings = array();
    
    // Add the settings
    foreach ( $options as $value ) {
        if ( isset( $value[ 'std' ] ) ) {
            $option = array( $value[ 'id' ] => $value[ 'std' ] );
            $settings = array_merge( $settings, $option );
        }
    }
    // MD5 tag for dynamic CSS file
    $md5 = md5( mt_rand() );
    
    // Sanitize array
    $settings = array_map( 'esc_sql', $settings );
    
    // Create the options
    add_blog_option( $blog_id, $info[ 'shortname' ] .'_slideshows', $slideshows );
    add_blog_option( $blog_id, $info[ 'shortname' ] .'_auto_increment', $auto_increment );
    add_blog_option( $blog_id, $info[ 'shortname' ] .'_global_settings', $settings );
    add_blog_option( $blog_id, $info[ 'shortname' ] .'_update_check', true );
    add_blog_option( $blog_id, $info[ 'shortname' ] .'_serialcode', true );
    add_blog_option( $blog_id, $info[ 'shortname' ] .'_dynamic_css', $md5 );
    add_blog_option( $blog_id, $info[ 'shortname' ] .'_version', RIVA_VERSION );
    
    // Create the CSS file
    if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
        riva_slider_pro_dynamic_css();
    
}
add_action( 'wpmu_new_blog', 'riva_slider_pro_mu_new_blog' );

?>