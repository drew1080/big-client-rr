<?php

/*
    Function used to delete a slideshow
*/
function riva_slider_pro_delete_slideshow( $id, $security ) {
    
    if ( riva_slider_pro_check_caps( 'rivasliderpro_edit_slideshows' ) ) {
    
        if ( !wp_verify_nonce( $security, 'riva-slider-nonce' ) )
            return false;
        
        // Establish important variables
        $info = riva_slider_pro_info();
        
        // Get slideshows & auto increment
        $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
        $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
        
        // Find the ID's array key
        $id = riva_slider_pro_search( $slideshows, 'index', $id, 'id' );
        
        // Remove the key from the $slideshows array & auto increment
        unset( $slideshows[ $id ] );
        unset( $auto_increment[ $id+1 ] );
        
        // Reset array indexes
        $slideshows = array_values( $slideshows );
        $auto_increment = array_values( $auto_increment );
        
        // Sanitize arrays
        $slideshows = array_map( 'esc_sql', $slideshows );
        $auto_increment = array_map( 'esc_sql', $auto_increment );
        
        // Update options
        update_option( $info[ 'shortname' ] .'_slideshows', $slideshows );
        update_option( $info[ 'shortname' ] .'_auto_increment', $auto_increment );
        
        // Generate dynamic CSS
        if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
            riva_slider_pro_dynamic_css( $refresh = true );
        
        return 'deleted';
    
    } else {
        wp_die( __( 'You do not have sufficient permissions to edit slideshows.', 'riva_slider_pro' ) );
        exit();
    }
    
}

?>