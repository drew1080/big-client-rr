<?php

/*
  Function used to load the slideshow's scripts
*/
function riva_slider_pro_scripts() {
    
    global $rs_pro_loaded;
    
    $info = riva_slider_pro_info();
    $settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    
    if ( !is_admin() && $rs_pro_loaded == false ) {
        
	if ( $settings[ 'enable_script' ] == 'true' ) 
	    wp_register_script( 'riva-slider-pro', riva_slider_pro_path( 'scripts/jquery.riva.slider.pro.js' ), false, $info[ 'version' ], true );
        
        // Tell other functions that the script has been registered
	$rs_pro_loaded = true;
    
    }
    
}



/*
    Function that loads the individual slideshows scripting
*/
function riva_slider_pro_slideshow_script( $id, $slideshow, $rand ) {
    
    if ( $slideshow[ 'play_once' ] == 'true' && $slideshow[ 'buttons_play_once' ] == 'true' )
      $playoncebuttons = 'true';
    else
      $playoncebuttons = 'false';
     
    ob_start();
?>
jQuery('#riva-slider-<?php echo esc_attr( $id ); ?>-<?php echo esc_attr( $rand ); ?>').rivaSlider({
width: <?php echo esc_attr( $slideshow[ 'width' ] ); ?>,
height: <?php echo esc_attr( $slideshow[ 'height' ] ); ?>,
preload: <?php echo esc_attr( $slideshow[ 'preload' ] ); ?>,
autoPlay: <?php echo esc_attr( $slideshow[ 'auto_play' ] ); ?>,
playOnce: <?php echo esc_attr( $slideshow[ 'play_once' ] ); ?>,
playOnceNav: <?php echo esc_attr( $playoncebuttons ); ?>,
startImage: <?php if ( $slideshow[ 'start_image' ] == '' ) echo 0; else echo esc_attr( $slideshow[ 'start_image' ] ); ?>,
pauseTime: <?php echo esc_attr( $slideshow[ 'pause_time' ] ); ?>,
pauseTimer: <?php echo esc_attr( $slideshow[ 'pause_timer' ] ); ?>,
transTime: <?php echo esc_attr( $slideshow[ 'trans_time' ] ); ?>,
transition: '<?php echo esc_attr( ucwords( str_replace( '_', ' ', $slideshow[ 'trans_effect' ] ) ) ); ?>',
cubeCols: <?php echo esc_attr( $slideshow[ 'cubes_cols' ] ); ?>,
cubeRows: <?php echo esc_attr( $slideshow[ 'cubes_rows' ] ); ?>,
blindCols: <?php echo esc_attr( $slideshow[ 'blinds_cols' ] ); ?>,
directionNav: '<?php echo esc_attr( $slideshow[ 'direction_nav' ] ); ?>',
directionNavHover: <?php echo esc_attr( $slideshow[ 'direction_nav_hover' ] ); ?>,
controlNav: '<?php echo esc_attr( $slideshow[ 'control_nav' ] ); ?>',
controlNavPos: '<?php echo esc_attr( $slideshow[ 'control_nav_pos' ] ); ?>',
pauseButton: '<?php echo esc_attr( $slideshow[ 'pause_button' ] ); ?>',
pauseButtonHover: <?php echo esc_attr( $slideshow[ 'pause_button_hover' ] ); ?>,
pauseButtonPos: '<?php echo esc_attr( $slideshow[ 'pause_button_pos' ] ); ?>'
});
<?php
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
  
}




/*
    Load slideshow inline scripts into footer
*/
function riva_slider_pro_footer_scripts() {
    
    global $rs_pro_loaded, $rs_pro_scripts;
    
    $info = riva_slider_pro_info();
    $settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    
    if ( $rs_pro_loaded == true ) {
	
	if ( $settings[ 'enable_jquery' ] == 'true' && wp_script_is( 'jquery', 'queue' ) == false )
	    wp_print_scripts( 'jquery' );
    
	if ( $settings[ 'enable_script' ] == 'true' && wp_script_is( 'riva-slider-pro', 'registered' ) == true )
	    wp_print_scripts( 'riva-slider-pro' );
	    
    }
    
    if ( !empty( $rs_pro_scripts ) ) {
        echo '<script type="text/javascript">';
        echo html_entity_decode( "\njQuery.noConflict();" );
        echo html_entity_decode( "\njQuery(document).ready(function() {\n" );
        echo $rs_pro_scripts;
        echo '});';
        echo html_entity_decode( "\n</script>" );
    }
    
}

add_action( 'wp_footer', 'riva_slider_pro_footer_scripts' );

?>