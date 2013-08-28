<?php

/*
    This is function is used to return vital plugin information.
    It containts the plugin name, permalink information, shortened name and version number.
*/
function riva_slider_pro_info( $type = '' ) {
    $info = array(
        'pluginname' => 'Riva Slider Pro',
        'permalink' => array( 'slideshows' => 'rs_manage_slideshows', 'new_slideshow' => 'rs_new_slideshow', 'global_settings' => 'rs_global_settings' ),
        'shortname' => 'riva_slider_pro',
        'version' => RIVA_VERSION,
        'api' => array( 'http://rivaslider.com/wp-content/plugins/riva-slider-api/api.php', 'http://rivaslider.com/wp-content/plugins/riva-slider-api/update.php' )
    );
    if ( $type != '' ) { return $info[ $type ]; }
    else { return $info; }
}




/*
    Search function used to obtain information about a slideshow from its ID.
    It takes the ID and find its' appropriate array key, then returning either the key or entire array.
*/
function riva_slider_pro_search( $slideshows, $key, $value, $return = 'array' ) {
    // Foreach array within the $slideshows array, this variable is incremented. It is then used to determine the array index of the matched ID.
    $id = 0;
    $found = false;
    if ( is_array( $slideshows ) ) {
        foreach ( $slideshows as $slideshow ) {
            if ( $slideshow[ $key ] == $value ) {
                // Return either the entire array, or its array index within the $slideshows array.
                if ( $return == 'array' ) return $slideshow;
                elseif ( $return == 'id' ) return $id;
                elseif ( $return == 'boolean' )
                    if ( isset ( $slideshow ) )
                        return true;
                    else
                        return false;
            }
        $id++; }
    }
}




/*
    Ajax nonce function
*/
function riva_slider_pro_nonce( $name = false ) {
    $nonce = wp_create_nonce( 'riva-slider-nonce' );
    return esc_attr( $nonce );
}




/*
    Video link function
*/
function riva_slider_pro_video_url( $url, $rel = 'false', $auto = 'false' ) {
    
    if ( strpos( $url, 'youtube' ) ) {
        if ( strpos( $url, 'watch?v=' ) ) {
            $position = strpos( $url, 'watch?v=' );
            $split = str_split( $url, $position - 1 );
            $url = $split[ 0 ];
            $embed = explode( '&', $split[ 1 ] );
            $embed = explode( '?', $embed[ 0 ] );
            $url = $url . str_replace( 'watch?v=', 'embed/', $embed[ 0 ] .'?'. $embed[ 1 ] );    
        }
        
        if ( $rel == 'true' )
            $url .= '?rel=1';
        else
            $url .= '?rel=0';
        
        if ( $auto == 'true' )
            $url .= '&autoplay=1';
            
    }
    elseif ( strpos( $url, 'vimeo' ) ) {
        if ( !strpos( $url, 'player.vimeo.com' ) ) {
            $url = str_replace( 'vimeo.com/', 'player.vimeo.com/video/', $url );
        }
        
        if ( $auto == 'true' )
            $url .= '?autoplay=1';
            
    }
    
    return $url;

}

  


/*
    Get the sites post categories and return them in an array.
*/
function riva_slider_pro_categories( $type = 'post' ) {
    
    // Get categories in an array
    $categories = get_categories( "type=$type" );
    $array = array();
    
    // Add the 'All Categories' info
    $all = array( 'name' => __( 'all_categories', 'riva_slider_pro' ), 'id' => 0, 'count' => 0, 'images' => 0 );
    array_push( $array, $all );
    
    // Create the array of info
    foreach ( $categories as $cat ) {
        
        // Category name
        $name = str_replace( '-', '_', $cat->category_nicename );
        
        // Get the amount of posts with Riva Slider images set to them
        $query = array( 'post_type' => 'post', 'cat' => $cat->cat_ID );
        
        $index = 0;
        $posts = new WP_Query( $query );
        while ( $posts -> have_posts() ) : $posts -> the_post();
            global $post;
            $image = get_post_meta( $post->ID, '_rivasliderpro', true );
            if ( isset( $image[ 'image-url' ] ) && $image[ 'image-url' ] != '' )
                $index++;
        endwhile;
        wp_reset_query();
        
        // Our info array
        $info = array( 'name' => $name, 'id' => $cat->cat_ID, 'count' => $cat->count, 'images' => $index );
        array_push( $array, $info );

    }
    
    // Add the total number of images to 'All Categories' option
    $total = 0;
    foreach ( $array as $info ) {
        $total = $total + $info[ 'images' ];
    }
    $array[ 0 ][ 'images' ] = $total;

    
    // Return that array
    return $array;

}





