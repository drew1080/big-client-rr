<?php

/*
  Function used to load the slideshow's styles
*/

function riva_slider_pro_styles() {
    
    // Plugin info
    $info = riva_slider_pro_info();
    
    // Get dynamic CSS name
    $css = get_option( $info[ 'shortname' ] .'_dynamic_css' );
    
    // Register the scripts
    wp_register_style( 'riva-slider-pro-dynamic-css', riva_slider_pro_path( 'cache/'. $css .'.css' ), false, $info[ 'version' ] );
    
    // Load the scripts
    wp_enqueue_style( 'riva-slider-pro-dynamic-css' );
    
}
add_action( 'wp_print_styles', 'riva_slider_pro_styles' );




/*
	Print CSS in the theme's header if plugin cannot generate dynamic CSS file
*/

function riva_slider_pro_print_styles() {

	// Plugin info
	$info = riva_slider_pro_info();
	
	// Print the CSS if the option exists
	if ( get_option( $info[ 'shortname' ] .'_print_css' ) != false ) {
		echo '<style type="text/css">';
		echo get_option( $info[ 'shortname' ] .'_print_css' );
		echo '</style>';
	}
	
}
add_action( 'wp_head', 'riva_slider_pro_print_styles' );
  
?>