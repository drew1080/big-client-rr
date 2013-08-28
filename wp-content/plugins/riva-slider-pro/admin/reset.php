<?php

/*
    Function that resets the CSS
*/

function riva_slider_pro_admin_reset_css() {
    
    // Generate dynamic CSS
    if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
        riva_slider_pro_dynamic_css( $refresh = true );
    
    return true;
    
}






/*
    Function that resets defaults
*/

function riva_slider_pro_admin_reset_defaults() {
    
    // Delete options
    if ( function_exists( 'riva_slider_pro_delete_plugin' ) )
        riva_slider_pro_delete_plugin();
        
    // Add new options
    if ( function_exists( 'riva_slider_pro_activate' ) )
        riva_slider_pro_activate();
    
    // Generate dynamic CSS
    if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
        riva_slider_pro_dynamic_css( $refresh = true );
    
    return true;
    
}

?>