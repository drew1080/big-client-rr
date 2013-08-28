<?php

/*
    Function called when the plugin is deleted from the Wordpress site
*/
function riva_slider_pro_delete_plugin() {
    $info = riva_slider_pro_info();
    $action = riva_slider_pro_action();
    
    if ( riva_slider_pro_check_mu() == true && !isset( $action[ 'reset' ] ) ) {
        $blogs = riva_slider_pro_mu_blog_ids();
        foreach ( $blogs as $blog ) {
            delete_blog_option( $blog, $info[ 'shortname' ] .'_slideshows' );
            delete_blog_option( $blog, $info[ 'shortname' ] .'_auto_increment' );
            delete_blog_option( $blog, $info[ 'shortname' ] .'_global_settings' );
            delete_blog_option( $blog, $info[ 'shortname' ] .'_update_check' );
            delete_blog_option( $blog, $info[ 'shortname' ] .'_serialcode' );
            $md5 = get_site_option( $blog, $info[ 'shortname' ] .'_dynamic_css' );
            if ( file_exists( riva_slider_pro_dir() .'cache/'. $md5 .'.css' ) )
                unlink( riva_slider_pro_dir() .'cache/'. $md5 .'.css' );
            delete_blog_option( $blog, $info[ 'shortname' ] .'_dynamic_css' );
            delete_blog_option( $blog, $info[ 'shortname' ] .'_version' );
        }
    }
    else {
        delete_option( $info[ 'shortname' ] .'_slideshows' );
        delete_option( $info[ 'shortname' ] .'_auto_increment' );
        delete_option( $info[ 'shortname' ] .'_global_settings' );
        delete_option( $info[ 'shortname' ] .'_update_check' );
        delete_option( $info[ 'shortname' ] .'_serialcode' );
        $md5 = get_option( $info[ 'shortname' ] .'_dynamic_css' );
        if ( file_exists( riva_slider_pro_dir() .'cache/'. $md5 .'.css' ) )
            unlink( riva_slider_pro_dir() .'cache/'. $md5 .'.css' );
        delete_option( $info[ 'shortname' ] .'_dynamic_css' );
        delete_option( $info[ 'shortname' ] .'_version' );
    }
    
    // Remove capabilities
    if ( function_exists( 'riva_slider_pro_remove_caps' ) )
        riva_slider_pro_remove_caps();
}

?>