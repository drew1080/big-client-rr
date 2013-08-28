<?php

/*
    Function used to duplicate a slideshow
*/
function riva_slider_pro_duplicate_slideshow( $id, $security ) {
    
    if ( riva_slider_pro_check_caps( 'rivasliderpro_edit_slideshows' ) ) {
    
        if ( !wp_verify_nonce( $security, 'riva-slider-nonce' ) )
            return false;
    
        // Establish important variables
        $info = riva_slider_pro_info();
        
        // Slideshow auto increment
        $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
        
        // Get slideshows
        $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
        
        // Slideshow to be duplicated
        $duplicated = riva_slider_pro_search( $slideshows, 'index', $id, 'array' );
        $id = riva_slider_pro_search( $slideshows, 'index', $id, 'id' );
        
        // Change the slideshow name & ID
        $duplicated[ 'name' ] = $duplicated[ 'name' ] . __( ' - Copy', 'riva_slider_pro' );
        $duplicated[ 'index' ] = $auto_increment[ 0 ];
        
        // Add it to the current slideshows
        $slideshows = array_merge( $slideshows, array( $duplicated ) );
        
        // Sanitize variables
        $slideshows = array_map( 'esc_sql', $slideshows );
        $slideshows = array_map( 'stripslashes_deep', $slideshows );
        $auto_increment[ 0 ] = intval( $auto_increment[ 0 ]+1 );
        $auto_increment[ count( $auto_increment ) ] = intval( $auto_increment[ $id+1 ] );
        
        // Sanitize the auto increment as an array
        $auto_increment = array_map( 'esc_sql', $auto_increment );
        
        // Update the slideshows & auto increment
        update_option( $info[ 'shortname' ] .'_slideshows', $slideshows );
        update_option( $info[ 'shortname' ] .'_auto_increment', $auto_increment );
          
        // Generate dynamic CSS
        if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
            riva_slider_pro_dynamic_css( $refresh = true );
        
        return 'duplicated';
    
    } else {
        wp_die( __( 'You do not have sufficient permissions to edit slideshows.', 'riva_slider_pro' ) );
        exit();
    }
    
}

?>