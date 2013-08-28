<?php

/*
    Check if this current site is part of a multi-site platform.
    'riva_slider_pro_image' depends on this function to return the appropriate thumbnail URL
*/
function riva_slider_pro_check_mu() {
    global $wpmu_version;
  
    // Check for multisite functionality
    if ( function_exists( 'is_multisite' ) )
    if ( is_multisite() ) return true;
    
    // Check to see if wpmu version exists
    if ( !empty( $wpmu_version ) ) return true;
    
    // If none of the above is true
    return false;
}




/*
    If using MU, get all of the blog ID's and return them into an array
*/
function riva_slider_pro_mu_blog_ids() {
    global $wpdb;
    $blogs = $wpdb -> get_col( "SELECT blog_id from $wpdb->blogs" );
    $array = array();
    foreach ( $blogs as $blog ) {
        array_push( $array, $blog );
    }
    return $array;
}



/*
    Admin user capabilities
*/
function riva_slider_pro_capabilities() {
    $role = get_role( 'administrator' );
    // Only give the user permissions if they already have permission to edit plugins.
    if ( $role->has_cap( 'edit_plugins' ) ) {
        $role -> add_cap( 'rivasliderpro_view_slideshows' );
        $role -> add_cap( 'rivasliderpro_view_addnew' );
        $role -> add_cap( 'rivasliderpro_view_global_settings' );
        $role -> add_cap( 'rivasliderpro_view_metabox' );
        $role -> add_cap( 'rivasliderpro_view_serial_code' );
        $role -> add_cap( 'rivasliderpro_edit_slideshows' );
        $role -> add_cap( 'rivasliderpro_edit_addnew' );
        $role -> add_cap( 'rivasliderpro_edit_global_settings' );
        $role -> add_cap( 'rivasliderpro_edit_metabox' );
    }
}




/*
    Remove user capabilities
*/
function riva_slider_pro_remove_caps() {
    global $wp_roles;
    $array = array(
        'rivasliderpro_view_slideshows',
        'rivasliderpro_view_addnew',
        'rivasliderpro_view_global_settings',
        'rivasliderpro_view_metabox',
        'rivasliderpro_view_serial_code',
        'rivasliderpro_edit_slideshows',
        'rivasliderpro_edit_addnew',
        'rivasliderpro_edit_global_settings',
        'rivasliderpro_edit_metabox'
    );
    foreach ( $wp_roles->roles as $key => $role ) {
        $role = get_role( $key );
        if ( !is_object( $role ) )
            continue;
        
        foreach ( $array as $cap )
            if ( $role->has_cap( $cap ) )
                $role->remove_cap( $cap );
    }
}




/*
    Check user capabilities function
*/
function riva_slider_pro_check_caps( $cap ) {
    
    if ( !function_exists( 'wp_get_current_user' ) )
        return false;
    
    if ( function_exists( 'current_user_can' ) )
        if ( is_array( $cap ) ) {
            $i = 0;
            foreach ( $cap as $c ) {
                $i++;
                if ( current_user_can( $c ) == false )
                    return false;
                elseif ( $i == count( $cap ) && current_user_can( $c ) )
                    return true;
            }
        } else
            return current_user_can( $cap );
    
}




/*
    Check if this current site is part of a multi-site platform.
    'riva_slider_pro_image' depends on this function to return the appropriate thumbnail URL
*/
function riva_slider_pro_dir( $nice = false ) {
    if ( $nice == true )
        $dir =  plugin_dir_url( __FILE__ );
    else
        $dir = plugin_dir_path( __FILE__ );
        
    return $dir;
}