/*
    Check the current URI for certain variables
*/
function riva_slider_pro_uri( $search ) {
    
    // Get the URI
    $uri = $_SERVER[ 'REQUEST_URI' ];
    
    // Check
    if ( strpos( $uri, $search ) != false && strpos( $uri, $search ) != 0 && strpos( $uri, $search ) != '' )
        return true;
    else
        return false;
    
}




/*
    Used to determine the current action.
    Derived from the current URL
*/
function riva_slider_pro_action() {
    
    // Important variables
    $info = riva_slider_pro_info();
    $uri = $_SERVER[ 'REQUEST_URI' ];
    
    // Get the actions and place them into one array
    $array = array();
    $actions = explode( '&', $uri );
    if ( is_array( $actions ) ) {
        unset( $actions[ 0 ] );
        foreach( $actions as $action ) {
            $action = explode( '=', $action );
            if ( isset( $action[ 0 ] ) && isset( $action[ 1 ] ) ) {
                $key = $action[ 0 ];
                $value = $action[ 1 ];
                $array = array_merge( $array, array( $key => $value ) );
            }
        }
    }
    return $array;
    
}





/*
    Call before any page has loaded
*/
function riva_slider_pro_admin_all_init() {
	$info = riva_slider_pro_info();

	if ( function_exists( 'riva_slider_pro_dir' ) && !file_exists( riva_slider_pro_dir() .'cache/'. get_option( $info[ 'shortname' ] .'_dynamic_css' ) .'.css' ) )
	    if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
                riva_slider_pro_dynamic_css( true, true );

	if ( get_option( $info[ 'shortname' ] .'_version' ) == false )
	    add_option( $info[ 'shortname' ] .'_version', RIVA_VERSION );
        elseif ( get_option( $info[ 'shortname' ] .'_version' ) < RIVA_VERSION )
            update_option( $info[ 'shortname' ] .'_version', RIVA_VERSION );
    
        // Load media uploader tab
        if ( class_exists( 'RSPMediaUploader' ) )
            $rspuploader = new RSPMediaUploader();
}
add_action( 'admin_init', 'riva_slider_pro_admin_all_init' );






