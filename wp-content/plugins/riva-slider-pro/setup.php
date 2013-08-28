<?php

/*
    Plugin Name: Riva Slider Pro
    Plugin URI: http://rivaslider.com/
    Description: Riva Slider Pro is a multi-instance image slideshow Wordpress plugin. Its easy to use interface allows you to quickly create & manage slideshows and insert them into posts/pages. The custom styling panel allows you to make your slideshows truely unique, no coding knowledge needed. Plugin updates will be announced via our <a href="http://twitter.com/#!/RivaSlider">Twitter Page</a>.
    Version: 1.0.6
    Author: Matthew Ruddy
    License: This plugin is licensed under the GNU General Public License. Your rights to usage, updates and support is limited by the license you have purchased. You have agreed to this license agreement in purchasing the plugin. Failure to obey the license agreement will result in your license being immediately terminated without prior notice.
*/

// Define constants
define( 'RIVA_ADMIN', 'admin/' );
define( 'RIVA_VERSION', '1.0.6' );

// Load appropriate files
require_once( RIVA_ADMIN .'activate.php' );
require_once( RIVA_ADMIN .'bulk.php' );
require_once( RIVA_ADMIN .'delete.php' );
require_once( RIVA_ADMIN .'duplicate.php' );
require_once( RIVA_ADMIN .'export.php' );
require_once( RIVA_ADMIN .'functions.php' );
require_once( RIVA_ADMIN .'interface.php' );
require_once( RIVA_ADMIN .'media.php' );
require_once( RIVA_ADMIN .'metabox.php' );
require_once( RIVA_ADMIN .'options.php' );
require_once( RIVA_ADMIN .'reset.php' );
require_once( RIVA_ADMIN .'save.php' );
require_once( RIVA_ADMIN .'scripts.php' );
require_once( RIVA_ADMIN .'shortcodes.php' );
require_once( RIVA_ADMIN .'styles.php' );
require_once( RIVA_ADMIN .'uninstall.php' );
require_once( 'functions.php' );
require_once( 'generate-css.php' );
require_once( 'scripts.php' );
require_once( 'slideshow.php' );
require_once( 'styles.php' );
require_once( 'update-changes.php' );
require_once( 'update-check.php' );
require_once( 'widget.php' );





// Activation & uninstall
register_activation_hook( __FILE__, 'riva_slider_pro_activate' );
register_uninstall_hook( __FILE__, 'riva_slider_pro_delete_plugin' );





// Wordpress thumbnail image sizes
add_image_size( 'riva-slider-pro-admin-image', '510', '170', true );




/*
    Function that returns the plugins path. Important for other functions such as 'riva_slider_pro_image'.
*/
function riva_slider_pro_path( $path = '' ) {
    $url = plugin_dir_url( __FILE__ ) . $path;
    return esc_url_raw( $url );
}

?>