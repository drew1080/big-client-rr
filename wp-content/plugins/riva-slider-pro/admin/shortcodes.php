<?php

/*
  Shortcode functionality
*/

function riva_slider_pro_shortcode( $atts ) {
  
  extract( shortcode_atts( array(
    "id" => ''
  ), $atts ) );
  
  return riva_slider_pro( $id, false );

}

add_shortcode( 'rivasliderpro', 'riva_slider_pro_shortcode' );

?>