/*
    A function that opens a file and return its contents
*/
function riva_slider_pro_file_content( $file, $force = false ) {
    
    if ( empty( $force ) && ini_get( 'allow_url_fopen' ) ) {
        
        $content = file_get_contents( riva_slider_pro_dir() . $file );
        if ( empty( $content ) )
            riva_slider_pro_file_content( $file, 'curl' );
        
    }
    elseif ( function_exists( 'curl_version' ) && ( ( !ini_get( 'allow_url_fopen' ) && empty( $force ) ) || $force == 'curl' ) ) {
        
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, riva_slider_pro_dir( true ) . $file );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        
        $content = curl_exec( $curl );
        curl_close( $curl );
        
        if ( empty( $content ) )
            riva_slider_pro_file_content( $file, 'require' );
            
    }
    else {
        
        ob_start();
        require_once( $file );
        $content = ob_get_contents();
        ob_end_clean();
        
        if ( empty( $content ) ) {
            wp_die( 'We have tried everything, but we cannot gain access to the CSS files. Please contact Riva Slider support.', 'riva_slider_pro' );
            exit();
        }
        
    }
    
    // Return the files content
    return $content;

}




/*
    Function used to get image URL from posts/pages featured images
*/
function riva_slider_pro_image( $id = null, $url = null, $width, $height ) {
    global $blog_id;
    $info = riva_slider_pro_info();
    $settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    
    // Check to see if the multi-site check function exists
    if ( function_exists( 'riva_slider_pro_check_mu' ) ) {
        
        if ( $id )
            $path =  wp_get_attachment_url( get_post_thumbnail_id( $id ) );
        elseif ( $url )
            $path = $url;
        
        // If this site is a multi-site enabled site
        if ( riva_slider_pro_check_mu() == true ) {
          
            if ( strpos( $path, get_blog_option( $blog_id, 'fileupload_url' ) ) === true )
                $path = get_current_site(1)->path . str_replace( get_blog_option( $blog_id, 'fileupload_url' ), get_blog_option( $blog_id, 'upload_path' ), $path );
            if ( $settings[ 'resize' ] == 'true' )
                $path = riva_slider_pro_path( "timthumb.php" ) .'?src='. $path .'&amp;h='. $height .'&amp;w='. $width .'&amp;zc=1';
            return $path;
        
        } else {
            
            if ( $settings[ 'resize' ] == 'true' )
                $path = riva_slider_pro_path( "timthumb.php" ) .'?src='. $path .'&amp;h='. $height .'&amp;w='. $width .'&amp;zc=1';
            return $path;
        
        }
    }
}




/*
    Localize the plugin
*/
load_plugin_textdomain( 'riva_slider_pro', false, basename( dirname( __FILE__ ) ) .'/languages' );




/*
    Add links to Wordpress admin bar
*/

function riva_slider_pro_admin_bar() {
    
    // Establish some variables
    global $wp_admin_bar;
    $info = riva_slider_pro_info();
    $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
    
    // Add the top level menu and submenu
    $wp_admin_bar -> add_menu(
        array(
            'id' => 'rs_slideshows',
            'title' => __( 'Slideshows', 'riva_slider_pro' ),
            'href' => admin_url( 'admin.php?page='. $info[ 'permalink' ][ 'slideshows' ] )
        )
    );
    $wp_admin_bar -> add_menu(
       array(
            'parent' => 'rs_slideshows',
            'id' => 'rs_manage_slideshows',
            'title' => __( 'Manage Slideshows', 'riva_slider_pro' ),
            'href' => admin_url( 'admin.php?page='. $info[ 'permalink' ][ 'slideshows' ] )
        )
    );
    $wp_admin_bar -> add_menu(
       array(
            'parent' => 'rs_slideshows',
            'id' => 'rs_global_settings',
            'title' => __( 'Global Settings', 'riva_slider_pro' ),
            'href' => admin_url( 'admin.php?page='. $info[ 'permalink' ][ 'global_settings' ] )
        )
    );
    $wp_admin_bar -> add_menu(
        array(
            'parent' => 'new-content',
            'id' => 'new_slideshow',
            'title' => __( 'Slideshow', 'riva_slider_pro' ),
            'href' => admin_url( 'admin.php?page='. $info[ 'permalink' ][ 'new_slideshow' ] )
        )
    );
}
add_action( 'wp_before_admin_bar_render', 'riva_slider_pro_admin_bar' );




/*
    Establish plugin global variables
*/
function riva_slider_pro_init() {
    
    if ( !is_admin() ) {
        global $rs_pro_load, $rs_pro_scripts;
        
        $rs_pro_load = false;
        $rs_pro_scripts = '';
    }
    
}
add_action( 'init', 'riva_slider_pro_init' );

?>