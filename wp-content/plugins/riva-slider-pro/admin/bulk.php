<?php

/*
    Bulk delete slideshows function
*/
function riva_slider_pro_bulk_delete( $checked, $security ) {
    
    if ( riva_slider_pro_check_caps( 'rivasliderpro_edit_slideshows' ) ) {
    
        if ( !wp_verify_nonce( $security, 'riva-slider-nonce' ) )
            return false;
        
        // Establish important variables
        $info = riva_slider_pro_info();
        
        // Get a list of current slideshows & auto increment
        $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
        $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
        
        // Foreach checked checkbox, get the slideshow and delete it.
        foreach ( $checked as $id ) {
            
            // Get the slideshows array index
            $id = riva_slider_pro_search( $slideshows, 'index', $id, 'id' );
            
            // Delete it from the array & delete its auto increment
            unset( $slideshows[ $id ] );
            unset( $auto_increment[ $id+1 ] );
            
            // Reset the array indexes
            $slideshows = array_values( $slideshows );
            $auto_increment = array_values( $auto_increment );
            
        }
        
        // Reset the indexes again for good measure
        $slideshows = array_values( $slideshows );
        $auto_increment = array_values( $auto_increment );
        
        // Sanitize arrays
        $slideshows = array_map( 'esc_sql', $slideshows );
        $auto_increment = array_map( 'esc_sql', $auto_increment );
        
        // Update options
        update_option( $info[ 'shortname' ] .'_slideshows', $slideshows);
        update_option( $info[ 'shortname' ] .'_auto_increment', $auto_increment );
        
        // Generate dynamic CSS
        if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
            riva_slider_pro_dynamic_css( $refresh = true );
        
        return 'bulk-deleted';
    
    } else {
        wp_die( __( 'You do not have sufficient permissions to edit slideshows.', 'riva_slider_pro' ) );
        exit();
    }
    
}

?>