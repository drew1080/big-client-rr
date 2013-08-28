<?php

/*
    New options and change applied when updating
*/
function riva_slider_pro_update_changes( $slideshows, $options, $image_options, $single = false ) {
    
    // Slideshow index
    $index = 0;
    
    // Iterate through each slideshow
    foreach ( $slideshows as $slideshow ) {
        
        // Version specific changes
        $slideshow = riva_slider_pro_version_changes( $slideshow );
        
        // Check for options not already set
        foreach ( $options as $value ) {
            if ( isset( $value[ 'id' ] ) && !isset( $slideshow[ $value[ 'id' ] ] ) && isset( $value[ 'std' ] ) )
                if ( isset( $value[ 'type' ] ) && $value[ 'type' ] == 'checkbox' )
                    $slideshow[ $value[ 'id' ] ] = '';
                else
                    $slideshow[ $value[ 'id' ] ] = $value[ 'std' ];
        }
        
        // Then check for image options not already set
        $image_index = 0;
        if ( is_array( $slideshow[ 'images' ] ) ) {
            foreach ( $slideshow[ 'images' ] as $image ) {
                foreach ( $image_options as $option ) {
                    if ( isset( $option[ 'id' ] ) && !isset( $image[ $option[ 'id' ] ] ) && isset( $option[ 'std' ] ) )
                        $image[ $option[ 'id' ] ] = $option[ 'std' ];
                }
                
                $slideshow[ 'images' ][ $image_index ] = $image;
                $image_index++;
                
            }
        }
        
        // Append to array being imported
        $slideshows[ $index ] = $slideshow;
        $index++;
        
    }
    
    // If only importing a single slideshow
    if ( $single == true )
        $slideshows = $slideshows[ 0 ];
    
    // Return the slideshows
    return $slideshows;

}




/*
    Version specific changes
*/
function riva_slider_pro_version_changes( $slideshow ) {
    if ( isset( $slideshow[ 'text_font' ] ) ) {
        $slideshow[ 'text_font' ] = stripslashes( $slideshow[ 'text_font' ] );
        $slideshow[ 'text_font' ] = str_replace( "\\", "+", $slideshow[ 'text_font' ] );
        $slideshow[ 'text_font' ] = str_replace( "+", "", $slideshow[ 'text_font' ] );
    }
    if ( isset( $slideshow[ 'style_preset' ] ) ) {
        $slideshow[ 'skin' ] = $slideshow[ 'style_preset' ];
        unset( $slideshow[ 'style_preset' ] );
    }
    $array = array(
        'direction_nav_next_image',
        'direction_nav_prev_image',
        'control_nav_icon_image',
        'shadow_image',
        'pause_button_image',
        'video_button_image',
        'close_video_image'
    );
    foreach ( $array as $string ) {
        if ( strpos( $slideshow[ $string ], '/styles/presets/' ) != -1 )
            $slideshow[ $string ] = str_replace( '/styles/presets/', '/styles/skins/', $slideshow[ $string ] );
    }
    if ( strpos( $slideshow[ 'control_nav_bg' ][ 0 ], '/styles/presets/' ) != -1 )
    $slideshow[ 'control_nav_bg' ][ 0 ] = str_replace( '/styles/presets/', '/styles/skins/', $slideshow[ 'control_nav_bg' ][ 0 ] );
    return $slideshow;
}

?>