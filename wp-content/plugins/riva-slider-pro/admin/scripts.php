<?php

/*
  Load the admin scripts
*/
function riva_slider_pro_admin_scripts() {
    
    global $post_type;
  
    $info = riva_slider_pro_info();
    $permalink = $info[ 'permalink' ];
    
    if ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) == true
        || riva_slider_pro_uri( $permalink[ 'new_slideshow' ] ) == true
        || riva_slider_pro_uri( $permalink[ 'global_settings' ] ) == true
        || ( riva_slider_pro_uri( 'post.php' ) == true && $post_type == 'post' )
        || ( riva_slider_pro_uri( 'post-new.php') == true && $post_type == 'post' ) ) {
      
      // Register scripts
      wp_register_script( 'rs-pro-admin-jquery', riva_slider_pro_path( 'scripts/jquery.rs.pro.admin.js' ), array( 'jquery', 'thickbox', 'media-upload' ), $info[ 'version' ], true );
      wp_register_script( 'rs-pro-media-jquery', riva_slider_pro_path( 'scripts/jquery.rs.pro.media.js' ), array( 'jquery', 'thickbox', 'media-upload' ), $info[ 'version' ], true );
      
      // Load scripts
      wp_enqueue_script( 'thickbox' );
      wp_enqueue_script( 'media-upload' );
      wp_enqueue_script( 'rs-pro-admin-jquery' );
      wp_enqueue_script( 'rs-pro-media-jquery' );
      wp_enqueue_script( 'jquery-ui-sortable' );
      
    }
    
    elseif ( riva_slider_pro_uri( 'post.php' ) == true && $post_type == 'page'
        ||  riva_slider_pro_uri( 'post-new.php' ) == true && $post_type == 'page' ) {
      
      // Register scripts
      wp_register_script( 'rs-pro-admin-jquery', riva_slider_pro_path( 'scripts/jquery.rs.pro.admin.js' ), array( 'jquery', 'thickbox', 'media-upload' ), $info[ 'version' ], true );
      
      // Load scripts
      wp_enqueue_script( 'rs-pro-admin-jquery' );
        
    }
    
}

add_action( 'admin_enqueue_scripts', 'riva_slider_pro_admin_scripts' );

?>