/*
    Call before Riva page has loaded
*/
function riva_slider_pro_admin_init() {
    
    // Variables
    $action = riva_slider_pro_action();
    $info = riva_slider_pro_info();
    $permalink = $info[ 'permalink' ];
    
    // Check to see we are on a Riva Slider page
    if ( isset( $_GET[ 'page' ] ) )
        if ( !in_array( $_GET[ 'page' ], $permalink ) )
            return;
    
    // Database values
    $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
    $global_settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    $update_check = get_option( $info[ 'shortname' ] .'_update_check' );
    
    if ( function_exists( 'riva_slider_pro_check_mu' ) && riva_slider_pro_check_mu() == true && ( !isset( $update_check->error ) ) ) {
        if ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) || riva_slider_pro_uri( $permalink[ 'new_slideshow' ] ) || riva_slider_pro_uri( $permalink[ 'global_settings' ] ) ) {
            if ( get_option( $info[ 'shortname' ] .'_serialcode' ) ) {
                $blogs = riva_slider_pro_mu_blog_ids();
                $codes = array();
                foreach ( $blogs as $blog ) {
                    $settings = get_blog_option( $blog, $info[ 'shortname' ] .'_global_settings' );
                    if ( isset( $settings[ 'serial_code' ] ) && !empty( $settings[ 'serial_code' ] ) ) {
                        array_push( $codes, $settings[ 'serial_code' ] );
                    }
                }
                if ( is_array( $codes ) && count( $codes ) >= 1 ) {
                    $global_settings[ 'serial_code' ] = $codes[ 0 ];
                    $boolean = update_option( $info[ 'shortname' ] .'_global_settings', $global_settings );
                    if ( $boolean == true ) {
                        $global_settings = get_option( $info[ 'shortname' ] .'_global_settings' );
                        delete_option( $info[ 'shortname' ] .'_serialcode' );
                    }
                }
            }
        }
    }
    
    // Various things to do
    if ( isset( $_POST[ 'save' ] ) ) {
        if ( riva_slider_pro_uri( $permalink[ 'global_settings' ] ) ) {
            if ( function_exists( 'riva_slider_pro_save_global_settings' ) ) {
                $message = riva_slider_pro_save_global_settings();
                wp_safe_redirect( admin_url().'admin.php?page='.$permalink[ 'global_settings' ].'&message='.$message );
            }
        } else {
            if ( function_exists( 'riva_slider_pro_save_slideshow' ) ) {
                if ( !isset( $action[ 'id' ] ) )
                    $action[ 'id' ] = $auto_increment[ 0 ];
                $message = riva_slider_pro_save_slideshow();
                wp_safe_redirect( admin_url().'admin.php?page='.$permalink[ 'slideshows' ].'&id='.$action[ 'id' ].'&message='.$message );
            }
        }
    }
    elseif ( isset( $action[ 'duplicate' ] ) ) {
        if ( function_exists( 'riva_slider_pro_duplicate_slideshow' ) ) {
            $message = riva_slider_pro_duplicate_slideshow( $action[ 'duplicate' ], $action[ 'security' ] );
            wp_safe_redirect( admin_url().'admin.php?page='.$permalink[ 'slideshows' ].'&message='.$message ); 
        }
    }
    elseif ( isset( $action[ 'delete' ] ) ) {
        if ( function_exists( 'riva_slider_pro_delete_slideshow' ) ) {
            $message = riva_slider_pro_delete_slideshow( $action[ 'delete' ], $action[ 'security' ] );
            wp_safe_redirect( admin_url().'admin.php?page='.$permalink[ 'slideshows' ].'&message='.$message ); 
        }
    }
    elseif ( isset( $_POST[ 'doaction' ] ) ) {
        if ( isset( $_POST[ 'bulkid' ] ) && is_array( $_POST[ 'bulkid' ] ) ) {
            if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'delete' )
                $message = riva_slider_pro_bulk_delete( $_POST[ 'bulkid' ], $_POST[ 'security' ] );
            wp_safe_redirect( admin_url().'admin.php?page='.$permalink[ 'slideshows' ].'&message='.$message );
        }
    }
    elseif ( isset( $action[ 'reset' ] ) ) {
        if ( $action[ 'reset' ] == 'css' ) {
            if ( function_exists( 'riva_slider_pro_admin_reset_css' ) ) {
                $die = riva_slider_pro_admin_reset_css();
                if ( $die == true )
                    wp_safe_redirect( admin_url().'admin.php?page='.$permalink[ 'global_settings' ].'&message=reset-css' );
            }
        }
        elseif ( $action[ 'reset' ] == 'defaults' ) {
            if ( function_exists( 'riva_slider_pro_admin_reset_defaults' ) ) {
                $die = riva_slider_pro_admin_reset_defaults();
                if ( $die == true )
                    wp_safe_redirect( admin_url().'admin.php?page='.$permalink[ 'global_settings' ].'&message=reset-defaults' );
            }
        }
    }
    elseif ( isset( $action[ 'export' ] ) ) {
        riva_slider_pro_export_settings();
    }
}
add_action( 'load-toplevel_page_rs_manage_slideshows', 'riva_slider_pro_admin_init' );
add_action( 'load-slideshows_page_rs_new_slideshow', 'riva_slider_pro_admin_init' );
add_action( 'load-slideshows_page_rs_global_settings', 'riva_slider_pro_admin_init' );





