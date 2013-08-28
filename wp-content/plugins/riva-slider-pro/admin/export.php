<?php

function riva_slider_pro_export_settings( $specific = false ) {
    
    // Get info variable
    $info = riva_slider_pro_info();
    
    // Then get the settings
    $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
    $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
    $global_settings = get_option( $info[ 'shortname' ] .'_global_settings' );

    // Get the current time
    $time = date( "Y-m-d-H-i-s" );
    $blog = str_replace( 'http://', '', get_bloginfo( 'url' ) );
    
    // Now encode them
    $backup = base64_encode( serialize( array( $slideshows, $auto_increment, $global_settings ) ) );
    
    // Initiate the .txt file
    header( "Content-Description: File Transfer" );
    header( "Content-Disposition: attachment; filename=rivasliderpro-$blog-$time.txt" );
    header( "Content-Type: pain/text;" );
    
    // Print the codes
    print $backup;
    die();
    
}

?>