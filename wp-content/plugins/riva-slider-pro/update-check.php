<?php

/*
    Riva Slider Pro update API URL
*/
function riva_slider_pro_update_api( $index = 0 ) {
    $info = riva_slider_pro_info();
    $api = $info[ 'api' ];
    return $api[ $index ];
}





/*
    Get and return the plugin's slug
*/
function riva_slider_pro_slug() {
    $info = riva_slider_pro_info();
    $value = str_replace( '_', '-', $info[ 'shortname' ] );
    return $value;
}




/*
    Get the latest plugin version
*/
function riva_slider_pro_get_latest( $global_settings, $save = false ) {
    
    $info = riva_slider_pro_info();
    if ( empty( $global_settings ) )
	$global_settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    
    if ( ( isset( $global_settings[ 'serial_code' ] ) && $global_settings[ 'serial_code' ] != '' ) ) {
	
	$update = riva_slider_pro_update_api( 1 );
	
	$values = array(
	    'version' => '',
	    'date' => '',
	    'package' => '',
	    'serial' => array(
		'code' => $global_settings[ 'serial_code' ]
	    )
	);
	
	$values = riva_slider_pro_prepare_request( 'version', $values );
	
	$latest = wp_remote_post( $update, $values );
	
	// Check for errors
	if ( $save == false && is_wp_error( $latest ) ) {
	    
	    wp_die( __( 'Something has gone wrong checking your Serial Code. Your site is blocking outbound requests. This may be due to your server running SSL. If your not sure what to do, please contact support.', 'riva_slider_pro' ) );
	    exit();
	    
	}
	elseif ( $save == true && is_wp_error( $latest ) ) {
	    
	    if ( riva_slider_pro_check_mu() == true ) {
		$blogs = riva_slider_pro_mu_blog_ids();
		foreach ( $blogs as $blog ) {
		    update_blog_option( $blog, $info[ 'shortname' ] .'_update_check', false );
		}
	    }
	    else
		update_option( $info[ 'shortname' ] .'_update_check', false );
		
	    return false;
	
	}
	
	$latest = unserialize( $latest[ 'body' ] );
	
	if ( $save == true ) {
	    
	    if ( riva_slider_pro_check_mu() == true ) {
		$blogs = riva_slider_pro_mu_blog_ids();
		foreach ( $blogs as $blog ) {
		    update_blog_option( $blog, $info[ 'shortname' ] .'_update_check', $latest );
		}
	    }
	    else
		update_option( $info[ 'shortname' ] .'_update_check', $latest );
	    
	    if ( is_object( $latest ) && isset( $latest->error ) ) {
		$global_settings[ 'serial_code' ] = '';
		update_option( $info[ 'shortname' ] .'_global_settings', $global_settings );
		update_option( $info[ 'shortname' ] .'_serialcode', true );
	    }
	}
	return $latest;
	
    }
    
}




/*
    Set the latest version into transient
*/
function riva_slider_pro_check_serial( $global_settings ) {
    
    $info = riva_slider_pro_info();
    
    if ( ( isset( $global_settings[ 'serial_code' ] ) && $global_settings[ 'serial_code' ] != '' ) ) {
	
	$latest = riva_slider_pro_get_latest( $global_settings, false );
	if ( isset( $latest->error ) && $latest->error == true ) {
	    return false;
	} else {
	    return true;
	}
    
    }
    
}




/*
    This function checks for plugin updates
*/
function riva_slider_pro_check_updates( $checked_data ) {
    
    $info = riva_slider_pro_info();
    $global_settings = get_option( $info[ 'shortname' ] .'_global_settings' );
    
    if ( ( isset( $global_settings[ 'serial_code' ] ) && $global_settings[ 'serial_code' ] != '' ) ) {
    
	$api = riva_slider_pro_update_api( 0 );
	$update = riva_slider_pro_update_api( 1 );
	$slug = riva_slider_pro_slug();
	
	if ( empty( $checked_data -> checked ) )
	    return $checked_data;
	
	$request = array(
	    'slug' => $slug,
	    'version' => $info[ 'version' ],
	    'latest' => riva_slider_pro_get_latest( false, true ),
	    'serial' => array(
		'code' => $global_settings[ 'serial_code' ]
	    )
	);
	
	if ( is_object( $request[ 'latest' ] ) && !isset( $request[ 'latest' ]->error ) ) {
	    
	    $request = riva_slider_pro_prepare_request( 'update_check', $request );
	    
	    // Start checking for an update
	    $response = wp_remote_post( $api, $request );
	    
	    // Check for errors
	    if ( is_wp_error( $response ) )
		return $checked_data;
	    
	    if ( !is_wp_error( $response ) && ( $response[ 'response' ][ 'code' ] == 200 ) )
		$feed = unserialize( $response[ 'body' ] );
		
	    if ( is_object ( $feed ) && !empty( $feed ) )
		$checked_data -> response[ $slug .'/setup.php' ] = $feed;
	
	}
    }
    
    return $checked_data;

}
//set_site_transient( 'update_plugins', null );
add_filter( 'pre_set_site_transient_update_plugins', 'riva_slider_pro_check_updates', 10 );




/*
    Take control of the plugin information screen when update is available
*/
function riva_slider_pro_update_plugin_info( $def, $action, $args ) {
    
    $action = riva_slider_pro_action();
    $info = riva_slider_pro_info();
    
    if ( isset( $action[ 'plugin' ] ) && $action[ 'plugin' ] == str_replace( '_', '-', $info[ 'shortname' ] ) ) {
	$data = get_option( $info[ 'shortname' ] .'_update_check' );
	return $data;
    }
    
    return false;
    
}
add_filter( 'plugins_api', 'riva_slider_pro_update_plugin_info', 10, 3 );





/*
    Function needed to prepare the request
*/
function riva_slider_pro_prepare_request( $action, $args ) {
    
    // Global variables
    global $wp_version;
    
    // Array to be returned
    return array(
        'body' => array(
            'action' => $action, 
            'request' => serialize( $args ),
	    'api-key' => md5( get_bloginfo( 'url' ) )
	),
	'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' )
    );	
    
}

?>