/*
    Returns a message from the permalink
*/
function riva_slider_pro_message( $action ) {
    
    // Set the right message
    if ( $action[ 'message' ] == 'created' )
        $die = __( 'Slideshow has been created successfully.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'saved' )
        $die = __( 'Slideshow has successfully been saved.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'duplicated' )
        $die = __( 'Slideshow has successfully been duplicated.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'deleted' )
        $die = __( 'Slideshow has been deleted.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'bulk-deleted' )
        $die = __( 'Bulk delete successful. Slideshow(s) have been deleted.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'import-success' )
        $die = __( 'Slideshow settings have been imported.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'import-failed' )
        $die = __( 'Imported settings not valid. Please try again.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'global-saved' )
        $die = __( 'Global settings have successfully been saved.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'serial-invalid' )
        $die = __( 'Global setting saved, but the serial code you have entered invalid. Please enter a valid one to receive update notifications.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'global-imported' )
        $die = __( 'Global settings have successfully been imported.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'reset-css' )
        $die = __( 'CSS has successfully been reset.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'reset-defaults' )
        $die = __( 'Plugin settings have been reset successfully.', 'riva_slider_pro' );
    elseif ( $action[ 'message' ] == 'enter-serial' )
        $die = __( 'Please enter a serial code. Check the email you received upon purchase for more information.', 'riva_slider_pro' );
    else
        $die = __( 'An unknown error has occurred. Please try again.', 'riva_slider_pro' );
    
    // Return the message
    return $die;

}





/*
    Returns the appropriate interface depending on the current query
*/
function riva_slider_pro_slideshows() {
    
    // Get some variables
    $action = riva_slider_pro_action();
    $info = riva_slider_pro_info();
    $permalink = $info[ 'permalink' ];
    $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
    
    
    if ( riva_slider_pro_uri( $permalink[ 'new_slideshow' ] ) )
        riva_slider_pro_interface(
            riva_slider_pro_options( 'new', $auto_increment[ 0 ] )   
        );
    elseif ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) && isset( $action[ 'id' ] ) )
        riva_slider_pro_interface(
            riva_slider_pro_options( 'edit', $action[ 'id' ] ),
            $action[ 'id' ]
        );
    elseif ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) && isset( $action[ 'orderby' ] ) && isset( $action[ 'order' ] ) )
        riva_slider_pro_interface(
            riva_slider_pro_options(),
            false,
            $action[ 'orderby' ],
            $action[ 'order' ]
        );
    elseif ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) && !isset( $action[ 'id' ] ) )
        riva_slider_pro_interface(
            riva_slider_pro_options()
        );
        
}





/*
    Returns the global settings interface
*/
function riva_slider_pro_global_settings() {
    
    riva_slider_pro_interface(
        riva_slider_pro_options( 'global-settings' ),
        null,
        null,
        null,
        true
    );
    
}





/*
    Function used to re-order a specific array
*/
function riva_slider_pro_order_array( $array, $index, $order ) {
    
    // This is a blank array to be filled full of the keys we wish to sort the arrays by
    $keys = array();
    
    // Push these keys from the original array into a new array
    foreach ( $array as $single ) {
      array_push( $keys, $single[ $index ] );
    }
    
    // Sort them alphabetically ascending or descending
    if ( $order == 'asc' ) { asort( $keys ); }
    elseif ( $order == 'desc' ) { arsort( $keys ); }
    
    // Create the array to be outputted
    $new_order = array();
    
    // Find the entire arrays via the keys then push them into a new array in their new sorted order
    foreach ( $keys as $key ) {
      $new_array = riva_slider_pro_search( $array, $index, $key, 'array' );
      array_push( $new_order, $new_array );
    }
    
    return $new_order;
    
}





/*
    Function that returns the slider skins from their directory
*/
function riva_slider_pro_skins() {
    
    // Get the skins
    $dir = riva_slider_pro_dir();
    $skins = scandir( $dir .'styles/skins/' );
    
    // Remove the '.' and '..'
    unset( $skins[ 0 ], $skins[ 1 ] );
    
    // Create a new array
    $array = array();
    
    foreach ( $skins as $skin ) {
        
        if ( is_dir( $dir .'styles/skins/'. $skin .'/' ) ) {
        
            // Get file contents
            $file = file_get_contents( $dir .'styles/skins/'. $skin .'/style.css' );
            $info = explode( '*/', $file );
            $info = str_replace( '/*', '', $info[ 0 ] );
            
            $name = explode( 'skin name:', strtolower( $info ) );
            $name = explode( "\n", $name[ 1 ] );
            $name = trim( $name[ 0 ] );
            
            $array = array_merge( $array, array( $skin => $name ) );
            
        }
        
    }
    
    $array = array_merge( $array, array( 'custom' => 'Custom' ) );
    
    // Return the array
    return $array;
}





/*
    Function that gets an images dimensions
*/
function riva_slider_pro_dimensions( $path, $slideshow ) {
    
    // Some variables
    $info = riva_slider_pro_info();
    $settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    
    if ( ini_get( 'allow_url_fopen' ) == 1 && $settings[ 'resize' ] == 'true' ) {
        $dimensions = getimagesize( $path );
        $dimensions = array(
          $slideshow[ 'width' ],
          ceil( $dimensions[ 1 ] * ( $slideshow[ 'width' ] / $dimensions[ 0 ] ) )
        );
        return $dimensions;
    }
    elseif ( ini_get( 'allow_url_fopen' ) != 1 && function_exists( 'curl_version' ) && $settings[ 'resize' ] == 'true' ) {
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $path );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        
        $content = curl_exec( $curl );
        curl_close( $curl );
        
        if ( !empty( $content ) ) {
            $path = imagecreatefromstring( $content );
            if ( $path != false ) {
                imagejpeg( $path, 'temp.jpg', 100 );
                
                $dimensions = getimagesize( 'temp.jpg' );
                $dimensions = array(
                  $slideshow[ 'width' ],
                  ceil( $dimensions[ 1 ] * ( $slideshow[ 'width' ] / $dimensions[ 0 ] ) )
                );
                return $dimensions;
            }
        }
    }
    
    return array( $slideshow[ 'width' ], 60 );
    
}





/*
    Creates the admin menus
*/
function riva_slider_pro_menu() {
  $info = riva_slider_pro_info();
  $permalink = $info[ 'permalink' ];
  $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
  $options_page = add_object_page(
    __( 'Slideshows', 'riva_slider_pro' ),
    __( 'Slideshows', 'riva_slider_pro'),
    'rivasliderpro_view_slideshows',
    $permalink[ 'slideshows' ],
    'riva_slider_pro_slideshows'
  );
  add_submenu_page(
    $permalink[ 'slideshows' ],
    __( $info[ 'pluginname' ], 'riva_slider_pro' ),
    __( 'Add New', 'riva_slider_pro' ),
    'rivasliderpro_view_addnew',
    $permalink[ 'new_slideshow' ],
    'riva_slider_pro_slideshows'
  );
  add_submenu_page(
    $permalink[ 'slideshows' ],
    __( $info[ 'pluginname' ], 'riva_slider_pro' ),
    __( 'Global Settings', 'riva_slider_pro' ),
    'rivasliderpro_view_global_settings',
    $permalink[ 'global_settings' ],
    'riva_slider_pro_global_settings'
  );
}
add_action( 'admin_menu', 'riva_slider_pro_menu' );





/*
    Admin area javascript variables
*/
function riva_slider_pro_javascript() {
    
    $info = riva_slider_pro_info();
    $permalink = $info[ 'permalink' ];
    
    if ( ( is_admin() && isset( $_GET[ 'page' ] ) ) ) {
        if ( ( $_GET[ 'page' ] == $permalink[ 'slideshows' ] ) || ( $_GET[ 'page' ] == $permalink[ 'new_slideshow' ] ) || ( $_GET[ 'page' ] == $permalink[ 'global_settings' ] ) ) { ?>
    
    <script type="text/javascript">
        slideshows_url = "<?php echo get_bloginfo( 'wpurl' ); ?>/wp-admin/admin.php?page=<?php echo esc_attr( $info[ 'permalink' ][ 'slideshows' ] ); ?>";
        global_settings_url = "<?php echo get_bloginfo( 'wpurl' ); ?>/wp-admin/admin.php?page=<?php echo esc_attr( $info[ 'permalink' ][ 'global_settings' ] ); ?>";
        addnew = "<?php _e( 'Riva Slider Pro', 'riva_slider_pro' ); ?>";
        no_name = "<?php _e( 'No name has been entered for the slideshow. Please enter a slideshow name.', 'riva_slider_pro' ); ?>";
        image = "<?php _e( 'Image #', 'riva_slider_pro'); ?>";
        image_bg = "<?php echo esc_attr( riva_slider_pro_path( 'images/image_bg.jpg' ) ); ?>";
        image_none = "<?php echo esc_attr( riva_slider_pro_path( 'images/no_image.jpg' ) ); ?>";
        edit_image = "<?php _e( 'Click to edit', 'riva_slider_pro' ); ?>";
        close_image = "<?php _e( 'Close editor', 'riva_slider_pro' ); ?>";
        delete_all_images = "<?php _e( 'Are you sure you want to delete all the images?', 'riva_slider_pro' ); ?>";
        enter_name = "<?php _e( 'Please enter a slideshow name', 'riva_slider_pro' ); ?>";
        close_message = "<?php _e( 'Close', 'riva_slider_pro' ); ?>";
        no_images = "<?php _e( 'This slideshow currently has no images', 'riva_slider_pro' ); ?>";
        alt_source = "<?php _e( 'Slideshow is currently set to use Images From Posts. Therefore, this panel is currently disabled. To enable it, change the image source on the right-hand side.', 'riva_slider_pro' ); ?>";
        pause_pos_text = "<?php _e( ' - Pause Button positioned here', 'riva_slider_pro' ); ?>";
        control_nav_disabled = "<?php _e( ' - Styling disabled. Slideshow is set to use thumbnails', 'riva_slider_pro' ); ?>";
        confirm_reset_settings = "<?php _e( 'Are you sure you wish to Reset All Slideshows & Settings? (They will be lost and cannot be recovered).', 'riva_slider_pro' ); ?>";
    </script>
    
    <?php }
    }
    
}
add_action( 'admin_head', 'riva_slider_pro_javascript' );

?>