<?php

/*
    This file generates the static CSS file using PHP
*/

function riva_slider_pro_dynamic_css( $refresh = false, $noerror = false ) {
    
    // Establish some variables
    $info = riva_slider_pro_info();
    $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
    $settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    $dir = riva_slider_pro_dir();
    $nice = riva_slider_pro_dir( true );
    
    // Get main slideshow CSS file
    $css = riva_slider_pro_file_content( 'styles/riva.slider.pro.css' );
    
    // Find the dynamically created CSS file name 
    $md5 = get_option( $info[ 'shortname' ] .'_dynamic_css' );
    
    // If applicable, create a new dynamic CSS and overwrite the old one
    if ( $refresh == true ) {
        if ( file_exists( $dir .'cache/'. $md5 .'.css' ) )
            unlink( $dir .'cache/'. $md5 .'.css' );
        $md5 = md5( mt_rand() );
        update_option( $info[ 'shortname' ] .'_dynamic_css', $md5 );
    }
    
    foreach ( $slideshows as $slideshow ) {
    
        // Relative path to images
        $path = riva_slider_pro_path();
        
        // Check if preloader is enabled or disabled
        if ( $slideshow[ 'preload' ] == 'true' )
            $preload = 'none';
        else
            $preload = 'block';
        
        // Check if preloader is to be transparent. If not, set the colour.
        if ( isset( $slideshow[ 'transparent_preloader' ] ) && $slideshow[ 'transparent_preloader' ] == true )
            $transparent_preloader = 'transparent';
        else
            $transparent_preloader = '#'. $slideshow[ 'preload_colour' ];
            
        // Maths for cubes and blinds
        $cubesheight = ceil( $slideshow[ 'height' ] / $slideshow[ 'cubes_rows' ] );
        $cubeswidth = ceil( $slideshow[ 'width' ] / $slideshow[ 'cubes_cols' ] );
        $blindwidth = ceil( $slideshow[ 'width' ] / $slideshow[ 'blinds_cols' ] );
        
        // Shadow settings
        if ( $slideshow[ 'skin' ] != 'custom' ) {
            $shadow_path = $path .'styles/skins/'. $slideshow[ 'skin' ] .'/shadow.png';
            $shadow = riva_slider_pro_dimensions( $shadow_path, $slideshow );
        }
        else {
            if ( !empty( $slideshow[ 'shadow_image' ] ) ) {
                $shadow_path = $slideshow[ 'shadow_image' ];
                $shadow = riva_slider_pro_dimensions( $shadow_path, $slideshow );
            }
        }
            
        // Control nav top  
        $nav_top = ( $slideshow[ 'height' ] / 2 ) - 14;

// Custom CSS variable
$css .=
<<<CSS


/* Important structural CSS for slideshow id: {$slideshow[ 'index' ]} */

.slider-id-{$slideshow[ 'index' ]},
.slider-id-{$slideshow[ 'index' ]} div.riva-slider-preload,
.slider-id-{$slideshow[ 'index' ]} ul.riva-slider {
    width: {$slideshow[ 'width' ]}px;
    height: {$slideshow[ 'height' ]}px;
}

.slider-id-{$slideshow[ 'index' ]} ul.riva-slider {
    display: {$preload};
}

.slider-id-{$slideshow[ 'index' ]} ul.riva-slider li {
    width: {$slideshow[ 'width' ]}px;
    height: {$slideshow[ 'height' ]}px;
}

.slider-id-{$slideshow[ 'index' ]} div.riva-slider-preload {
    background-image: url({$path}images/loading/{$slideshow[ 'preload-icon' ]});
    background-repeat: no-repeat;
    background-position: center center;
    background-color: {$transparent_preloader};
}

.slider-id-{$slideshow[ 'index' ]} div.rs-next,
.slider-id-{$slideshow[ 'index' ]} div.rs-prev {
    top: {$nav_top}px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-blind {
    height: {$slideshow[ 'height' ]}px;
    width: {$blindwidth}px;
    position: absolute;
    z-index: 3;
}

.slider-id-{$slideshow[ 'index' ]} .rs-blind.no-width {
    width: 0px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-blind.no-height {
    height: 0px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-cube {
    height: {$cubesheight}px;
    width: {$cubeswidth}px;
    position: absolute;
    z-index: 3;
}

.slider-id-{$slideshow[ 'index' ]} .rs-cube.no-dim {
    height: 0px;
    width: 0px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-thumbnails {
    height: {$slideshow[ 'thumb_height' ]}px;
    width: {$slideshow[ 'thumb_width' ]}px;
}
CSS;

if ( isset( $shadow ) ) {
$css .=
<<<CSS


.slider-shadow-{$slideshow[ 'index' ]} {
    width: {$shadow[ 0 ]}px;
    height: {$shadow[ 1 ]}px;
}
CSS;
}

}

    foreach ( $slideshows as $slideshow ) {
    
        // Shadow settings
        if ( $slideshow[ 'skin' ] != 'custom' ) {
            $shadow_path = $path .'styles/skins/'. $slideshow[ 'skin' ] .'/shadow.png';
            $shadow = riva_slider_pro_dimensions( $shadow_path, $slideshow );
        }
        else {
            if ( !empty( $slideshow[ 'shadow_image' ] ) ) {
                $shadow_path = $slideshow[ 'shadow_image' ];
                $shadow = riva_slider_pro_dimensions( $shadow_path, $slideshow );
            }
        }
        
        if ( isset( $slideshow[ 'shadow_image' ] ) ) {
            if ( $settings[ 'resize' ] == 'true' && isset( $shadow ) )
                    $shadow_path = html_entity_decode( riva_slider_pro_image( null, $slideshow[ 'shadow_image' ], $shadow[ 0 ], $shadow[ 1 ] ) );
            else
                $shadow_path = $slideshow[ 'shadow_image' ];
        }
        
        if ( $slideshow[ 'skin' ] != 'custom' ) {
            $this_css = riva_slider_pro_file_content( 'styles/skins/'. $slideshow[ 'skin' ] .'/style.css' );
            $this_css = explode( '*/', $this_css );
            $this_css = str_replace( "\r", '', $this_css );
            unset( $this_css[ 0 ] );
            $this_css = implode( '*/', $this_css );
            $this_css = str_replace( '[id]', $slideshow[ 'index' ], $this_css );
            if ( $settings[ 'resize' ] == 'true' ) {
                $this_css = str_replace( '[timthumb]', $path .'timthumb.php?src=', $this_css );
                $this_css = str_replace( '[shadow_width]', '&w='. $shadow[ 0 ], $this_css );
                $this_css = str_replace( '[shadow_height]', '&h='. $shadow[ 1 ], $this_css );
            } else {
                $this_css = str_replace( '[timthumb]', '', $this_css );
                $this_css = str_replace( '[shadow_width]', '', $this_css );
                $this_css = str_replace( '[shadow_height]', '', $this_css );
            }
            $css .= str_replace( '[url]', $path .'styles/skins/'. $slideshow[ 'skin' ], $this_css );
            
        }
        elseif ( $slideshow[ 'skin' ] == 'custom' ) {
            
            $control_current = $slideshow[ 'control_nav_icon_height' ] * 2;
            $control_hover = $slideshow[ 'control_nav_icon_height' ];
            if ( $slideshow[ 'control_nav_index' ] == 'thumbnails' ) {
                $slideshow[ 'control_nav_icon_width' ] = $slideshow[ 'thumb_width' ];
                $slideshow[ 'control_nav_icon_height' ] = $slideshow[ 'thumb_height' ];
            }
            
            // Direction navigation outside positioning
            $dir_out = $slideshow[ 'width' ] + $slideshow[ 'border_thickness' ];
            if ( $slideshow[ 'direction_nav_pos' ] == 'outside' )
                $slideshow[ 'direction_nav_width' ] = $slideshow[ 'direction_nav_width' ] + $slideshow[ 'direction_nav_margin' ];
                
            // Control navigation margin
            if ( $slideshow[ 'control_nav_pos' ] == 'bottom_center' || $slideshow[ 'control_nav_pos' ] == 'bottom_left' ) {
                $slideshow[ 'control_nav_margin' ][ 3 ] = $slideshow[ 'control_nav_margin' ][ 3 ] - $slideshow[ 'control_nav_margin' ][ 1 ];
                $slideshow[ 'control_nav_margin' ][ 0 ] = $slideshow[ 'control_nav_margin' ][ 0 ] - $slideshow[ 'control_nav_margin' ][ 2 ];
            } elseif ( $slideshow[ 'control_nav_pos' ] == 'bottom_right' ) {
                $slideshow[ 'control_nav_margin' ][ 1 ] = $slideshow[ 'control_nav_margin' ][ 1 ] - $slideshow[ 'control_nav_margin' ][ 3 ];
                $slideshow[ 'control_nav_margin' ][ 0 ] = $slideshow[ 'control_nav_margin' ][ 0 ] - $slideshow[ 'control_nav_margin' ][ 2 ];
            }
            
            // Control navigation thumbnails border
            if ( $slideshow[ 'control_nav_index' ] == 'thumbnails' ) {
                $thumb_margin = array(
                    ( $slideshow[ 'control_nav_thumb_margin' ][ 0 ] - $slideshow[ 'control_nav_current_border_thickness' ] ),
                    ( $slideshow[ 'control_nav_thumb_margin' ][ 1 ] - $slideshow[ 'control_nav_current_border_thickness' ] ),
                    ( $slideshow[ 'control_nav_thumb_margin' ][ 2 ] - $slideshow[ 'control_nav_current_border_thickness' ] ),
                    ( $slideshow[ 'control_nav_thumb_margin' ][ 3 ] - $slideshow[ 'control_nav_current_border_thickness' ] ),
                );
            } else {
                $thumb_margin = array( 0, 0, 0, 0 );
            }
            
            // Control navigation bottom padding for IE
            $ie_padding = $slideshow[ 'control_nav_icon_margin' ][ 2 ] + $slideshow[ 'control_nav_padding' ][ 2 ];
            
            $css .=
<<<CSS


/* Skin CSS for slideshow id: {$slideshow[ 'index' ]} */

.slider-id-{$slideshow[ 'index' ]} {
    border: {$slideshow[ 'border_thickness' ]}px solid #{$slideshow[ 'border_colour' ]};
}

.slider-id-{$slideshow[ 'index' ]} .rs-pause-timer {
    background-color: #{$slideshow[ 'pause_timer_bg' ]}; 
    height: {$slideshow[ 'pause_timer_height' ]}px;
    width: 0px;
    opacity: 0.3;
    position: absolute;
    bottom: 0;
    left: 0;
}

.slider-id-{$slideshow[ 'index' ]}.rs-ie7 .rs-pause-timer,
.slider-id-{$slideshow[ 'index' ]}.rs-ie8 .rs-pause-timer {
    filter: alpha(opacity=30);
}

.slider-id-{$slideshow[ 'index' ]} .rs-next {
    background: url({$slideshow[ 'direction_nav_next_image' ]}) top right no-repeat;
}

.slider-id-{$slideshow[ 'index' ]} .rs-prev {
    background: url({$slideshow[ 'direction_nav_prev_image' ]}) top left no-repeat;
}

.slider-id-{$slideshow[ 'index' ]} .rs-next,
.slider-id-{$slideshow[ 'index' ]} .rs-prev {
    width: {$slideshow[ 'direction_nav_width' ]}px;
    height: {$slideshow[ 'direction_nav_height' ]}px;
}

.slider-id-{$slideshow[ 'index' ]} div.rs-next.inside {
    right: 0px;
}

.slider-id-{$slideshow[ 'index' ]} div.rs-prev.inside {
    left: 0px;
}

.slider-id-{$slideshow[ 'index' ]} div.rs-next.outside {
    left: {$dir_out}px;
}

.slider-id-{$slideshow[ 'index' ]} div.rs-prev.outside {
    right: {$dir_out}px;
}

.slider-id-{$slideshow[ 'index' ]} div.rs-next.inside {
    margin-right: {$slideshow[ 'direction_nav_margin' ]}px !important;
}

.slider-id-{$slideshow[ 'index' ]} div.rs-prev.inside {
    margin-left: {$slideshow[ 'direction_nav_margin' ]}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav {
    background-image: url({$slideshow[ 'control_nav_bg' ][ 0 ]}) !important;
    background-position: {$slideshow[ 'control_nav_bg' ][ 1 ]} {$slideshow[ 'control_nav_bg' ][ 2 ]} !important;
    background-repeat: {$slideshow[ 'control_nav_bg' ][ 3 ]} !important;
    background-color: #{$slideshow[ 'control_nav_bg' ][ 4 ]} !important;
    padding: {$slideshow[ 'control_nav_padding' ][ 0 ]}px {$slideshow[ 'control_nav_padding' ][ 1 ]}px {$slideshow[ 'control_nav_padding' ][ 2 ]}px {$slideshow[ 'control_nav_padding' ][ 3 ]}px !important;
    margin: {$slideshow[ 'control_nav_margin' ][ 0 ]}px {$slideshow[ 'control_nav_margin' ][ 1 ]}px {$slideshow[ 'control_nav_margin' ][ 2 ]}px {$slideshow[ 'control_nav_margin' ][ 3 ]}px !important; 
    -webkit-border-radius-top-left: {$slideshow[ 'control_nav_corners' ][ 0 ]}px;
    -webkit-border-radius-top-right: {$slideshow[ 'control_nav_corners' ][ 1 ]}px;
    -webkit-border-radius-bottom-left: {$slideshow[ 'control_nav_corners' ][ 2 ]}px;
    -webkit-border-radius-bottom-right: {$slideshow[ 'control_nav_corners' ][ 3 ]}px;
    -moz-border-radius-topleft: {$slideshow[ 'control_nav_corners' ][ 0 ]}px;
    -moz-border-radius-topright: {$slideshow[ 'control_nav_corners' ][ 1 ]}px;
    -moz-border-radius-bottomleft: {$slideshow[ 'control_nav_corners' ][ 2 ]}px;
    -moz-border-radius-bottomright: {$slideshow[ 'control_nav_corners' ][ 3 ]}px;
    border-top-left-radius: {$slideshow[ 'control_nav_corners' ][ 0 ]}px;
    border-top-right-radius: {$slideshow[ 'control_nav_corners' ][ 1 ]}px;
    border-bottom-left-radius: {$slideshow[ 'control_nav_corners' ][ 2 ]}px;
    border-bottom-right-radius: {$slideshow[ 'control_nav_corners' ][ 3 ]}px;
}

.slider-id-{$slideshow[ 'index' ]}.rs-ie7 .rs-control-nav {
    padding-bottom: {$ie_padding}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .bottom-left {
    left: -{$slideshow[ 'border_thickness' ]}px;
}

.slider-id-{$slideshow[ 'index' ]} .bottom-right {
    right: -{$slideshow[ 'border_thickness' ]}px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li {
    background-image: url({$slideshow[ 'control_nav_icon_image' ]}) !important;
    background-position: 0px 0px !important;
    background-repeat: no-repeat !important;
    font-size: 14px;
    line-height: 14px;
    vertical-align: middle !important;
    font-weight: bold;
    float: left !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li.rs-icons {
    margin: {$slideshow[ 'control_nav_icon_margin' ][ 0 ]}px {$slideshow[ 'control_nav_icon_margin' ][ 1 ]}px {$slideshow[ 'control_nav_icon_margin' ][ 2 ]}px {$slideshow[ 'control_nav_icon_margin' ][ 3 ]}px !important;
    width: {$slideshow[ 'control_nav_icon_width' ]}px;
    height: {$slideshow[ 'control_nav_icon_height' ]}px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li.rs-icons.current {
    background-position: 0px -{$control_current}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li.rs-icons.hover {
    background-position: 0px -{$control_hover}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li.rs-numbers {
    color: #{$slideshow[ 'control_nav_text_colour' ]} !important;
    margin: {$slideshow[ 'control_nav_number_margin' ][ 0 ]}px {$slideshow[ 'control_nav_number_margin' ][ 1 ]}px {$slideshow[ 'control_nav_number_margin' ][ 2 ]}px {$slideshow[ 'control_nav_number_margin' ][ 3 ]}px !important;
    font-weight: bold;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li.rs-numbers.current {
    color: #{$slideshow[ 'control_nav_text_colour_current' ]} !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li.rs-thumbnails {
    border: {$slideshow[ 'control_nav_border_thickness' ]}px solid #{$slideshow[ 'control_nav_border_colour' ]} !important;
    margin: {$slideshow[ 'control_nav_thumb_margin' ][ 0 ]}px {$slideshow[ 'control_nav_thumb_margin' ][ 1 ]}px {$slideshow[ 'control_nav_thumb_margin' ][ 2 ]}px {$slideshow[ 'control_nav_thumb_margin' ][ 3 ]}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-control-nav li.rs-thumbnails.current {
    border: {$slideshow[ 'control_nav_current_border_thickness' ]}px solid #{$slideshow[ 'control_nav_current_border_colour' ]} !important;
    margin: {$thumb_margin[ 0 ]}px {$thumb_margin[ 1 ]}px {$thumb_margin[ 2 ]}px {$thumb_margin[ 3 ]}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-pause-button {
    width: {$slideshow[ 'pause_button_width' ]}px;
    height: {$slideshow[ 'pause_button_height' ]}px;
    position: absolute;
    background: url({$slideshow[ 'pause_button_image' ]}) 0px 0px no-repeat;
}

.slider-id-{$slideshow[ 'index' ]} .rs-video-button {
    width: {$slideshow[ 'video_button_width' ]}px;
    height: {$slideshow[ 'video_button_height' ]}px;
    background: url({$slideshow[ 'video_button_image' ]}) 0px 0px no-repeat;
    position: absolute;
    display: none;
    opacity: 0;
}

.slider-id-{$slideshow[ 'index' ]} .rs-close-video {
    width: {$slideshow[ 'close_video_width' ]}px;
    height: {$slideshow[ 'close_video_height' ]}px;
    position: absolute;
    bottom: -{$slideshow[ 'close_video_height' ]}px;
    right: 10px;
    display: none;
    background: url({$slideshow[ 'close_video_image' ]}) 0px 0px no-repeat;
    -webkit-border-radius-top-left: {$slideshow[ 'close_video_corners' ][ 0 ]}px;
    -webkit-border-radius-top-right: {$slideshow[ 'close_video_corners' ][ 1 ]}px;
    -webkit-border-radius-bottom-left: {$slideshow[ 'close_video_corners' ][ 2 ]}px;
    -webkit-border-radius-bottom-right: {$slideshow[ 'close_video_corners' ][ 3 ]}px;
    -moz-border-radius-topleft: {$slideshow[ 'close_video_corners' ][ 0 ]}px;
    -moz-border-radius-topright: {$slideshow[ 'close_video_corners' ][ 1 ]}px;
    -moz-border-radius-bottomleft: {$slideshow[ 'close_video_corners' ][ 2 ]}px;
    -moz-border-radius-bottomright: {$slideshow[ 'close_video_corners' ][ 3 ]}px;
    border-top-left-radius: {$slideshow[ 'close_video_corners' ][ 0 ]}px;
    border-top-right-radius: {$slideshow[ 'close_video_corners' ][ 1 ]}px;
    border-bottom-left-radius: {$slideshow[ 'close_video_corners' ][ 2 ]}px;
    border-bottom-right-radius: {$slideshow[ 'close_video_corners' ][ 3 ]}px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-content .rs-content-holder {
    font-family: {$slideshow[ 'text_font' ]} !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-content .rs-content-holder h2 {
    font-size: {$slideshow[ 'text_font_size_h2' ]}px !important;
    padding: {$slideshow[ 'text_font_h2_padding' ][ 0 ]}px {$slideshow[ 'text_font_h2_padding' ][ 1 ]}px {$slideshow[ 'text_font_h2_padding' ][ 2 ]}px {$slideshow[ 'text_font_h2_padding' ][ 3 ]}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-content .rs-content-holder span {
    display: block;
    font-size: {$slideshow[ 'text_font_size_span' ]}px !important;
    padding: {$slideshow[ 'text_font_span_padding' ][ 0 ]}px {$slideshow[ 'text_font_span_padding' ][ 1 ]}px {$slideshow[ 'text_font_span_padding' ][ 2 ]}px {$slideshow[ 'text_font_span_padding' ][ 3 ]}px !important;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-left,
.slider-id-{$slideshow[ 'index' ]} .rs-text-right  {
    top: 0px;
    height: 100%;
    width: 45%;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-left {
    left: 0px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-right {
    right: 0px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-left .rs-content-holder,
.slider-id-{$slideshow[ 'index' ]} .rs-text-right .rs-content-holder {
    height: 100%;
    margin: {$slideshow[ 'text_bg_left_right_margin' ][ 0 ]}px {$slideshow[ 'text_bg_left_right_margin' ][ 1 ]}px {$slideshow[ 'text_bg_left_right_margin' ][ 2 ]}px {$slideshow[ 'text_bg_left_right_margin' ][ 3 ]}px !important;
    border-radius: {$slideshow[ 'text_bg_left_right_corners' ][ 0 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 1 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 2 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 3 ]}px;
    -moz-border-radius: {$slideshow[ 'text_bg_left_right_corners' ][ 0 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 1 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 2 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 3 ]}px;
    -webkit-border-radius: {$slideshow[ 'text_bg_left_right_corners' ][ 0 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 1 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 2 ]}px {$slideshow[ 'text_bg_left_right_corners' ][ 3 ]}px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-top,
.slider-id-{$slideshow[ 'index' ]} .rs-text-bottom {
    left: 0px;
    width: 100%;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-top {
    top: 0px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-bottom {
    bottom: 0px;
}

.slider-id-{$slideshow[ 'index' ]} .rs-text-top .rs-content-holder,
.slider-id-{$slideshow[ 'index' ]} .rs-text-bottom .rs-content-holder {
    width: 100%;
    margin: {$slideshow[ 'text_bg_top_bottom_margin' ][ 0 ]}px {$slideshow[ 'text_bg_top_bottom_margin' ][ 1 ]}px {$slideshow[ 'text_bg_top_bottom_margin' ][ 2 ]}px {$slideshow[ 'text_bg_top_bottom_margin' ][ 3 ]}px !important;
    border-radius: {$slideshow[ 'text_bg_top_bottom_corners' ][ 0 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 1 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 2 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 3 ]}px;
    -moz-border-radius: {$slideshow[ 'text_bg_top_bottom_corners' ][ 0 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 1 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 2 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 3 ]}px;
    -webkit-border-radius: {$slideshow[ 'text_bg_top_bottom_corners' ][ 0 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 1 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 2 ]}px {$slideshow[ 'text_bg_top_bottom_corners' ][ 3 ]}px;

}
CSS;

if ( isset( $shadow ) ) {
$css .=
<<<CSS


.slider-shadow-{$slideshow[ 'index' ]} {
    background: url({$shadow_path}) top center no-repeat;
}
CSS;
}

        }
    
        // Append the new CSS to the static CSS file
        $return = false;
        if ( !is_writable( $dir .'cache/' ) && substr( sprintf( '%o', fileperms( $dir .'cache/' ) ), -4 ) < 0755 ) {
            $disabled = explode( ',', ini_get( 'disable_functions' ) );
            if ( !in_array( 'chmod', $is_disabled ) )
                @chmod( $dir .'cache/', 0755 );
        }
        
        if ( is_writable( $dir .'cache/' ) ) {
            $return = file_put_contents( $dir .'cache/'. $md5 .'.css', $css );
            
            // Check disabled functions and do a chmod if neccessary.
            if ( substr( sprintf( '%o', fileperms( $dir .'cache/'. $md5 .'.css' ) ), -4 ) < 0644 ) {
                $disabled = explode( ',', ini_get( 'disable_functions' ) );
                if ( !in_array( 'chmod', $is_disabled ) )
                    @chmod( $dir .'cache/'. $md5 .'.css', 0644 );
            }
        }
        
        if ( $return === false && $noerror == false ) {
            update_option( $info[ 'shortname' ] .'_print_css', $css );
            wp_die( __( 'Failed to create the dynamic CSS file. This may be due to a file permissions error. Inside your Wordpress directories <b>wp-content/plugins/</b> folder, make sure the <b>riva-slider-pro</b> file permissions are set to <b>755</b>, and the individual files within this folder are set to <b>644</b>. Also try setting file permissions to <b>777</b>. If you are still running into issues, please contact the Riva Slider support.', 'riva_slider_pro' ) );
            exit();
        } else
            delete_option( $info[ 'shortname' ] .'_print_css' );
    }
    
}

?>